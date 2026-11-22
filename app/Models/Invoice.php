<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'discount_type',
        'discount_value',
        'discount_amount',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'remaining_balance',
        'payment_status',
        'payment_method',
        'notes',
        'terms',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount_value' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'remaining_balance' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePartial(Builder $query): Builder
    {
        return $query->where('payment_status', 'partial');
    }

    public function scopeUnpaid(Builder $query): Builder
    {
        return $query->where('payment_status', 'unpaid');
    }

    public static function generateNextInvoiceNumber(): string
    {
        $year = date('Y');
        $prefix = "INV-{$year}-";

        $last = self::where('invoice_number', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->first();

        if ($last && preg_match('/INV-\d{4}-(\d+)/', $last->invoice_number, $matches)) {
            $nextSequence = (int) $matches[1] + 1;
        } else {
            $nextSequence = 1;
        }

        return $prefix.str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Recalculate totals and payment status.
     */
    public function recalculateTotals(): void
    {
        $this->subtotal = $this->items()->sum('total_price') ?: 0;

        if ($this->discount_type === 'percentage') {
            $this->discount_amount = round(($this->subtotal * ($this->discount_value / 100)), 2);
        } else {
            $this->discount_amount = min($this->subtotal, $this->discount_value);
        }

        $afterDiscount = max(0, $this->subtotal - $this->discount_amount);
        $this->tax_amount = round($afterDiscount * ($this->tax_rate / 100), 2);
        $this->total_amount = $afterDiscount + $this->tax_amount;
        $this->remaining_balance = max(0, $this->total_amount - $this->paid_amount);

        if ($this->paid_amount >= $this->total_amount && $this->total_amount > 0) {
            $this->payment_status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->payment_status = 'partial';
        } else {
            $this->payment_status = 'unpaid';
        }
    }
}
