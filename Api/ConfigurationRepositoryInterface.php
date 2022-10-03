<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Api;

interface ConfigurationRepositoryInterface
{
    /**
     * @param int $configurationId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @return \MageSuite\ErpConnectorCleaner\Model\Data\Configuration
     */
    public function getById(int $configurationId);

    /**
     * @param int $providerId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @return \MageSuite\ErpConnectorCleaner\Model\Data\Configuration
     */
    public function getByProviderId($providerId);

    /**
     * @param \MageSuite\ErpConnectorCleaner\Model\Data\Configuration $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param \MageSuite\ErpConnectorCleaner\Model\Data\Configuration $configuration
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return bool
     */
    public function save(\MageSuite\ErpConnectorCleaner\Model\Data\Configuration $configuration);

    /**
     * @param \MageSuite\ErpConnectorCleaner\Model\Data\Configuration $configuration
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return bool
     */
    public function delete(\MageSuite\ErpConnectorCleaner\Model\Data\Configuration $configuration);
}
