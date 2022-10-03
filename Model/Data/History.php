<?php

namespace MageSuite\ErpConnectorCleaner\Model\Data;

class History extends \Magento\Framework\Model\AbstractModel implements \MageSuite\ErpConnectorCleaner\Api\Data\HistoryInterface
{
    protected $_cacheTag = self::CACHE_TAG; //phpcs:ignore
    protected $_eventPrefix = self::EVENT_PREFIX; //phpcs:ignore

    protected function _construct()
    {
        $this->_init(\MageSuite\ErpConnectorCleaner\Model\ResourceModel\History::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderId()
    {
        return $this->getData(self::PROVIDER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectorId()
    {
        return $this->getData(self::CONNECTOR_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstFileName()
    {
        return $this->getData(self::FIRST_FILE_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function getLastFileName()
    {
        return $this->getData(self::LAST_FILE_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function getCount()
    {
        return $this->getData(self::COUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setId(int $id)
    {
        $this->setData(self::ID, $id);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setProviderId(int $providerId)
    {
        $this->setData(self::PROVIDER_ID, $providerId);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setConnectorId(int $connectorId)
    {
        $this->setData(self::CONNECTOR_ID, $connectorId);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->setData(self::CREATED_AT, $createdAt);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstFileName(string $firstFileName)
    {
        $this->setData(self::FIRST_FILE_NAME, $firstFileName);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastFileName(string $lastFileName)
    {
        $this->setData(self::LAST_FILE_NAME, $lastFileName);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setCount(int $count)
    {
        $this->setData(self::COUNT, $count);
        return $this;
    }
}
