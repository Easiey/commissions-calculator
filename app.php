<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use App\Service\CommissionCalculator;
use App\Service\BinProvider;
use App\Service\CurrencyRateProvider;

$commissionCalculator = new CommissionCalculator(new BinProvider(), new CurrencyRateProvider());

foreach (explode("\n", file_get_contents($argv[1])) as $row) {
    if (empty($row)) break;

    $transaction = json_decode($row, true);

    $commission = $commissionCalculator->calculateCommission($transaction['bin'], $transaction['amount'], $transaction['currency']);

    echo $commission;
    print "\n";
}