<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Service\ProviderProcessor;

class General extends \MageSuite\ErpConnector\Model\ProviderProcessor\ProviderProcessor implements \MageSuite\ErpConnector\Model\ProviderProcessor\ProviderProcessorInterface
{
    protected array $allowedConnectorTypes = ['ftp', 'sftp'];

    protected $schedulerIdToConfigurationMap = [];

    protected \MageSuite\ErpConnectorCleaner\Api\ConfigurationRepositoryInterface $configurationRepository;

    protected \MageSuite\ErpConnector\Api\ConnectorRepositoryInterface $connectorRepository;

    protected \MageSuite\ErpConnector\Api\SchedulerConnectorConfigurationRepositoryInterface $schedulerConnectorConfigurationRepository;


    public function __construct(
        \MageSuite\ErpConnectorCleaner\Api\ConfigurationRepositoryInterface $configurationRepository,
        \MageSuite\ErpConnector\Api\ConnectorRepositoryInterface $connectorRepository,
        \MageSuite\ErpConnector\Api\SchedulerConnectorConfigurationRepositoryInterface $schedulerConnectorConfigurationRepository
    ) {
        $this->configurationRepository = $configurationRepository;
        $this->connectorRepository = $connectorRepository;
        $this->schedulerConnectorConfigurationRepository = $schedulerConnectorConfigurationRepository;
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
        foreach ($this->getConnectors() as $connector) {
            if (!in_array($connector->getType(), $this->allowedConnectorTypes)) {
                continue;
            }

            $client = $connector->getClient();
        }
    }

    protected function getConfiguration(): \MageSuite\ErpConnectorCleaner\Api\Data\ConfigurationInterface
    {
        if ($this->scheduler === null) {
            throw new \Exception('Scheduler isn\'t set.'); //phpcs:ignore
        }

        $schedulerId = $this->scheduler->getId();

        if (!isset($this->schedulerIdToConfigurationMap[$schedulerId])) {
            $this->schedulerIdToConfigurationMap[$schedulerId] = $this->configurationRepository
                ->getByProviderId($this->scheduler->getProviderId());
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
}
