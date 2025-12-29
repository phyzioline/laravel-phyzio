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

    /**
     * Get currency code for a given country code
     */
    public function currencyForCountry($countryCode)
    {
        if (!$countryCode) {
            return config('currency.default', 'EGP');
        }

        $map = [
            'EG' => 'EGP',
            'US' => 'USD',
            'SA' => 'SAR',
            'AE' => 'AED',
            'KW' => 'KWD',
            'GB' => 'GBP',
            'EU' => 'EUR',
            'CA' => 'CAD',
            'AU' => 'AUD',
        ];

        $countryCode = strtoupper($countryCode);
        return $map[$countryCode] ?? config('currency.default', 'EGP');
    }

    /**
     * Get exchange rate between two currencies
     */
    public function getRate($fromCurrency, $toCurrency)
    {
        // If same currency, rate is 1
        if ($fromCurrency === $toCurrency) {
            return 1.0;
        }

        // Base currency is EGP (rate = 1)
        // To convert FROM EGP to another currency, use that currency's rate
        if ($fromCurrency === 'EGP' || $fromCurrency === config('currency.default', 'EGP')) {
            $toConfig = $this->currencies[$toCurrency] ?? null;
            if ($toConfig && isset($toConfig['rate'])) {
                return $toConfig['rate'];
            }
        }

        // To convert TO EGP from another currency, use inverse of that currency's rate
        if ($toCurrency === 'EGP' || $toCurrency === config('currency.default', 'EGP')) {
            $fromConfig = $this->currencies[$fromCurrency] ?? null;
            if ($fromConfig && isset($fromConfig['rate'])) {
                return 1 / $fromConfig['rate'];
            }
        }

        // For conversions between two non-EGP currencies
        $fromConfig = $this->currencies[$fromCurrency] ?? null;
        $toConfig = $this->currencies[$toCurrency] ?? null;

        if ($fromConfig && $toConfig && isset($fromConfig['rate']) && isset($toConfig['rate'])) {
            // Convert fromCurrency -> EGP -> toCurrency
            // Rate = (1 / fromRate) * toRate = toRate / fromRate
            return $toConfig['rate'] / $fromConfig['rate'];
        }

        // Fallback: return 1 if we can't determine the rate
        return 1.0;
    }

    /**
     * Convert amount from one currency to another
     */
    public function convertFromTo($amount, $fromCurrency, $toCurrency)
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $rate = $this->getRate($fromCurrency, $toCurrency);
        return $amount * $rate;
    }
}
