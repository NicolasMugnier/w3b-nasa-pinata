<?php

declare(strict_types=1);

namespace Anyvoid\W3bNasaPinata\BusinessRules\Gateways;

interface FileStorageGateway
{
    public function save(string $data, string $filename, array $metadata): string;
}
