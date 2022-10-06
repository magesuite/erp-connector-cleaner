<?php
declare(strict_types=1);

namespace MageSuite\ErpConnectorCleaner\Controller\Adminhtml\History;

class Index extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'MageSuite_ErpConnectorCleaner::history';

    public function execute()
    {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $title = __('Clean Up History');
        $resultPage->setActiveMenu('MageSuite_ErpConnectorCleaner::clean_up_history');
        $resultPage->addBreadcrumb($title, $title);
        $resultPage->getConfig()->getTitle()->prepend($title);

        return $resultPage;
    }
}
