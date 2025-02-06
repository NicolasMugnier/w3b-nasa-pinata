<?php

declare(strict_types=1);

namespace Anyvoid\W3bNasaPinata\App\Gateways;

use Anyvoid\W3bNasaPinata\BusinessRules\Gateways\SpaceGateway;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class NasaSpaceGateway implements SpaceGateway
{

    private HttpClientInterface $httpClient;

    public function __construct(
        private readonly string $nasaApiKey,
        private readonly string $nasaEpicApiUrl,
        private readonly string $nasaEpicArchiveUrl
    ) {
        $this->httpClient = HttpClient::create();
    }

    /**
     * @return array{metadata: <array<string, string>, images: array<string, array{name: string, content: string, url: string, thumbnail: string}>}
     */
    public function getEarthImages(\DateTimeInterface $datetime): array
    {
        $type = 'natural';
        $year = $datetime->format('Y');
        $month = $datetime->format('m');
        $day = $datetime->format('d');
        $url = $this->nasaEpicApiUrl."$type/date/$year-$month-$day?api_key=" . $this->nasaApiKey;
        $response = $this->httpClient->request('GET', $url)->getContent();
        $items = \json_decode($response);

        $data = [
            'metadata' => [
                'description' => 'Imagery collected by DSCOVR\'s Earth Polychromatic Imaging Camera (EPIC) instrument',
                'images_collected_at' => "$year-$month-$day",
                'source' => 'Nasa Epic API'
            ],
            'images' => []
        ];
        foreach($items as $item) {
            $image = $item->image;
            $imgUrl = $this->nasaEpicArchiveUrl."$type/$year/$month/$day/png/$image.png";
            $binary = $this->httpClient->request('GET', $imgUrl)->getContent();
            $data['images'][$image] = [
                'name' => $image,
                'content' => $binary,
                'url' => $imgUrl,
                'thumbnail' => 'https://epic.gsfc.nasa.gov/archive/natural/'.$year.'/'.$month.'/'.$day.'/thumbs/'.$image.'.jpg'
            ];
        }

        return $data;
    }
}
