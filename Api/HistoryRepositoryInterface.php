<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Api;

interface HistoryRepositoryInterface
{
    /**
     * @param \MageSuite\ErpConnectorCleaner\Api\Data\HistoryInterface $history
     * @return \MageSuite\ErpConnectorCleaner\Api\Data\HistoryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\MageSuite\ErpConnectorCleaner\Api\Data\HistoryInterface $history);

    /**
     * @param int $id
     * @return \MageSuite\ErpConnectorCleaner\Api\Data\HistoryInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id);

    /**
     * @param int $providerId
     * @return \MageSuite\ErpConnectorCleaner\Api\Data\HistoryInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByProviderId(int $providerId);

    /**
     * @param ?\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList($searchCriteria = null);

    /**
     * @param \MageSuite\ErpConnectorCleaner\Api\Data\HistoryInterface $history
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\MageSuite\ErpConnectorCleaner\Api\Data\HistoryInterface $history);

    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $id);
}
