<?php

namespace Wexo\Shipping\Controller\Adminhtml\Rate;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Wexo\Shipping\Api\Data\RateInterface;
use Wexo\Shipping\Api\Data\RateInterfaceFactory;
use Wexo\Shipping\Model\Provider\CurrentRate;
use Wexo\Shipping\Model\RateRepository;

class Edit extends Action
{
    const string ADMIN_RESOURCE = 'Wexo_Shipping::edit';

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param CurrentRate $currentRate
     * @param RateRepository $rateRepository
     * @param RateInterfaceFactory $rateInterfaceFactory
     */
    public function __construct(
        Context $context,
        private readonly PageFactory $resultPageFactory,
        private readonly CurrentRate $currentRate,
        private readonly RateRepository $rateRepository,
        private readonly RateInterfaceFactory $rateInterfaceFactory
    ) {
        parent::__construct($context);
    }

    /**
     * @return Page|Redirect|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute(): Page|Redirect|\Magento\Framework\Controller\Result\Redirect
    {
        $id = $this->getRequest()->getParam('entity_id');

        try {
            $rate = $this->rateRepository->get($id);

            if ($rate->getId() === '') {
                $this->messageManager->addErrorMessage(__('This Rate no longer exists'));
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }
        } catch (NoSuchEntityException) {
            /** @var RateInterface $rate */
            $rate = $this->rateInterfaceFactory->create();
        }

        $this->currentRate->setCurrentRate($rate);

        /** @var Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Rate') : __('New Rate'),
            $id ? __('Edit Rate') : __('New Rate')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Rates'));
        $resultPage->getConfig()->getTitle()
            ->prepend($rate->getId() ? $rate->getTitle() : __('New Rate'));

        return $resultPage;
    }

    /**
     * @return Page
     */
    protected function _initAction(): Page
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Wexo_Shipping::shipping')
            ->addBreadcrumb(__('Wexo Shipping'), __('Wexo Shipping'))
            ->addBreadcrumb(__('Manage Rates'), __('Manage Rates'));
        return $resultPage;
    }
}
