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
     * @param $id
     * @return self
     */
    public function setId($id);

    /**
     * @param $providerId
     * @return self
     */
    public function setProviderId($providerId);

    /**
     * @param $connectorId
     * @return self
     */
    public function setConnectorId($connectorId);

    /**
     * @param $createdAt
     * @return self
     */
    public function setCreatedAt($createdAt);

    /**
     * @param $firstFileName
     * @return self
     */
    public function setFirstFileName($firstFileName);

    /**
     * @param $lastFileName
     * @return self
     */
    public function setLastFileName($lastFileName);

    /**
     * @param $count
     * @return self
     */
    public function setCount($count);
}
