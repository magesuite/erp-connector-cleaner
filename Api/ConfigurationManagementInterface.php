<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Api;

interface ConfigurationManagementInterface
{
    public function execute(int $providerId, array $configurationData);
}
