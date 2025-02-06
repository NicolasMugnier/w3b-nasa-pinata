<?php

declare(strict_types=1);

namespace Anyvoid\W3bNasaPinata\App\Gateways;

use Anyvoid\W3bNasaPinata\BusinessRules\Gateways\SpaceGateway;

final class NasaSpaceGateway implements SpaceGateway
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $nasaApiKey,
        private readonly string $nasaEpicApiUrl,
        private readonly string $nasaEpicArchiveUrl
    ) {}

    public function getEarthImages(\DateTimeInterface $datetime): array
    {
        $type = 'natural';
        $year = $datetime->format('Y');
        $month = $datetime->format('m');
        $day = $datetime->format('d');
        $url = $this->nasaEpicApiUrl."$type/date/$year-$month-$day?api_key=" . $this->nasaApiKey;
        $response = $this->httpClient->request('GET', $url)->getContent();
        $items = \json_decode($response);

        $data = [];
        foreach($items as $item) {
            $image = $item->image;
            $imgUrl = $this->nasaEpicArchiveUrl."$type/$year/$month/$day/png/$image.png";
            $binary = $this->httpClient->request('GET', $imgUrl)->getContent();
            $data[$image] = $binary;
        }

        return $data;
    }
}
