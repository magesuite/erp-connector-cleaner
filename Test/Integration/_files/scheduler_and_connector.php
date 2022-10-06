<?php

$resolver = \Magento\TestFramework\Workaround\Override\Fixture\Resolver::getInstance();
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$providerRepository = $objectManager->get(\MageSuite\ErpConnector\Api\ProviderRepositoryInterface::class);
$connectorFactory = $objectManager->get(\MageSuite\ErpConnector\Model\Data\ConnectorFactory::class);
$connectorRepository = $objectManager->get(\MageSuite\ErpConnector\Api\ConnectorRepositoryInterface::class);
$schedulerConnectorConfigurationFactory = $objectManager->get(\MageSuite\ErpConnector\Model\Data\SchedulerConnectorConfigurationFactory::class);
$schedulerConnectorConfigurationRepository = $objectManager->get(\MageSuite\ErpConnector\Model\SchedulerConnectorConfigurationRepository::class);
$schedulerRepository = $objectManager->get(\MageSuite\ErpConnector\Api\SchedulerRepositoryInterface::class);

$resolver->requireDataFixture('MageSuite_ErpConnector::Test/Integration/_files/provider.php');

$provider = $providerRepository->getByName('Test Provider');

$connector = $connectorFactory->create();
$connector
    ->setProviderId($provider->getId())
    ->setName('connector')
    ->setType('ftp');

$connector = $connectorRepository->save($connector);

$scheduler = $objectManager->create(\MageSuite\ErpConnector\Model\Data\Scheduler::class);
$scheduler->isObjectNew(true);
$scheduler
    ->setProviderId($provider->getId())
    ->setName('Test Scheduler')
    ->setType('cleaner')
    ->setCronExpression('10 03 * * *')
    ->setRemoveFilesOlderThanDaysAgo(20);

$scheduler = $schedulerRepository->save($scheduler);

$schedulerConnectorConfiguration = $schedulerConnectorConfigurationFactory->create();
$schedulerConnectorConfiguration
    ->setSchedulerId($scheduler->getId())
    ->setProviderId($provider->getId())
    ->setConnectorId($connector->getId());

$schedulerConnectorConfigurationRepository->save($schedulerConnectorConfiguration);
