<?php

declare(strict_types=1);

namespace Anyvoid\W3bNasaPinata\App\Commands;

use Anyvoid\W3bNasaPinata\BusinessRules\UseCases\CreateEarthGifImage\CreateEarthGifImage;
use Anyvoid\W3bNasaPinata\BusinessRules\UseCases\CreateEarthGifImage\Request\CreateEarthGifImageRequest;

final class GenerateEarthCommand
{
    protected static $defaultName = 'anyvoid:generate-earth-gif';

    public function __construct(
        private readonly CreateEarthGifImage $createEarthGifImage
    ) {}

    public function execute(): void
    {
        $request = new CreateEarthGifImageRequest(new \DateTime('now'));
        $this->createEarthGifImage->execute($request);
    }
}
