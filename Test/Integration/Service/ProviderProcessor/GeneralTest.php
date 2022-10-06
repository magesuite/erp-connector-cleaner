<?php

namespace MageSuite\ErpConnectorCleaner\Test\Integration\Service\ProviderProcessor;

class GeneralTest extends \PHPUnit\Framework\TestCase
{
    protected ?\MageSuite\ErpConnector\Model\ProviderRepository $providerRepository;
    protected ?\MageSuite\ErpConnector\Api\SchedulerRepositoryInterface $schedulerRepository;
    protected ?\MageSuite\ErpConnector\Service\Scheduler\Processor $schedulerProcessor;
    protected ?\MageSuite\ErpConnectorCleaner\Model\Data\ConfigurationFactory $configurationFactory;
    protected ?\MageSuite\ErpConnectorCleaner\Api\ConfigurationRepositoryInterface $configurationRepository;
    protected ?\MageSuite\ErpConnectorCleaner\Api\HistoryRepositoryInterface $historyRepository;
    protected ?\PHPUnit\Framework\MockObject\MockObject $clientRemoveFiles;

    protected function setUp(): void
    {
        $objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->providerRepository = $objectManager->get(\MageSuite\ErpConnector\Model\ProviderRepository::class);
        $this->schedulerRepository = $objectManager->get(\MageSuite\ErpConnector\Api\SchedulerRepositoryInterface::class);
        $this->schedulerProcessor = $objectManager->get(\MageSuite\ErpConnector\Service\Scheduler\Processor::class);
        $this->configurationFactory = $objectManager->get(\MageSuite\ErpConnectorCleaner\Model\Data\ConfigurationFactory::class);
        $this->configurationRepository = $objectManager->get(\MageSuite\ErpConnectorCleaner\Api\ConfigurationRepositoryInterface::class);
        $this->historyRepository = $objectManager->get(\MageSuite\ErpConnectorCleaner\Api\HistoryRepositoryInterface::class);

        $this->clientRemoveFiles = $this->getMockBuilder(\MageSuite\ErpConnectorCleaner\Model\Command\Client\RemoveFiles::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager->addSharedInstance(
            $this->clientRemoveFiles,
            \MageSuite\ErpConnectorCleaner\Model\Command\Client\RemoveFiles::class,
            true
        );
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture MageSuite_ErpConnectorCleaner::Test/Integration/_files/scheduler_and_connector.php
     */
    public function testItReturnsProviderProcessor()
    {
        $cleanerProcessorType = 'cleaner';

        $provider = $this->providerRepository->getByName('Test Provider');
        $schedulers = $this->schedulerRepository->getByProviderIdAndType($provider->getId(), $cleanerProcessorType);
        $scheduler = current($schedulers);

        $providerProcessor = $this->schedulerProcessor->getProviderProcessor($scheduler);

        $this->assertInstanceOf(\MageSuite\ErpConnectorCleaner\Service\ProviderProcessor\General::class, $providerProcessor);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture MageSuite_ErpConnectorCleaner::Test/Integration/_files/scheduler_and_connector.php
     */
    public function testItExecutesCorrectlyAndSavesResult()
    {
        $cleanerProcessorType = 'cleaner';
        $result = new \Magento\Framework\DataObject([
            'first_file_name' => 'item.csv',
            'last_file_name' => 'last.txt',
            'count' => 3
        ]);

        $this->clientRemoveFiles
            ->method('execute')
            ->willReturn($result);

        $provider = $this->providerRepository->getByName('Test Provider');
        $schedulers = $this->schedulerRepository->getByProviderIdAndType($provider->getId(), $cleanerProcessorType);
        $scheduler = current($schedulers);

        $providerProcessor = $this->schedulerProcessor->getProviderProcessor($scheduler);
        $providerProcessor = $this->prepareProcessor($providerProcessor, $scheduler);
        $providerProcessor->setScheduler($scheduler);

        $providerProcessor->execute();

        $historyEntities = $this->historyRepository->getByProviderId($provider->getId());
        $historyEntity = current($historyEntities);

        $this->assertEquals($provider->getId(), $historyEntity->getProviderId());
        $this->assertEquals($result->getFirstFileName(), $historyEntity->getFirstFileName());
        $this->assertEquals($result->getLastFileName(), $historyEntity->getLastFileName());
        $this->assertEquals($result->getCount(), $historyEntity->getCount());
    }

    protected function prepareProcessor($providerProcessor, $scheduler)
    {
        $providerProcessor->setScheduler($scheduler);

        $configurationData = [
            'is_enabled' => true,
            'save_cleanup_history' => true,
            'batch_size' => 100
        ];

        $configuration = $this->configurationFactory->create();
        $configuration
            ->addData($configurationData)
            ->setProviderId($scheduler->getProviderId());

        $this->configurationRepository->save($configuration);
        return $providerProcessor;
    }
}
