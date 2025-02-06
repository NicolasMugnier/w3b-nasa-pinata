<?php

declare(strict_types=1);

namespace Anyvoid\W3bNasaPinata\BusinessRules\Gateways;

interface SpaceGateway
{
    /**
     * @return array<string, string>
     */
    public function getEarthImages(\DateTimeInterface $datetime): array;
}
