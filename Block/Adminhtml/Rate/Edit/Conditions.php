<?php

namespace Wexo\Shipping\Block\Adminhtml\Rate\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Form\Renderer\Fieldset;
use Magento\Framework\Data\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Wexo\Shipping\Api\Data\RateInterfaceFactory;
use Wexo\Shipping\Model\Provider\CurrentRate;
use Wexo\Shipping\Model\Rate;
use Wexo\Shipping\Model\RateRepository;

class Conditions extends Generic implements TabInterface
{
    /**
     * @var string
     */
    protected $_nameInLayout = 'conditions_apply_to';

    /**
     * Constructor
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param \Magento\Rule\Block\Conditions $conditions
     * @param Fieldset $rendererFieldset
     * @param RateInterfaceFactory $rateFactory
     * @param CurrentRate $currentRateProvider
     * @param RateRepository $rateRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        protected \Magento\Rule\Block\Conditions $conditions,
        protected Fieldset $rendererFieldset,
        private readonly RateInterfaceFactory $rateFactory,
        private readonly CurrentRate $currentRateProvider,
        private readonly RateRepository $rateRepository,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @inheritDoc
     *
     * @codeCoverageIgnore
     */
    public function getTabClass(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getTabUrl(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function isAjaxLoaded(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getTabLabel(): string
    {
        return __('Conditions');
    }

    /**
     * @inheritDoc
     */
    public function getTabTitle(): string
    {
        return __('Conditions');
    }

    /**
     * @inheritDoc
     */
    public function canShowTab(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isHidden(): bool
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return Generic
     * @throws LocalizedException
     */
    #[\Override]
    protected function _prepareForm(): Generic
    {
        $rate = $this->currentRateProvider->getCurrentRate();
        $form = $this->addTabToForm($rate);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Handles addition of conditions tab to supplied form.
     *
     * @param Rate|null $rate
     * @param string $fieldsetId
     * @param string $formName
     * @return Form
     * @throws LocalizedException
     */
    protected function addTabToForm(
        ?Rate $rate,
        string $fieldsetId = 'conditions_fieldset',
        string $formName = 'wexo_shipping_rate_form'
    ): Form {
        if (!$rate instanceof \Wexo\Shipping\Model\Rate) {
            $id = $this->getRequest()->getParam('entity_id');
            try {
                $rate = $this->rateRepository->get($id);
            } catch (NoSuchEntityException) {
                $rate = $this->rateFactory->create();
            }
        }

        $conditionsFieldSetId = $rate->getConditionsFieldSetId($formName);
        $newChildUrl = $this->getUrl(
            'wexo_shipping/rate/newConditionHtml/form/' . $conditionsFieldSetId,
            ['form_namespace' => $formName]
        );

        /** @var Form $form */
        $form = $this->_formFactory->create();
        $form->setData('html_id_prefix', 'rule_');
        $renderer = $this->rendererFieldset
            ->setTemplate('Magento_CatalogRule::promo/fieldset.phtml')
            ->setData('new_child_url', $newChildUrl)
            ->setData('field_set_id', $conditionsFieldSetId);

        $fieldset = $form->addFieldset(
            $fieldsetId,
            [
                'legend' => __(
                    'Allow the rate only if the following conditions are met (leave blank for always).'
                )
            ]
        )->setRenderer($renderer);

        $fieldset->addField(
            'conditions',
            'text',
            [
                'name' => 'conditions',
                'label' => __('Conditions'),
                'title' => __('Conditions'),
                'required' => true,
                'data-form-part' => $formName
            ]
        )->setData(
            'rule',
            $rate
        )->setRenderer(
            $this->conditions
        );

        $form->setValues($rate->getData());
        $this->setConditionFormName($rate->getConditions(), $formName);
        return $form;
    }

    /**
     * Handles addition of form name to condition and its conditions.
     *
     * @param AbstractCondition $conditions
     * @param string $formName
     * @return void
     */
    private function setConditionFormName(AbstractCondition $conditions, string $formName): void
    {
        $conditions->setData('form_name', $formName);
        if ($conditions->getConditions() && is_array($conditions->getConditions())) {
            foreach ($conditions->getConditions() as $condition) {
                $this->setConditionFormName($condition, $formName);
            }
        }
    }
}
