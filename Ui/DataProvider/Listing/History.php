<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Ui\DataProvider\Listing;

class History extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    protected \MageSuite\ErpConnectorCleaner\Api\HistoryRepositoryInterface $historyRepository;
    protected \Magento\Ui\DataProvider\SearchResultFactory $searchResultFactory;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\Framework\Api\Search\ReportingInterface $reporting,
        \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \MageSuite\ErpConnectorCleaner\Api\HistoryRepositoryInterface $historyRepository,
        \Magento\Ui\DataProvider\SearchResultFactory $searchResultFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $reporting, $searchCriteriaBuilder, $request, $filterBuilder, $meta, $data);

        $this->historyRepository = $historyRepository;
        $this->searchResultFactory = $searchResultFactory;
    }

    public function getSearchResult()
    {
        $searchCriteria = $this->getSearchCriteria();
        $result = $this->historyRepository->getList($searchCriteria);

        return $this->searchResultFactory->create(
            $result->getItems(),
            $result->getTotalCount(),
            $searchCriteria,
            'id'
        );
    }
}
