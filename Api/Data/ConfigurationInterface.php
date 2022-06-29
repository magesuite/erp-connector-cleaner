<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Api\Data;

interface ConfigurationInterface
{
    public const CONFIGURATION_ID = 'configuration_id';
    public const PROVIDER_ID = 'provider_id';
    public const IS_ENABLED = 'is_enabled';
    public const SAVE_CLEANUP_HISTORY = 'save_cleanup_history';
    public const BATCH_SIZE = 'batch_size';

    public function getConfigurationId();

    public function setConfigurationId($configurationId);

    public function getProviderId();

    public function setProviderId($providerId);

    public function getIsEnabled();

    public function setIsEnabled($isEnabled);

    public function getSaveCleanupHistory();

    public function setSaveCleanupHistory($saveCleanupHistory);

    public function getBatchSize();

    public function setBatchSize($batchSize);
}
