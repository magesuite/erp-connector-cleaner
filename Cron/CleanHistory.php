<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Cron;

class CleanHistory
{
    protected \MageSuite\ErpConnectorCleaner\Helper\Configuration $configuration;
    protected \MageSuite\ErpConnectorCleaner\Model\Command\CleanHistory $cleanHistory;

    public function __construct(
        \MageSuite\ErpConnectorCleaner\Helper\Configuration $configuration,
        \MageSuite\ErpConnectorCleaner\Model\Command\CleanHistory $cleanHistory
    ) {
        $this->configuration = $configuration;
        $this->cleanHistory = $cleanHistory;
    }

    public function execute()
    {
        if (!$this->configuration->isCleanupOfHistoryEnabled()) {
            return true;
        }

        $this->cleanHistory->execute($this->configuration->getCleanupOfHistoryRetentionPeriodInDays());
    }
}
