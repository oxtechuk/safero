<?php

namespace App\Http\Controllers;

use App\Models\Trainee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TraineeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Trainee::withCount('certificates');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('national_id', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $trainees = $query->latest('id')->paginate(15)->withQueryString();

        return view('trainees.index', compact('trainees'));
    }

    public function create(): View
    {
        return view('trainees.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $trainee = Trainee::create($validated);

        return redirect()->route('trainees.index')
            ->with('success', 'تمت إضافة المتدرب بنجاح: '.$trainee->name);
    }

    public function show(Trainee $trainee): View
    {
        $trainee->load('certificates');

        return view('trainees.show', compact('trainee'));
    }

    public function edit(Trainee $trainee): View
    {
        return view('trainees.edit', compact('trainee'));
    }

    public function update(Request $request, Trainee $trainee): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $trainee->update($validated);

        return redirect()->route('trainees.index')
            ->with('success', 'تم تحديث بيانات المتدرب بنجاح.');
    }

    public function destroy(Trainee $trainee): RedirectResponse
    {
        $trainee->delete();

        return redirect()->route('trainees.index')
            ->with('success', 'تم حذف المتدرب بنجاح.');
    }
}
