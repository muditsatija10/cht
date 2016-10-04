<?php
/*
 * Currency service.
 * Gets info on currencies.
 */
namespace Somtel\RemitOneBundle\Service;

use Somtel\RemitOneBundle\Interfaces;

class CurrencyService implements Interfaces\CurrencyGetter
{
    public function __construct($currencies)
    {
        $this->currencies = $currencies;
    }

    public function getByCountryCode($countryCode)
    {
        return null;
    }

    public function getCurrenciesForCountries($countries)
    {
        $allCurrencies = $this->currencies->getAll();
        $selectedCurrencies = [];

        foreach ($allCurrencies as $value) {
            $currencyCountries = $value["country"];
            $currencyCountries = is_array($currencyCountries) ? $currencyCountries : [$currencyCountries];

            if (!empty(array_intersect($countries, $currencyCountries))) {
                $selectedCurrencies[$value["alpha3"]] = $value;
            }
        }
        return $selectedCurrencies;
    }
}
