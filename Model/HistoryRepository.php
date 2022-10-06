<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Model;

class HistoryRepository implements \MageSuite\ErpConnectorCleaner\Api\HistoryRepositoryInterface
{
    protected \MageSuite\ErpConnectorCleaner\Model\ResourceModel\History $resourceModel;
    protected \MageSuite\ErpConnectorCleaner\Api\Data\HistoryInterfaceFactory $historyFactory;
    protected \MageSuite\ErpConnectorCleaner\Model\ResourceModel\History\CollectionFactory $collectionFactory;
    protected \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory;
    protected \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder;
    protected \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor;

    public function __construct(
        \MageSuite\ErpConnectorCleaner\Model\ResourceModel\History $resourceModel,
        \MageSuite\ErpConnectorCleaner\Api\Data\HistoryInterfaceFactory $historyFactory,
        \MageSuite\ErpConnectorCleaner\Model\ResourceModel\History\CollectionFactory $collectionFactory,
        \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resourceModel = $resourceModel;
        $this->historyFactory = $historyFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\MageSuite\ErpConnectorCleaner\Api\Data\HistoryInterface $history)
    {
        try {
            $this->resourceModel->save($history);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__($exception->getMessage()));
        }

        return $history;
    }

    /**
     * {@inheritdoc}
     */
    public function getById(int $id)
    {
        $history = $this->historyFactory->create();
        $this->resourceModel->load($history, $id);

        if (!$history->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('The History with the "%1" ID doesn\'t exist.', $id));
        }

        return $history;
    }

    /**
     * {@inheritdoc}
     */
    public function getByProviderId(int $providerId)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(\MageSuite\ErpConnectorCleaner\Api\Data\HistoryInterface::PROVIDER_ID, $providerId)
            ->create();

        $list = $this->getList($searchCriteria);

        if (!$list->getTotalCount()) {
            return [];
        }

        return $list->getItems();
    }

    /**
     * {@inheritdoc}
     */
    public function getList($searchCriteria = null)
    {
        $collection = $this->collectionFactory->create();

        if ($searchCriteria === null) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\MageSuite\ErpConnectorCleaner\Api\Data\HistoryInterface $history)
    {
        try {
            $this->resourceModel->delete($history);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(__($exception->getMessage()));
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
