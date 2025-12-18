<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyService
{
    protected string $provider = 'exchangerate.host';
    protected int $ttlSeconds = 3600; // 1 hour cache

    public function getRate(string $from, string $to): float
    {
        $from = strtoupper($from);
        $to = strtoupper($to);

        if ($from === $to) return 1.0;

        return Cache::remember("fx:$from:$to", $this->ttlSeconds, function() use ($from, $to) {
            $resp = Http::get("https://api.exchangerate.host/convert", [
                'from' => $from,
                'to' => $to,
                'amount' => 1
            ]);

            if ($resp->successful() && isset($resp['info']['rate'])) {
                return (float) $resp['info']['rate'];
            }

            // fallback: try rates endpoint
            $r = Http::get("https://api.exchangerate.host/latest", ['base' => $from, 'symbols' => $to]);
            if ($r->successful() && isset($r['rates'][$to])) {
                return (float) $r['rates'][$to];
            }

            // As last resort, return 1.0 (no conversion) to avoid crashing
            return 1.0;
        });
    }

    public function convert(float $amount, string $from, string $to): float
    {
        $rate = $this->getRate($from, $to);
        return round($amount * $rate, 2);
    }

    public function currencyForCountry(?string $countryCode): string
    {
        // Minimal mapping for common countries. Extend as needed or use a library
        $map = [
            'EG' => 'EGP',
            'US' => 'USD',
            'GB' => 'GBP',
            'AE' => 'AED',
            'SA' => 'SAR',
            'IN' => 'INR',
        ];

        if (!$countryCode) return config('app.currency', 'EGP');
        $code = strtoupper($countryCode);
        return $map[$code] ?? config('app.currency', 'EGP');
    }
}
