<?php

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\BinProvider;
use App\Service\CurrencyRateProvider;
use App\Service\CommissionCalculator;

class CommissionCalculatorTest extends TestCase
{
    private $binProvider;
    private $currencyRateProvider;
    private $commissionCalculator;

    public function setUp(): void
    {
        $this->binProvider = $this->createMock(BinProvider::class);
        $this->currencyRateProvider = $this->createMock(CurrencyRateProvider::class);
        $this->commissionCalculator = new CommissionCalculator($this->binProvider, $this->currencyRateProvider);
    }

    public function testCalculateCommissionForEUCurrency()
    {
        $this->binProvider->method('getCountryAlpha2Code')
            ->willReturn('DE');
        $this->currencyRateProvider->method('getRate')
            ->willReturn(0.85);

        $commission = $this->commissionCalculator->calculateCommission('45717360', '200.00', 'EUR');

        $this->assertEquals(2.0, $commission);
    }

    public function testCalculateCommissionForNonEUCurrency()
    {
        $this->binProvider->method('getCountryAlpha2Code')
            ->willReturn('US');
        $this->currencyRateProvider->method('getRate')
            ->willReturn(2.0);

        $commission = $this->commissionCalculator->calculateCommission('516793', '100.00', 'USD');

        $this->assertEquals(1, $commission);
    }
}