<?php

namespace Wexo\Shipping\Controller\Adminhtml\Rate;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Wexo\Shipping\Model\RateRepository;

class Delete extends Action
{
    const ADMIN_RESOURCE = 'Wexo_Shipping::edit';

    /**
     * @var RateRepository
     */
    private $repository;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @param Action\Context $context
     * @param RateRepository $repository
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        RateRepository $repository,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->repository = $repository;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return ResultInterface|ResponseInterface
     * @throws Exception
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                $this->repository->delete(
                    $this->repository->get($id)
                );
                $this->messageManager->addSuccessMessage(__('Rate was deleted'));

            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This rate no longer exists.'));
            }

            $this->dataPersistor->clear('wexo_shipping_rate');
            return $resultRedirect->setPath('*/*/');
        }

        $this->messageManager->addErrorMessage(__('This rate could not be found.'));
        return $resultRedirect->setPath('*/*/');
    }
}
