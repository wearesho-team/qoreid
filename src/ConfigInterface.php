<?php

declare(strict_types=1);

namespace Wearesho\QoreId;

interface ConfigInterface
{
    public const BASE_URL = 'https://api.qoreid.com/';

    public function getClientId(): string;

    public function getClientSecret(): string;
}
