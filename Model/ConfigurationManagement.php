<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Model;

class ConfigurationManagement implements \MageSuite\ErpConnectorCleaner\Api\ConfigurationManagementInterface
{
    protected \MageSuite\ErpConnectorCleaner\Api\ConfigurationRepositoryInterface $configurationRepository;

    protected \MageSuite\ErpConnectorCleaner\Api\Data\ConfigurationInterfaceFactory $configurationFactory;

    protected \MageSuite\ErpConnector\Model\Command\Provider\SaveSchedulers $saveSchedulers;

    public function __construct(
        \MageSuite\ErpConnectorCleaner\Api\ConfigurationRepositoryInterface $configurationRepository,
        \MageSuite\ErpConnectorCleaner\Api\Data\ConfigurationInterfaceFactory $configurationFactory,
        \MageSuite\ErpConnector\Model\Command\Provider\SaveSchedulers $saveSchedulers
    ) {
        $this->configurationRepository = $configurationRepository;
        $this->configurationFactory = $configurationFactory;
        $this->saveSchedulers = $saveSchedulers;
    }

    public function execute(int $providerId, array $configurationData)
    {
        try {
            $configuration = $this->configurationRepository->getByProviderId($providerId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $configuration = $this->configurationFactory->create();
        }

        $configuration->addData($configurationData)
            ->setProviderId($providerId);
        $this->configurationRepository->save($configuration);
        $schedulersData = $configurationData['schedulers']['schedulers'] ?? [];
        $this->saveSchedulers->execute($providerId, 'cleaner', $schedulersData);
    }
}
