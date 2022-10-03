<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Model\ResourceModel;

class Configuration extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('erp_connector_cleaner_configuration', 'configuration_id');
    }
}
