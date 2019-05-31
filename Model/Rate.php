<?php

namespace Wexo\Shipping\Model;

use DateTime;
use Exception;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\Rule\Model\Condition\Combine;
use Magento\SalesRule\Model\Rule\Condition\CombineFactory;
use Wexo\Shipping\Api\Data\RateInterface;

class Rate extends AbstractModel implements RateInterface
{
    /**
     * @var AbstractCondition
     */
    protected $conditions = null;

    /**
     * @var Form
     */
    protected $form = null;

    /**
     * @var CombineFactory
     */
    private $combineConditionFactory;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param CombineFactory $combineConditionFactory
     * @param Json $serializer
     * @param FormFactory $formFactory
     * @param AbstractResource $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        CombineFactory $combineConditionFactory,
        Json $serializer,
        FormFactory $formFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->combineConditionFactory = $combineConditionFactory;
        $this->serializer = $serializer;
        $this->formFactory = $formFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init('Wexo\Shipping\Model\ResourceModel\Rate');
    }

    /**
     * @return string|null
     */
    public function getCarrierType(): ?string
    {
        return $this->getData(static::CARRIER_TYPE);
    }

    /**
     * @param string $carrierType
     * @return RateInterface
     */
    public function setCarrierType(string $carrierType): RateInterface
    {
        $this->setData(static::CARRIER_TYPE, $carrierType);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMethodType(): ?string
    {
        return $this->getData(static::METHOD_TYPE);
    }

    /**
     * @param string $methodType
     * @return RateInterface
     */
    public function setMethodType(string $methodType): RateInterface
    {
        $this->setData(static::METHOD_TYPE);
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return boolval($this->getData(static::IS_ACTIVE));
    }

    /**
     * @param bool $isActive
     * @return RateInterface
     */
    public function setIsActive(bool $isActive): RateInterface
    {
        $this->setData(static::IS_ACTIVE, $isActive);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSortOrder(): ?int
    {
        return $this->getData(static::SORT_ORDER);
    }

    /**
     * @param int $sortOrder
     * @return RateInterface
     */
    public function setSortOrder(int $sortOrder): RateInterface
    {
        $this->setData(static::SORT_ORDER, $sortOrder);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->getData(static::TITLE);
    }

    /**
     * @param string $title
     * @return RateInterface
     */
    public function setTitle(string $title): RateInterface
    {
        $this->setData(static::TITLE, $title);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->getData(static::PRICE);
    }

    /**
     * @param float $price
     * @return RateInterface
     */
    public function setPrice(float $price): RateInterface
    {
        $this->setData(static::PRICE, $price);
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAllowFree(): ?bool
    {
        return boolval($this->getData(static::ALLOW_FREE));
    }

    /**
     * @param bool $allowFree
     * @return $this
     */
    public function setAllowFree(bool $allowFree)
    {
        $this->setData(static::ALLOW_FREE, $allowFree);
        return $this;
    }

    /**
     * @return AbstractModel
     */
    public function beforeSave()
    {
        // Serialize conditions
        if ($this->getConditions()) {
            $this->setConditionsSerialized(
                $this->serializer->serialize(
                    $this->getConditions()->asArray()
                )
            );
            $this->conditions = null;
        }

        return parent::beforeSave();
    }

    /**
     * @return AbstractCondition
     */
    public function getConditions()
    {
        if (empty($this->conditions)) {
            $this->resetConditions();
        }

        if ($this->hasData(static::CONDITIONS_SERIALIZED)) {
            $conditions = $this->getConditionsSerialized();
            if (!empty($conditions)) {
                $conditions = $this->serializer->unserialize($conditions);
                if (is_array($conditions) && !empty($conditions)) {
                    $this->conditions->loadArray($conditions);
                }
            }
            $this->unsetData(static::CONDITIONS_SERIALIZED);
        }

        return $this->conditions;
    }

    /**
     * @param AbstractCondition $conditions
     * @return $this
     */
    public function setConditions(AbstractCondition $conditions)
    {
        $this->conditions = $conditions;
        return $this;
    }

    /**
     * Reset rule combine conditions
     *
     * @param null|Combine $conditions
     * @return Rate
     */
    protected function resetConditions($conditions = null)
    {
        if (null === $conditions) {
            $conditions = $this->getConditionsInstance();
        }
        $conditions->setData('rule', $this)->setData('id', '1')->setData('prefix', 'conditions');
        $this->setConditions($conditions);

        return $this;
    }

    /**
     * @return AbstractCondition
     */
    protected function getConditionsInstance()
    {
        return $this->combineConditionFactory->create();
    }

    /**
     * @return string|null
     */
    public function getConditionsSerialized(): ?string
    {
        return $this->getData(static::CONDITIONS_SERIALIZED);
    }

    /**
     * @param string $conditionalsSerialized
     * @return RateInterface
     */
    public function setConditionsSerialized(string $conditionalsSerialized): RateInterface
    {
        $this->setData(static::CONDITIONS_SERIALIZED, $conditionalsSerialized);
        return $this;
    }

    /**
     * Rule form getter
     *
     * @return Form
     * @throws LocalizedException
     */
    public function getForm()
    {
        if (!$this->form) {
            $this->form = $this->formFactory->create();
        }
        return $this->form;
    }

    /**
     * Retrieve condition field set id
     *
     * @param string $formName
     * @return string
     */
s
    public function getConditionsFieldSetId($formName = '')
    {
        return $formName . 'rule_conditions_fieldset_' . $this->getId();
    }

    /**
     * Initialize rule model data from array
     *
     * @param array $data
     * @return Rate
     * @throws Exception
     */
    public function loadPost(array $data)
    {
        $arr = $this->convertFlatToRecursive($data);
        if (isset($arr['conditions'])) {
            $this->getConditions()->setConditions([])->loadArray($arr['conditions'][1]);
        }

        return $this;
    }

    /**
     * Set specified data to current rule.
     * Set conditions and actions recursively.
     * Convert dates into \DateTime.
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    protected function convertFlatToRecursive(array $data)
    {
        $arr = [];
        foreach ($data as $key => $value) {
            if ($key === 'conditions' && is_array($value)) {
                foreach ($value as $id => $data) {
                    $path = explode('--', $id);
                    $node = &$arr;
                    for ($i = 0, $l = sizeof($path); $i < $l; $i++) {
                        if (!isset($node[$key][$path[$i]])) {
                            $node[$key][$path[$i]] = [];
                        }
                        $node = &$node[$key][$path[$i]];
                    }
                    foreach ($data as $k => $v) {
                        $node[$k] = $v;
                    }
                }
            } else {
                /**
                 * Convert dates into \DateTime
                 */
                if (in_array($key, ['from_date', 'to_date'], true) && $value) {
                    $value = new DateTime($value);
                }
                $this->setData($key, $value);
            }
        }

        return $arr;
    }
}

