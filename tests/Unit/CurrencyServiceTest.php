<?php

namespace Tests\Unit;

use App\Services\CurrencyService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CurrencyServiceTest extends TestCase
{
    public function test_get_rate_returns_1_for_same_currency()
    {
        $svc = new CurrencyService();
        $this->assertEquals(1.0, $svc->getRate('USD', 'USD'));
    }

    public function test_convert_uses_api_rate()
    {
        Http::fake([
            'https://api.exchangerate.host/convert*' => Http::response([
                'success' => true,
                'info' => ['rate' => 3.67],
            ], 200),
        ]);

        $svc = new CurrencyService();
        $converted = $svc->convert(10, 'USD', 'EGP');
        $this->assertEquals(36.7, $converted);
    }

    public function test_currency_for_country_map()
    {
        $svc = new CurrencyService();
        $this->assertEquals('EGP', $svc->currencyForCountry('EG'));
        $this->assertEquals('USD', $svc->currencyForCountry('US'));
        $this->assertEquals('EGP', $svc->currencyForCountry(null));
    }

    public function test_get_rate_falls_back_to_latest_when_convert_missing()
    {
        Http::fake([
            'https://api.exchangerate.host/convert*' => Http::response(['success' => false], 200),
            'https://api.exchangerate.host/latest*' => Http::response(['rates' => ['EGP' => 3.65]], 200),
        ]);

        $svc = new CurrencyService();
        $rate = $svc->getRate('USD', 'EGP');
        $this->assertEquals(3.65, $rate);
    }
}
