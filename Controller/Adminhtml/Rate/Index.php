<?php

namespace Wexo\Shipping\Controller\Adminhtml\Rate;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    const string ADMIN_RESOURCE = 'Wexo_Shipping::rates';

    /**
     * @param Action\Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Action\Context $context,
        private readonly PageFactory $pageFactory
    ) {
        parent::__construct($context);
    }

    /**
     * @return ResultInterface|ResponseInterface
     */
    public function execute(): ResultInterface|ResponseInterface
    {
        /** @var Page $page */
        $page = $this->pageFactory->create();
        $page->setActiveMenu('Wexo_Shipping::rates');
        $page->getConfig()->getTitle()->prepend(__('Wexo Shipping Rates'));

        return $page;
    }
}
