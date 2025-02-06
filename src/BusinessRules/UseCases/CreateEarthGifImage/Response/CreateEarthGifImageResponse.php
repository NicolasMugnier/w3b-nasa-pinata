<?php

declare(strict_types=1);

namespace Anyvoid\W3bNasaPinata\BusinessRules\UseCases\CreateEarthGifImage\Response;

final class CreateEarthGifImageResponse
{
    public function __construct(
        public readonly string $id
    ) {}
}
