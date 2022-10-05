<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Service\ProviderProcessor;

class General extends \MageSuite\ErpConnector\Model\ProviderProcessor\ProviderProcessor implements \MageSuite\ErpConnector\Model\ProviderProcessor\ProviderProcessorInterface
{
    protected $schedulerIdToConfigurationMap = [];

    protected \MageSuite\ErpConnectorCleaner\Api\ConfigurationRepositoryInterface $configurationRepository;
    protected \MageSuite\ErpConnector\Api\ConnectorRepositoryInterface $connectorRepository;
    protected \MageSuite\ErpConnector\Api\SchedulerConnectorConfigurationRepositoryInterface $schedulerConnectorConfigurationRepository;
    protected \MageSuite\ErpConnectorCleaner\Api\Data\HistoryInterfaceFactory $historyFactory;
    protected \MageSuite\ErpConnectorCleaner\Api\HistoryRepositoryInterface $historyRepository;
    protected array $allowedConnectorTypes = [];

    public function __construct(
        \MageSuite\ErpConnector\Api\ProviderRepositoryInterface $providerRepository,
        \MageSuite\ErpConnectorCleaner\Api\ConfigurationRepositoryInterface $configurationRepository,
        \MageSuite\ErpConnector\Api\ConnectorRepositoryInterface $connectorRepository,
        \MageSuite\ErpConnector\Api\SchedulerConnectorConfigurationRepositoryInterface $schedulerConnectorConfigurationRepository,
        \MageSuite\ErpConnector\Model\Command\LogErrorMessage $logErrorMessage,
        \MageSuite\ErpConnectorCleaner\Api\Data\HistoryInterfaceFactory $historyFactory,
        \MageSuite\ErpConnectorCleaner\Api\HistoryRepositoryInterface $historyRepository,
        array $allowedConnectorTypes,
        $data = []
    ) {
        parent::__construct($providerRepository, $logErrorMessage, $data);

        $this->configurationRepository = $configurationRepository;
        $this->connectorRepository = $connectorRepository;
        $this->schedulerConnectorConfigurationRepository = $schedulerConnectorConfigurationRepository;
        $this->historyFactory = $historyFactory;
        $this->historyRepository = $historyRepository;
        $this->allowedConnectorTypes = $allowedConnectorTypes;
    }

    public function execute()
    {
        if (!$this->getConfiguration()->getIsEnabled()) {
            return true;
        }

        $this->removeOutdatedFiles();
    }

    protected function removeOutdatedFiles(): void
    {
        $clientData = [
            'provider' => $this->getProvider(),
            'batch_size' => $this->getConfiguration()->getBatchSize(),
            'file_name_pattern' => $this->scheduler->getFileName(),
            'remove_files_older_than_days_ago' => $this->scheduler->getRemoveFilesOlderThanDaysAgo()
        ];

        foreach ($this->getConnectors() as $connector) {
            if (!in_array($connector->getType(), $this->allowedConnectorTypes)) {
                continue;
            }

            $client = $connector->getClient();
            $client->addData($clientData);
            $client->removeFiles();

            $this->saveCleanupHistory($client, (int)$connector->getId(), (int)$this->scheduler->getProviderId());
        }
    }

    protected function getConfiguration(): \MageSuite\ErpConnectorCleaner\Model\Data\Configuration
    {
        if ($this->scheduler === null) {
            throw new \InvalidArgumentException('Scheduler isn\'t set.');
        }

        $schedulerId = $this->scheduler->getId();

        if (!isset($this->schedulerIdToConfigurationMap[$schedulerId])) {
            $this->schedulerIdToConfigurationMap[$schedulerId] = $this->configurationRepository->getByProviderId((int)$this->scheduler->getProviderId());
        }

        return $this->schedulerIdToConfigurationMap[$schedulerId];
    }

    /**
     * @return \MageSuite\ErpConnector\Model\Data\Connector[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getConnectors(): array
    {
        $connectors = [];
        $schedulerConnectors = $this->schedulerConnectorConfigurationRepository
            ->getBySchedulerId($this->scheduler->getId());

        foreach ($schedulerConnectors as $schedulerConnector) {
            $connectors[] = $this->connectorRepository->getById($schedulerConnector->getConnectorId());
        }

        return $connectors;
    }

    /**
     * @param $removedFiles
     * @param $connectorId
     * @param $providerId
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function saveCleanupHistory($client, int $connectorId, int $providerId): void
    {
        if (!$this->getConfiguration()->getSaveCleanupHistory()) {
            return;
        }

        $removedFiles = $client->getRemovedFiles();

        if (!$removedFiles instanceof \Magento\Framework\DataObject || !$removedFiles->getCount()) {
            return;
        }

        $history = $this->historyFactory->create();

        $history
            ->setProviderId($providerId)
            ->setConnectorId($connectorId)
            ->setFirstFileName($removedFiles->getFirstFileName())
            ->setLastFileName($removedFiles->getLastFileName())
            ->setCount($removedFiles->getCount());

        $this->historyRepository->save($history);
    }
}
