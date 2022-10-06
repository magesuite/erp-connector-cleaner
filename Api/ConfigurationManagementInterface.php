<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Api;

interface ConfigurationManagementInterface
{
    public function saveConfiguration(int $providerId, array $configurationData);
}
