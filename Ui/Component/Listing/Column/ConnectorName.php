<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Ui\Component\Listing\Column;

class ConnectorName extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected \MageSuite\ErpConnector\Api\ConnectorRepositoryInterface $connectorRepository;

    protected $connectorIdToNameMap = [];

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \MageSuite\ErpConnector\Api\ConnectorRepositoryInterface $connectorRepository,
        array $components = [],
        array $data = []
    ) {
        $this->connectorRepository = $connectorRepository;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $fieldName = $this->getData('name');

        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['connector_id'])) {
                $item[$fieldName] = '';
                continue;
            }

            $item[$fieldName] = $this->getConnectorName($item['connector_id']);
        }

        return $dataSource;
    }

    protected function getConnectorName($connectorId)
    {
        if (!isset($this->connectorIdToNameMap[$connectorId])) {
            $connector = $this->connectorRepository->getById($connectorId);
            $this->connectorIdToNameMap[$connectorId] = $connector->getName();
        }

        return $this->connectorIdToNameMap[$connectorId];
    }
}
