<?php

declare(strict_types=1);

namespace Anyvoid\W3bNasaPinata\App\Gateways;

use Anyvoid\W3bNasaPinata\BusinessRules\Gateways\FileStorageGateway;

final class PinataFileStorageGateway implements FileStorageGateway
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,        
        private readonly string $pinataApiUrl,
        private readonly string $pinataJwt
    ) {}

    public function save(string $filename, string $name, array $metadata): string
    {
        $assetIpfsHash = $this->saveAsset($filename, $name, $metadata);
        $metadataIpfsHash = $this->saveAssetMetadata($assetIpfsHash, $name, $metadata);

        return $metadataIpfsHash;
    }

    private function saveAsset(string $filename, string $name, array $metadata): string
    {
        $url = $this->pinataApiUrl.'pinning/pinFileToIPFS';
        $options = [
            'headers' => [
                'Authorization' => 'Bearer '.$this->pinataJwt,
                'Content-Type' => 'multipart/form-data'
            ],
            'body' => [
                'file' => \fopen($filename, 'r'),
                'pinataMetadata' => ['name' => $name, 'keyvalues' => $metadata],
            ]
        ];
        $response = $this->httpClient->request('POST', $url, $options);
        $statusCode = $response->getStatusCode();
        $content = $response->getContent();
        if ($statusCode !== 200) {
            throw new \Exception($content);
        }

        $data = \json_decode($content, true);
        return $data['IpfsHash'];
    }

    public function saveAssetMetadata(string $assetIpfsHash, string $name, array $metadata): string
    {
        $metadata['asset_name'] = $name;
        $metadata['asset_ipfs_hash'] = $assetIpfsHash;
        $metadata['asset_created_at'] = new \DateTime('now');
        $url = $this->pinataApiUrl.'pinning/pinJSONToIPFS';
        $options = [
            'headers' => [
                'Authorization' => 'Bearer '.$this->pinataJwt,
                'Content-Type' => 'application/json'
            ],
            'body' => \json_encode([
                'pinataMetadata' => [
                    'name' => \pathinfo($name, PATHINFO_FILENAME).'.json',
                ],
                'pinataContent' => $metadata
            ])
        ];
        $response = $this->httpClient->request('POST', $url, $options);
        $statusCode = $response->getStatusCode();
        $content = $response->getContent();
        if ($statusCode !== 200) {
            throw new \Exception($content);
        }

        $data = \json_decode($content, true);
        return $data['IpfsHash'];
    }
}
