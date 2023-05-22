<?php

declare(strict_types=1);

namespace App\Service;

use Exception;

class BinProvider implements BinProviderInterface {

    const BIN_LOOKUP_API_URL = 'https://lookup.binlist.net/';

    /**
     * @throws Exception
     */
    public function getCountryAlpha2Code(string $bin): string
    {
        $binResults = file_get_contents(self::BIN_LOOKUP_API_URL . $bin);

        if (!$binResults) {
            throw new Exception('Error retrieving BIN data');
        }

        $data = json_decode($binResults);

        return $data->country->alpha2;
    }
}