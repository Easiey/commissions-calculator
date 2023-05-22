<?php

declare(strict_types=1);

namespace App\Service;

interface BinProviderInterface {
    public function getCountryAlpha2Code(string $bin): string;
}