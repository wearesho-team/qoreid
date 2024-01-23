<?php

declare(strict_types=1);

namespace Wearesho\QoreId;

use Horat1us\Environment;

class EnvironmentConfig extends Environment\Config implements ConfigInterface
{
    public function __construct(string $keyPrefix = 'QOREID_')
    {
        parent::__construct($keyPrefix);
    }

    public function getClientId(): string
    {
        return $this->getEnv("CLIENT_ID");
    }

    public function getClientSecret(): string
    {
        return $this->getEnv("CLIENT_SECRET");
    }
}
