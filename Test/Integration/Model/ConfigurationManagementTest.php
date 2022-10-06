<?php

namespace MageSuite\ErpConnectorCleaner\Test\Integration\Model;

/**
 * @magentoAppArea adminhtml
 */
class ConfigurationManagementTest extends \PHPUnit\Framework\TestCase
{
    protected ?\MageSuite\ErpConnector\Model\ProviderRepository $providerRepository;
    protected ?\MageSuite\ErpConnectorCleaner\Model\ConfigurationManagement $configurationManagement;
    protected ?\MageSuite\ErpConnectorCleaner\Api\ConfigurationRepositoryInterface $configurationRepository;

    protected function setUp(): void
    {
        $objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->providerRepository = $objectManager->get(\MageSuite\ErpConnector\Model\ProviderRepository::class);
        $this->configurationManagement = $objectManager->get(\MageSuite\ErpConnectorCleaner\Model\ConfigurationManagement::class);
        $this->configurationRepository = $objectManager->get(\MageSuite\ErpConnectorCleaner\Api\ConfigurationRepositoryInterface::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture MageSuite_ErpConnector::Test/Integration/_files/provider.php
     */
    public function testItSavesAndLoadConfigurationCorrectly()
    {
        $provider = $this->providerRepository->getByName('Test Provider');

        $configurationData = [
            'is_enabled' => true,
            'save_cleanup_history' => false,
            'batch_size' => 100
        ];

        $this->configurationManagement->saveConfiguration($provider->getId(), $configurationData);
        $configuration = $this->configurationRepository->getByProviderId($provider->getId());

        $this->assertEquals($provider->getId(), $configuration->getProviderId());
        $this->assertEquals($configurationData['is_enabled'], $configuration->getIsEnabled());
        $this->assertEquals($configurationData['save_cleanup_history'], $configuration->getSaveCleanupHistory());
        $this->assertEquals($configurationData['batch_size'], $configuration->getBatchSize());

        $configurationData = [
            'is_enabled' => false,
            'save_cleanup_history' => true,
            'batch_size' => 20
        ];

        $this->configurationManagement->saveConfiguration($provider->getId(), $configurationData);
        $configuration = $this->configurationRepository->getByProviderId($provider->getId());

        $this->assertEquals($provider->getId(), $configuration->getProviderId());
        $this->assertEquals($configurationData['is_enabled'], $configuration->getIsEnabled());
        $this->assertEquals($configurationData['save_cleanup_history'], $configuration->getSaveCleanupHistory());
        $this->assertEquals($configurationData['batch_size'], $configuration->getBatchSize());
    }
}
