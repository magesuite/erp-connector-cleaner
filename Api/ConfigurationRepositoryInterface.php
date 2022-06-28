<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Api;

interface ConfigurationRepositoryInterface
{
    /**
     * @param int $configurationId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @return \MageSuite\ErpConnectorCleaner\Api\Data\ConfigurationInterface
     */
    public function getById(int $configurationId);

    /**
     * @param int $providerId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @return \MageSuite\ErpConnectorCleaner\Api\Data\ConfigurationInterface
     */
    public function getByProviderId(int $providerId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param \MageSuite\ErpConnectorCleaner\Api\Data\ConfigurationInterface $configuration
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return bool
     */
    public function save(\MageSuite\ErpConnectorCleaner\Api\Data\ConfigurationInterface $configuration);

    /**
     * @param \MageSuite\ErpConnectorCleaner\Api\Data\ConfigurationInterface $configuration
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return bool
     */
    public function delete(\MageSuite\ErpConnectorCleaner\Api\Data\ConfigurationInterface $configuration);
}
