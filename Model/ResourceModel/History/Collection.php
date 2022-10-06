<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Model\ResourceModel\History;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected \MageSuite\ErpConnectorCleaner\Model\ResourceModel\History $historyResource;

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \MageSuite\ErpConnectorCleaner\Model\ResourceModel\History $historyResource,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);

        $this->historyResource = $historyResource;
    }

    protected function _construct(): void
    {
        $this->_init(\MageSuite\ErpConnectorCleaner\Model\Data\History::class, \MageSuite\ErpConnectorCleaner\Model\ResourceModel\History::class);
    }

    public function addFieldToFilter($field, $condition = null)
    {
        if (!is_array($field) || $field[0] != 'fulltext') {
            return parent::addFieldToFilter($field, $condition);
        }

        $fields = ['first_file_name', 'last_file_name'];

        $conditions = [
            ['attribute' => 'first_file_name', 'like' => $condition[0]['like']],
            ['attribute' => 'last_file_name', 'like' => $condition[0]['like']]
        ];

        $providerIds = $this->historyResource->getProviderIdsByName($condition[0]['like']);

        if ($providerIds) {
            $fields[] = 'provider_id';
            $conditions[] = ['attribute' => 'provider_id', 'in' => $providerIds];
        }

        $connectorIds = $this->historyResource->getConnectorIdsByName($condition[0]['like']);

        if ($connectorIds) {
            $fields[] = 'connector_id';
            $conditions[] = ['attribute' => 'connector_id', 'in' => $connectorIds];
        }

        return parent::addFieldToFilter($fields, $conditions);
    }
}
