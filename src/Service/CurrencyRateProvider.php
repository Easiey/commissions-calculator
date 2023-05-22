<?php

declare(strict_types=1);

namespace App\Service;

use Exception;

class CurrencyRateProvider implements CurrencyRateProviderInterface {

    const EXCHANGE_RATES_API_URL = 'https://api.exchangerate.host/latest';
    private ?array $cachedRates = null;

    /**
     * @throws Exception
     */
    public function getRate(string $currency): float
    {
        if ($this->cachedRates === null) {
            $this->cachedRates = $this->fetchExchangeRates();
        }

        if (!isset($this->cachedRates[$currency])) {
            throw new Exception("Currency {$currency} not found in the exchange rates API response.");
        }

        return (float)$this->cachedRates[$currency];
    }

    /**
     * @throws Exception
     */
    private function fetchExchangeRates(): array
    {
        $data = @file_get_contents(self::EXCHANGE_RATES_API_URL);
        $rates = @json_decode($data, true)['rates'];

        if (!is_array($rates)) {
            throw new Exception('Could not fetch exchange rates.');
        }

        return $rates;
    }
}