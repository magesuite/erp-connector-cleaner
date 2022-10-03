<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Ui\Component\Listing\Column;

class ProviderName extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected \MageSuite\ErpConnector\Api\ProviderRepositoryInterface $providerRepository;

    protected $providerIdToNameMap = [];

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \MageSuite\ErpConnector\Api\ProviderRepositoryInterface $providerRepository,
        array $components = [],
        array $data = []
    ) {
        $this->providerRepository = $providerRepository;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $fieldName = $this->getData('name');

        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['provider_id'])) {
                $item[$fieldName] = '';
                continue;
            }

            $item[$fieldName] = $this->getProviderName($item['provider_id']);
        }

        return $dataSource;
    }

    protected function getProviderName($providerId)
    {
        if (!isset($this->providerIdToNameMap[$providerId])) {
            $provider = $this->providerRepository->getById($providerId);
            $this->providerIdToNameMap[$providerId] = $provider->getName();
        }

        return $this->providerIdToNameMap[$providerId];
    }
}
