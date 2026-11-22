<?php

namespace App\Helpers;

class Tafqeet
{
    private static array $ones = [
        0 => '',
        1 => 'واحد',
        2 => 'اثنان',
        3 => 'ثلاثة',
        4 => 'أربعة',
        5 => 'خمسة',
        6 => 'ستة',
        7 => 'سبعة',
        8 => 'ثمانية',
        9 => 'تسعة',
        10 => 'عشرة',
        11 => 'أحد عشر',
        12 => 'اثنا عشر',
        13 => 'ثلاثة عشر',
        14 => 'أربعة عشر',
        15 => 'خمسة عشر',
        16 => 'ستة عشر',
        17 => 'سبعة عشر',
        18 => 'ثمانية عشر',
        19 => 'تسعة عشر',
    ];

    private static array $tens = [
        2 => 'عشرون',
        3 => 'ثلاثون',
        4 => 'أربعون',
        5 => 'خمسون',
        6 => 'ستون',
        7 => 'سبعون',
        8 => 'ثمانون',
        9 => 'تسعون',
    ];

    private static array $hundreds = [
        0 => '',
        1 => 'مائة',
        2 => 'مائتان',
        3 => 'ثلاثمائة',
        4 => 'أربعمائة',
        5 => 'خمسمائة',
        6 => 'ستمائة',
        7 => 'سبعمائة',
        8 => 'ثمانمائة',
        9 => 'تسعمائة',
    ];

    public static function inArabic(float|int|string $number, string $currency = 'EGP'): string
    {
        $number = round((float) $number, 2);
        if ($number <= 0) {
            return 'صفر جنيه مصري';
        }

        $parts = explode('.', number_format($number, 2, '.', ''));
        $integerPart = (int) $parts[0];
        $fractionPart = (int) ($parts[1] ?? 0);

        $result = self::convertGroup($integerPart);

        $currencyName = match ($currency) {
            'USD' => 'دولار أمريكي',
            'EUR' => 'يورو',
            'SAR' => 'ريال سعودي',
            'AED' => 'درهم إماراتي',
            default => 'جنيه مصري',
        };

        $fractionName = match ($currency) {
            'USD', 'EUR' => 'سنت',
            'SAR', 'AED' => 'هللة',
            default => 'قرش',
        };

        $text = 'فقط '.$result.' '.$currencyName;

        if ($fractionPart > 0) {
            $fractionWords = self::convertGroup($fractionPart);
            $text .= ' و'.$fractionWords.' '.$fractionName;
        }

        $text .= ' لا غير';

        return $text;
    }

    private static function convertGroup(int $number): string
    {
        if ($number === 0) {
            return '';
        }

        if ($number >= 1000000000) {
            $billions = (int) ($number / 1000000000);
            $remainder = $number % 1000000000;
            $word = match ($billions) {
                1 => 'مليار',
                2 => 'ملياران',
                default => self::convertGroup($billions).' مليار',
            };

            return $word.($remainder > 0 ? ' و'.self::convertGroup($remainder) : '');
        }

        if ($number >= 1000000) {
            $millions = (int) ($number / 1000000);
            $remainder = $number % 1000000;
            $word = match ($millions) {
                1 => 'مليون',
                2 => 'مليونان',
                3, 4, 5, 6, 7, 8, 9, 10 => self::$ones[$millions].' ملايين',
                default => self::convertGroup($millions).' مليون',
            };

            return $word.($remainder > 0 ? ' و'.self::convertGroup($remainder) : '');
        }

        if ($number >= 1000) {
            $thousands = (int) ($number / 1000);
            $remainder = $number % 1000;
            $word = match ($thousands) {
                1 => 'ألف',
                2 => 'ألفان',
                3, 4, 5, 6, 7, 8, 9, 10 => self::$ones[$thousands].' آلاف',
                default => self::convertGroup($thousands).' ألف',
            };

            return $word.($remainder > 0 ? ' و'.self::convertGroup($remainder) : '');
        }

        if ($number >= 100) {
            $h = (int) ($number / 100);
            $remainder = $number % 100;
            $word = self::$hundreds[$h];

            return $word.($remainder > 0 ? ' و'.self::convertGroup($remainder) : '');
        }

        if ($number <= 19) {
            return self::$ones[$number];
        }

        $t = (int) ($number / 10);
        $o = $number % 10;
        $word = self::$tens[$t];

        if ($o > 0) {
            return self::$ones[$o].' و'.$word;
        }

        return $word;
    }
}
