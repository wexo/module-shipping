<?php


namespace Wexo\Shipping\Block\Adminhtml\Rate\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Mirasvit\Rewards\Ui\General\Form\Control\GenericButton;

class BackButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getUrl('*/*/')),
            'class' => 'back',
            'sort_order' => 10,
        ];
    }
}
