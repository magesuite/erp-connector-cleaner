<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Model\ResourceModel;

class History extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('erp_connector_cleaner_history', 'id');
    }

    public function getProviderIdsByName($name)
    {
        $connection = $this->getConnection();

        $select = $connection
            ->select()
            ->from(['ecp' => $connection->getTableName('erp_connector_provider')], ['id'])
            ->where('name LIKE ?', $name);

        return $connection->fetchCol($select);
    }

    public function getConnectorIdsByName($name)
    {
        $connection = $this->getConnection();

        $select = $connection
            ->select()
            ->from(['ecp' => $connection->getTableName('erp_connector_connector')], ['id'])
            ->where('name LIKE ?', $name);

        return $connection->fetchCol($select);
    }
}
