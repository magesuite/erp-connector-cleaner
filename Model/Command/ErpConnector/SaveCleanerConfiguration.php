<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Model\Command\ErpConnector;

class SaveCleanerConfiguration
{
    /**
     * @var \Creativestyle\CustomizationIpetOrder\Model\Data\OrderExportConfigurationFactory
     */
    protected $orderExportConfigurationFactory;

    /**
     * @var \Creativestyle\CustomizationIpetOrder\Api\OrderExportConfigurationRepositoryInterface
     */
    protected $orderExportConfigurationRepository;

    /**
     * @var \Creativestyle\CustomizationIpetOrder\Model\Command\OrderExport\Adminhtml\SaveAdditionalConfigurations
     */
    protected $saveAdditionalConfigurations;

    /**
     * @var \MageSuite\ErpConnector\Model\Command\Provider\SaveSchedulers
     */
    protected $saveSchedulers;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \MageSuite\ErpConnector\Logger\Logger
     */
    protected $logger;

    public function __construct(
        \Creativestyle\CustomizationIpetOrder\Model\Data\OrderExportConfigurationFactory $orderExportConfigurationFactory,
        \Creativestyle\CustomizationIpetOrder\Api\OrderExportConfigurationRepositoryInterface $orderExportConfigurationRepository,
        \Creativestyle\CustomizationIpetOrder\Model\Command\OrderExport\Adminhtml\SaveAdditionalConfigurations $saveAdditionalConfigurations,
        \MageSuite\ErpConnector\Model\Command\Provider\SaveSchedulers $saveSchedulers,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \MageSuite\ErpConnector\Logger\Logger $logger
    ) {
        $this->orderExportConfigurationFactory = $orderExportConfigurationFactory;
        $this->orderExportConfigurationRepository = $orderExportConfigurationRepository;
        $this->saveAdditionalConfigurations = $saveAdditionalConfigurations;
        $this->saveSchedulers = $saveSchedulers;
        $this->messageManager = $messageManager;
        $this->logger = $logger;
    }

    public function execute($provider, $orderExportConfiguration)
    {
        try {
            $model = $this->orderExportConfigurationRepository->getByProviderId($provider->getId());
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $model = $this->orderExportConfigurationFactory->create();
        }

        $model
            ->setProviderId($provider->getId())
            ->setIsVerificationNeeded($orderExportConfiguration['is_verification_needed'])
            ->setIncrement($orderExportConfiguration['increment'])
            ->setUsedPaymentMethod($orderExportConfiguration['used_payment_method'])
            ->setProductBrand($orderExportConfiguration['product_brand'])
            ->setExportStrategy($orderExportConfiguration['export_strategy'])
            ->setOrdersInBundleFileLimit((int)$orderExportConfiguration['orders_in_bundle_file_limit'] ?: null)
            ->setComment($orderExportConfiguration['comment'])
            ->setAddress($orderExportConfiguration['address']);

        try {
            $this->orderExportConfigurationRepository->save($model);

            $formData = $orderExportConfiguration['additional_configuration']['additional_configuration'] ?? [];
            $this->saveAdditionalConfigurations->execute($provider->getId(), $formData);

            $formData = $orderExportConfiguration['schedulers']['schedulers'] ?? [];
            $this->saveSchedulers->execute($provider->getId(), 'order_export', $formData);

            $formData = $orderExportConfiguration['verification_schedulers']['verification_schedulers'] ?? [];
            $this->saveSchedulers->execute($provider->getId(), 'order_export_verification', $formData);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the provider order export configuration: %1', $e->getMessage()));
        }
    }
}
