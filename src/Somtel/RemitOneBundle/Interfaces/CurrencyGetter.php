<?php

namespace Somtel\RemitOneBundle\Interfaces;

interface CurrencyGetter
{
    /**
     * Get currency by country code.
     *
     * @param string $countryCode Country code in ISO3166-1 alpha2 standard.
     * @return string Currency code in ISO4217.
     */
    public function getByCountryCode($countryCode);

    /**
     * Get currencies for given countries.
     *
     * @param array $countries Country codes in ISO3166-1 alpha2 standard.
     * @return array  Currencies of provided countries.
     */
    public function getCurrenciesForCountries($countries);
}