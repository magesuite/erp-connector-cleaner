<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Model\Data;

class Configuration extends \Magento\Framework\Model\AbstractModel
{
    const CONFIGURATION_ID = 'configuration_id';
    const PROVIDER_ID = 'provider_id';
    const IS_ENABLED = 'is_enabled';
    const SAVE_CLEANUP_HISTORY = 'save_cleanup_history';
    const BATCH_SIZE = 'batch_size';

    protected function _construct()
    {
        $this->_init(\MageSuite\ErpConnectorCleaner\Model\ResourceModel\Configuration::class);
    }

    public function getConfigurationId()
    {
        return $this->_getData(self::CONFIGURATION_ID);
    }

    public function setConfigurationId($configurationId)
    {
        return $this->setData(self::CONFIGURATION_ID, $configurationId);
    }

    public function getProviderId()
    {
        return $this->_getData(self::PROVIDER_ID);
    }

    public function setProviderId($providerId)
    {
        return $this->setData(self::PROVIDER_ID, $providerId);
    }

    public function getIsEnabled()
    {
        return $this->_getData(self::IS_ENABLED);
    }

    public function setIsEnabled($isEnabled)
    {
        return $this->setData(self::IS_ENABLED, $isEnabled);
    }

    public function getSaveCleanupHistory()
    {
        return $this->_getData(self::SAVE_CLEANUP_HISTORY);
    }

    public function setSaveCleanupHistory($saveCleanupHistory)
    {
        return $this->setData(self::SAVE_CLEANUP_HISTORY, $saveCleanupHistory);
    }

    public function getBatchSize()
    {
        return $this->_getData(self::BATCH_SIZE);
    }

    public function setBatchSize($batchSize)
    {
        return $this->setData(self::BATCH_SIZE, $batchSize);
    }
}
