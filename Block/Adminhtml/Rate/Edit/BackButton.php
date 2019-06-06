<?php


namespace Wexo\Shipping\Block\Adminhtml\Rate\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->context = $context;
    }

    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->context->getUrlBuilder()->getUrl('*/*/')),
            'class' => 'back',
            'sort_order' => 10,
        ];
    }
}
