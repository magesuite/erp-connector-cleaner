<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Observer;

class RemoveFiles implements \Magento\Framework\Event\ObserverInterface
{
    protected \MageSuite\ErpConnectorCleaner\Service\Client\FileRemover $fileRemover;

    public function __construct(\MageSuite\ErpConnectorCleaner\Service\Client\FileRemover  $fileRemover)
    {
        $this->fileRemover = $fileRemover;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $client = $observer->getClient();
        $result = $this->fileRemover->removeFiles($client);
        $client->setRemovedFiles($result);
    }
}
