<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Ui\Component\Listing\Column;

class CreatedAt extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected \Magento\Framework\Stdlib\DateTime\Timezone $timezone;

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\Stdlib\DateTime\Timezone $timezone,
        array $components = [],
        array $data = []
    ) {
        $this->timezone = $timezone;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $fieldName = $this->getData('name');

        foreach ($dataSource['data']['items'] as &$item) {
            $item[$fieldName] = $this->timezone
                ->date(new \DateTime($item[$fieldName]))
                ->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
        }

        return $dataSource;
    }
}
