<?php

declare(strict_types=1);

namespace Anyvoid\W3bNasaPinata\BusinessRules\UseCases\CreateEarthGifImage;

use Anyvoid\W3bNasaPinata\BusinessRules\Gateways\FileStorageGateway;
use Anyvoid\W3bNasaPinata\BusinessRules\Gateways\SpaceGateway;
use Anyvoid\W3bNasaPinata\BusinessRules\UseCases\CreateEarthGifImage\Request\CreateEarthGifImageRequest;
use Anyvoid\W3bNasaPinata\BusinessRules\UseCases\CreateEarthGifImage\Response\CreateEarthGifImageResponse;

final class CreateEarthGifImage
{
    public function __construct(
        private readonly SpaceGateway $spaceGateway,
        private readonly FileStorageGateway $fileStorageGateway,
        private readonly string $imageDirectory
    ) {}

    public function execute(CreateEarthGifImageRequest $request): CreateEarthGifImageResponse
    {
        $year = $request->date->format('Y');
        $month = $request->date->format('m');
        $day = $request->date->format('d');
        
        $directory = $this->imageDirectory.'/epic/'.$year.'/'.$month.'/'.$day.'/';
        $imgGifName = 'earth.gif';

        if (!\file_exists($directory.$imgGifName)) {
            $images = $this->spaceGateway->getEarthImages($request->date);
            if (\count($images) === 0) {
                throw new \Exception('No image available');
            }

            if (!\is_dir($directory)) {
                \mkdir($directory, 0777, true);
            }

            foreach ($images as $name => $binary) {
                \file_put_contents($directory.$name.'.png', $binary);
            }
        
            $cmd = "cd $directory && convert -delay 15 -loop 0 -layers Optimize -resize 1024x1024 epic_*.png $imgGifName";
            \exec($cmd);
        }

        $name = $year.'-'.$month.'-'.$day.'-'.$imgGifName;
        $metadata = [
            'description' => 'Imagery collected by DSCOVR\'s Earth Polychromatic Imaging Camera (EPIC) instrument',
            'images_collected_at' => "$year-$month-$day",
            'source' => 'Nasa Epic API'
        ];
        $id = $this->fileStorageGateway->save($directory.$imgGifName, $name, $metadata);

        return new CreateEarthGifImageResponse($id);
    }
}
