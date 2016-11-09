<?php
namespace AppBundle\Service;

use GeoIp2\Database\Reader;
use GeoIp2\Model\Country;
use Monolog\Logger;

class GeoIpService
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * GeoIpService constructor.
     * @param $pathToDb
     * @param $logger
     */
    public function __construct($pathToDb, $logger)
    {
        $this->reader = new Reader($pathToDb);
        $this->logger = $logger;
    }

    /**
     * @param $ip
     * @return mixed
     */
    public function getCountry($ip)
    {
        try {
            $countryData = $this->reader->country($ip)->country;

            return [
                'isoCode' => $countryData->isoCode,
                'name' => $countryData->name
            ];
        } catch (\Exception $e) {
            $this->logger->error('Country detection exception: ' . $e->getTraceAsString());
            return [
                'isoCode' => false,
                'name' => false
            ];
        }
    }
}
