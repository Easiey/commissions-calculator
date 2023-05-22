<?php

declare(strict_types=1);

namespace App\Service;

class CommissionCalculator {

    const EU_COMMISSION_RATE = 0.01;
    const NON_EU_COMMISSION_RATE = 0.02;
    private BinProviderInterface $binProviderInterface;
    private CurrencyRateProviderInterface $currencyRateProvider;

    public function __construct(BinProviderInterface $binProvider, CurrencyRateProviderInterface $currencyRateProvider) {
        $this->binProviderInterface = $binProvider;
        $this->currencyRateProvider = $currencyRateProvider;
    }

    public function calculateCommission(string $bin, string $amount, string $currency): float {
        $countryAlpha2Code = $this->binProviderInterface->getCountryAlpha2Code($bin);
        $rate = $this->currencyRateProvider->getRate($currency);
        $euroAmount = ($currency === 'EUR' || $rate === 0.0) ? $amount : $amount / $rate;
        $commission = $euroAmount * ($this->isCountryInEu($countryAlpha2Code) ? self::EU_COMMISSION_RATE : self::NON_EU_COMMISSION_RATE);

        return ceil($commission * 100) / 100;
    }

    private function isCountryInEu(string $countryCode): bool {
        $euCountriesCodes = ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'];

        return in_array($countryCode, $euCountriesCodes, true);
    }
}