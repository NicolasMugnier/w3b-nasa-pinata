<?php

declare(strict_types=1);

namespace Anyvoid\W3bNasaPinata\BusinessRules\Gateways;

interface SpaceGateway
{
    /**
     * @return array{metadata: <array<string, string>, images: array<string, array{name: string, content: string, url: string, thumbnail: string}>}
     */
    public function getEarthImages(\DateTimeInterface $datetime): array;
}
