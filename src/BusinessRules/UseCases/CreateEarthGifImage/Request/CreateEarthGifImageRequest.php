<?php

declare(strict_types=1);

namespace Anyvoid\W3bNasaPinata\BusinessRules\UseCases\CreateEarthGifImage\Request;

final class CreateEarthGifImageRequest
{
    public function __construct(
        public readonly \DateTimeInterface $date
    ) {}
}
