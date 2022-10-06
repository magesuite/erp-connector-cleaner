<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Observer;

class RemoveFiles implements \Magento\Framework\Event\ObserverInterface
{
    protected \MageSuite\ErpConnectorCleaner\Model\Command\Client\RemoveFiles $removeFiles;

    public function __construct(\MageSuite\ErpConnectorCleaner\Model\Command\Client\RemoveFiles  $removeFiles)
    {
        $this->removeFiles = $removeFiles;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $client = $observer->getClient();
        $result = $this->removeFiles->execute($client);
        $client->setRemovedFiles($result);
    }
}
