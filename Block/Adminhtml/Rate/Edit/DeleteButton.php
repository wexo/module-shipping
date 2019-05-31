<?php

namespace Wexo\Shipping\Block\Adminhtml\Rate\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Mirasvit\Rewards\Ui\General\Form\Control\GenericButton;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getId()) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to do this?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    public function getId()
    {
        return $this->context->getRequest()->getParam('entity_id');
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['entity_id' => $this->getId()]);
    }
}
