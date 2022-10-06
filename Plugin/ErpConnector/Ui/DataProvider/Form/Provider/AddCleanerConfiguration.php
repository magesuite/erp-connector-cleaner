<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Plugin\ErpConnector\Ui\DataProvider\Form\Provider;

class AddCleanerConfiguration
{
    protected \MageSuite\ErpConnectorCleaner\Api\ConfigurationRepositoryInterface $configurationRepository;

    protected \MageSuite\ErpConnector\Api\SchedulerRepositoryInterface $schedulerRepository;

    protected \MageSuite\ErpConnector\Api\SchedulerConnectorConfigurationRepositoryInterface $schedulerConnectorConfigurationRepository;

    protected \MageSuite\ErpConnector\Logger\Logger $logger;

    public function __construct(
        \MageSuite\ErpConnectorCleaner\Api\ConfigurationRepositoryInterface $configurationRepository,
        \MageSuite\ErpConnector\Api\SchedulerRepositoryInterface $schedulerRepository,
        \MageSuite\ErpConnector\Api\SchedulerConnectorConfigurationRepositoryInterface $schedulerConnectorConfigurationRepository,
        \MageSuite\ErpConnector\Logger\Logger $logger
    ) {
        $this->configurationRepository = $configurationRepository;
        $this->schedulerRepository = $schedulerRepository;
        $this->schedulerConnectorConfigurationRepository = $schedulerConnectorConfigurationRepository;
        $this->logger = $logger;
    }

    public function afterGetData(\MageSuite\ErpConnector\Ui\DataProvider\Form\Provider $subject, $result)
    {
        if (empty($result)) {
            return $result;
        }

        foreach ($result as $providerId => &$data) {
            try {
                $configuration = $this->configurationRepository->getByProviderId((int)$providerId);
                $data['cleaner'] = $configuration->getData();
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $data['cleaner'] = [];
            }

            try {
                $data['cleaner']['schedulers'] = $this->getSchedulersByProviderIdAndType((int)$providerId, 'cleaner');

            } catch (\Exception $e) {
                $this->logger->warning($e->getMessage());
            }
        }

        return $result;
    }

    protected function getSchedulersByProviderIdAndType(int $providerId, string $type)
    {
        $schedulers = $this->schedulerRepository->getByProviderIdAndType($providerId, $type);

        $result = ['schedulers' => []];

        foreach ($schedulers as $scheduler) {
            $schedulerData = $scheduler->getData();
            $schedulerData = $this->addConnectorConfigurationToScheduler($schedulerData);

            $result['schedulers'][] = $schedulerData;
        }

        return $result;
    }

    protected function addConnectorConfigurationToScheduler($schedulerData)
    {
        $connectorConfiguration = $this->schedulerConnectorConfigurationRepository->getBySchedulerId($schedulerData['id']);

        if (empty($connectorConfiguration)) {
            return $schedulerData;
        }

        $schedulerData['connectors'] = [
            'connectors' => []
        ];

        foreach ($connectorConfiguration as $configuration) {
            $schedulerData['connectors']['connectors'][] = $configuration->getData();
        }

        return $schedulerData;
    }
}
