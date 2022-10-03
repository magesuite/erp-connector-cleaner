<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Model;

class ConfigurationRepository implements \MageSuite\ErpConnectorCleaner\Api\ConfigurationRepositoryInterface
{
    /**
     * @var \MageSuite\ErpConnectorCleaner\Model\Data\Configuration[]
     */
    protected $instancesById = [];

    protected \MageSuite\ErpConnectorCleaner\Model\Data\ConfigurationFactory $configurationFactory;
    protected \MageSuite\ErpConnectorCleaner\Model\ResourceModel\Configuration $configurationResource;
    protected \MageSuite\ErpConnectorCleaner\Model\ResourceModel\Configuration\CollectionFactory $collectionFactory;
    protected \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory;
    protected \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor;

    public function __construct(
        \MageSuite\ErpConnectorCleaner\Model\Data\ConfigurationFactory $configurationFactory,
        \MageSuite\ErpConnectorCleaner\Model\ResourceModel\Configuration $configurationResource,
        \MageSuite\ErpConnectorCleaner\Model\ResourceModel\Configuration\CollectionFactory $collectionFactory,
        \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
    ) {
        $this->configurationFactory = $configurationFactory;
        $this->configurationResource = $configurationResource;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function getById(int $configurationId)
    {
        if (!isset($this->instancesById[$configurationId])) {
            $configuration = $this->configurationFactory->create();
            $configuration->load($configurationId);
            $this->instancesById[$configurationId] = $configurationId;
        }

        if (!$this->instancesById[$configurationId]->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('The configuration with the "%1" ID doesn\'t exist.', $configurationId)
            );
        }

        return $this->instancesById[$configurationId];
    }

    /**
     * {@inheritdoc}
     */
    public function getByProviderId(int $providerId)
    {
        $configuration = $this->configurationFactory->create();
        $configuration->load($providerId, 'provider_id');

        if (!$configuration->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('The configuration with the "%1" provider ID doesn\'t exist.', $providerId)
            );
        }

        return $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var \MageSuite\ErpConnectorCleaner\Model\ResourceModel\Configuration\Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($criteria, $collection);

        foreach ($collection->getItems() as $configuration) {
            $this->instancesById[$configuration->getId()] = $configuration;
        }

        /** @var \Creativestyle\CustomizationMicroConfigurator\Api\Data\FontSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->count());

        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\MageSuite\ErpConnectorCleaner\Model\Data\Configuration $configuration)
    {
        try {
            $this->configurationResource->save($configuration);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Could not save the configuration: %1', $exception->getMessage()),
                $exception
            );
        }

        return $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\MageSuite\ErpConnectorCleaner\Model\Data\Configuration $configuration)
    {
        try {
            $this->configurationResource->delete($configuration);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Could not delete the font: %1', $exception->getMessage())
            );
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById(int $id)
    {
        return $this->delete($this->getById($id));
    }
}
