<?php

declare(strict_types=1);

namespace App\Service;

interface CurrencyRateProviderInterface {
    public function getRate(string $currency): float;
}