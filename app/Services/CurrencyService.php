<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class CurrencyService
{
    protected $currencies;

    public function __construct()
    {
        $this->currencies = config('currency.currencies', []);
    }

    public function getCurrentCurrency()
    {
        if (Session::has('currency') && array_key_exists(Session::get('currency'), $this->currencies)) {
            return Session::get('currency');
        }

        if (auth()->check() && auth()->user()->country_code) {
             // Basic mapping from Country Code to Currency
             // This can be expanded.
             $map = [
                 'EG' => 'EGP',
                 'US' => 'USD',
                 'SA' => 'SAR',
                 'AE' => 'AED',
                 'KW' => 'KWD',
             ];
             $userCountry = strtoupper(auth()->user()->country_code);
             if (isset($map[$userCountry])) {
                 return $map[$userCountry];
             }
        }

        // Default or Fallback
        return config('currency.default', 'EGP');
    }

    public function convert($amount, $toCurrency = null)
    {
        $toCurrency = $toCurrency ?? $this->getCurrentCurrency();
        $config = $this->currencies[$toCurrency] ?? null;

        if (!$config) {
            return $amount; // Fallback
        }

        // Base currency is EGP (Rate 1)
        // logic: Amount in EGP * Rate 
        // Example: 100 EGP * 0.02 (USD Rate) = 2 USD
        return $amount * $config['rate'];
    }

    public function format($amount, $currency = null)
    {
        $currency = $currency ?? $this->getCurrentCurrency();
        $config = $this->currencies[$currency] ?? null;

        if (!$config) {
            return number_format($amount, 2) . ' EGP';
        }

        $converted = $this->convert($amount, $currency);
        
        // Format: "$ 100.00" or "100.00 SAR"
        if ($currency === 'USD') {
             return $config['symbol'] . number_format($converted, 2);
        }

        return number_format($converted, 2) . ' ' . $config['symbol'];
    }
}
