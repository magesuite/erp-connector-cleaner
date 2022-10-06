<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Helper;

class Configuration
{
    const XML_PATH_CLEANUP_OF_HISTORY_IS_ENABLED = 'erp_connector/cleaner/cleanup_of_history/is_enabled';
    const XML_PATH_CLEANUP_OF_HISTORY_RETENTION_PERIOD_IN_DAYS = 'erp_connector/cleaner/cleanup_of_history/retention_period_in_days';

    protected \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isCleanupOfHistoryEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_CLEANUP_OF_HISTORY_IS_ENABLED);
    }

    public function getCleanupOfHistoryRetentionPeriodInDays()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_CLEANUP_OF_HISTORY_RETENTION_PERIOD_IN_DAYS);
    }
}
