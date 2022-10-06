<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Api\Data;

interface HistoryInterface
{
    const ID = 'id';
    const PROVIDER_ID = 'provider_id';
    const CONNECTOR_ID = 'connector_id';
    const CREATED_AT = 'created_at';
    const FIRST_FILE_NAME = 'first_file_name';
    const LAST_FILE_NAME = 'last_file_name';
    const COUNT = 'count';

    const CACHE_TAG = 'erp_connector_cleaner_history';
    const EVENT_PREFIX = 'erp_connector_cleaner_history';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getProviderId();

    /**
     * @return int
     */
    public function getConnectorId();

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @return string
     */
    public function getFirstFileName();

    /**
     * @return string
     */
    public function getLastFileName();

    /**
     * @return int
     */
    public function getCount();

    /**
     * @param int $id
     * @return self
     */
    public function setId($id);

    /**
     * @param int $providerId
     * @return self
     */
    public function setProviderId(int $providerId);

    /**
     * @param int $connectorId
     * @return self
     */
    public function setConnectorId(int $connectorId);

    /**
     * @param string $createdAt
     * @return self
     */
    public function setCreatedAt(string $createdAt);

    /**
     * @param string $firstFileName
     * @return self
     */
    public function setFirstFileName(string $firstFileName);

    /**
     * @param string $lastFileName
     * @return self
     */
    public function setLastFileName(string $lastFileName);

    /**
     * @param int $count
     * @return self
     */
    public function setCount(int $count);
}
