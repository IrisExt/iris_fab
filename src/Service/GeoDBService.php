<?php
declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;

/**
 * Class GeoDBService
 * @package App\Service
 */
class GeoDBService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $key;

    /**
     * GeoDBService constructor.
     *
     * @param Client $client
     * @param string $host
     * @param string $key
     */
    public function __construct(Client $client, string $host, string $key)
    {
        $this->client = $client;
        $this->host = $host;
        $this->key = $key;
    }

    /**
     * Get country ids
     *
     * @param string|null $namePrefix
     * @param string $languageCode
     *
     * @return string
     */
    public function getCountryIds(?string $namePrefix, string $languageCode): string
    {
        $response = $this->client->get(
            '/v1/geo/countries',
            [
                'http_errors' => false,
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key'  => $this->key
                ],
                'query' => [
                    'namePrefix' => $namePrefix,
                    'languageCode' => $languageCode
                ]
            ]
        );

        return $response->getBody()->getContents();
    }

    /**
     * Get city
     *
     * @param string|null $namePrefix
     * @param string|null $countryIds
     *
     * @return string
     */
    public function getCity(?string $namePrefix, ?string $countryIds): string
    {
        $response = $this->client->get(
            '/v1/geo/cities',
            [
                'http_errors' => false,
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key'  => $this->key
                ],
                'query' => [
                    'namePrefix' => $namePrefix,
                    'countryIds' => $countryIds
                ]
            ]
        );

        return $response->getBody()->getContents();
    }

    /**
     * Get cities
     *
     * @param string|null $namePrefix
     * @param string      $order
     * @param int         $limit
     * @param int         $offset
     *
     * @return array
     */
    public function getCities(
        ?string $namePrefix,
        string $order = 'asc',
        int $limit = 10,
        int $offset = 0
    ) {
        $sort = $order == 'asc' ? '+' : '-';
        $response = $this->client->get(
            '/v1/geo/cities',
            [
                'http_errors' => false,
                'headers' => [
                    'X-RapidAPI-Host' => $this->host,
                    'X-RapidAPI-Key'  => $this->key
                ],
                'query' => [
                    'namePrefix' => $namePrefix,
                    'sort' => $sort . 'name',
                    'limit' => $limit,
                    'offset' => $offset,
                    'languageCode' => 'fr'
                ]
            ]
        );
        $cities = array();

        foreach (json_decode($response->getBody()->getContents())->data as $city) {
            $cities[] = array('id' => $city->id, 'name' => $city->name , 'country' => $city->country);
        }

        return $cities;
    }
}
