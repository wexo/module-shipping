<?php

namespace Wexo\Shipping\Controller\Adminhtml\Rate;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Wexo\Shipping\Api\Data\RateInterface;
use Wexo\Shipping\Api\Data\RateInterfaceFactory;
use Wexo\Shipping\Model\RateRepository;

class Save extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Wexo_Shipping::edit';

    /**
     * @var RateRepository
     */
    private $repository;

    /**
     * @var RateInterfaceFactory
     */
    private $rateInterfaceFactory;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    public function __construct(
        Action\Context $context,
        RateRepository $repository,
        RateInterfaceFactory $rateInterfaceFactory,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->repository = $repository;
        $this->rateInterfaceFactory = $rateInterfaceFactory;
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
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {

            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = RateInterface::STATUS_ENABLED;
            }

            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }

            if (isset($data['store_id'])) {
                $data['store_id'] = implode(',', $data['store_id']);
            }

            $rate = $this->rateInterfaceFactory->create();

            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                try {
                    $rate = $this->repository->get($id);
                } catch (NoSuchEntityException $e) {
                    $this->messageManager->addErrorMessage(__('This rate no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            if (isset($data['rule'])) {
                $rate->loadPost($data['rule']);
                unset($data['conditions_serialized']);
                unset($data['rule']);
            }

            $rate->setData($data);

            try {
                $this->repository->save($rate);
                $this->messageManager->addSuccessMessage(__('You saved the rate.'));
                $this->dataPersistor->clear('cms_page');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $rate->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the rate.'));
            }

            $this->dataPersistor->set('wexo_shipping_rate', $data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
