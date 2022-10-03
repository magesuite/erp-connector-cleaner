<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Observer;

class SaveCleanerConfiguration implements \Magento\Framework\Event\ObserverInterface
{
    protected \MageSuite\ErpConnectorCleaner\Api\ConfigurationManagementInterface $configurationManagement;

    public function __construct(\MageSuite\ErpConnectorCleaner\Api\ConfigurationManagementInterface $configurationManagement)
    {
        $this->configurationManagement = $configurationManagement;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $provider = $observer->getProvider();
        $providerId = (int)$provider->getId();

        if (!$providerId) {
            return;
        }

        $request = $observer->getController()->getRequest();
        $cleanerConfiguration = $request->getParam('cleaner');

        if (empty($cleanerConfiguration)) {
            return;
        }

        $this->configurationManagement->saveConfiguration($providerId, $cleanerConfiguration);
    }
}
