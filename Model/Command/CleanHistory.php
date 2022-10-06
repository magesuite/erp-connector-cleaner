<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Model\Command;

class CleanHistory
{
    protected \Magento\Framework\DB\Adapter\AdapterInterface $connection;

    public function __construct(\Magento\Framework\App\ResourceConnection $resourceConnection)
    {
        $this->connection = $resourceConnection->getConnection();
    }

    public function execute($retentionPeriodInDays)
    {
        if (empty($retentionPeriodInDays)) {
            return;
        }

        $maxDate = (new \DateTime())
            ->modify('-' . $retentionPeriodInDays . ' days')
            ->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);

        $this->connection->delete(
            $this->connection->getTableName('erp_connector_cleanup_history'),
            ['created_at < ?' => $maxDate]
        );
    }
}
