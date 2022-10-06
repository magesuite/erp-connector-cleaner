<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Model\ResourceModel\Configuration;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(\MageSuite\ErpConnectorCleaner\Model\Data\Configuration::class, \MageSuite\ErpConnectorCleaner\Model\ResourceModel\Configuration::class);
    }
}
