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
use Wexo\Shipping\Api\Data\RateInterface;
use Magento\SalesRule\Model\Rule\Condition\CombineFactory;

class Rate extends AbstractModel implements RateInterface
{
    /**
     * @var AbstractCondition|null
     */
    protected ?AbstractCondition $conditions;

    /**
     * @var Form
     */
    protected Form $form;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param CombineFactory $combineConditionFactory
     * @param Json $serializer
     * @param FormFactory $formFactory
     * @param ?AbstractResource $resource
     * @param ?AbstractDb $resourceCollection
     * @param array $data
     * @throws LocalizedException
     */
    public function __construct(
        Context $context,
        Registry $registry,
        private CombineFactory $combineConditionFactory,
        private readonly Json $serializer,
        private readonly FormFactory $formFactory,
        ?AbstractResource $resource = null,
        ?AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->combineConditionFactory = $combineConditionFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    public function _construct(): void
    {
        $this->_init(ResourceModel\Rate::class);
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function getId(): string
    {
        return (string) parent::getId();
    }

    /**
     * @inheritDoc
     */
    public function getCarrierType(): string
    {
        return $this->getData(static::CARRIER_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setCarrierType(string $carrierType): RateInterface
    {
        $this->setData(static::CARRIER_TYPE, $carrierType);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMethodType(): string
    {
        return $this->getData(static::METHOD_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setMethodType(string $methodType): RateInterface
    {
        $this->setData(static::METHOD_TYPE, $methodType);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIsActive(): int
    {
        return (int) $this->getData(static::IS_ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function setIsActive(int|null $isActive): RateInterface
    {
        $this->setData(static::IS_ACTIVE, $isActive);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSortOrder(): int
    {
        return (int) $this->getData(static::SORT_ORDER);
    }

    /**
     * @inheritDoc
     */
    public function setSortOrder(int|null $sortOrder): RateInterface
    {
        $this->setData(static::SORT_ORDER, $sortOrder);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return $this->getData(static::TITLE);
    }

    /**
     * @inheritDoc
     */
    public function setTitle(string $title): RateInterface
    {
        $this->setData(static::TITLE, $title);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPrice(): string
    {
        return $this->getData(static::PRICE);
    }

    /**
     * @inheritDoc
     */
    public function setPrice(float $price): RateInterface
    {
        $this->setData(static::PRICE, $price);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAllowFree(): bool
    {
        return boolval($this->getData(static::ALLOW_FREE));
    }

    /**
     * @inheritDoc
     */
    public function setAllowFree(bool $allowFree): static
    {
        $this->setData(static::ALLOW_FREE, $allowFree);
        return $this;
    }

    /**
     * @return AbstractModel
     */
    #[\Override]
    public function beforeSave(): AbstractModel
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
    public function getConditions(): AbstractCondition
    {
        if (empty($this->conditions)) {
            $this->resetConditions();
        }

        if ($this->hasData(static::CONDITIONS_SERIALIZED)) {
            $conditions = $this->getConditionsSerialized();
            if (!$conditions === null && $conditions !== '') {
                $conditions = $this->serializer->unserialize($conditions);
                if (is_array($conditions) && $conditions !== []) {
                    $this->conditions->loadArray($conditions);
                }
            }
            $this->unsetData(static::CONDITIONS_SERIALIZED);
        }

        return $this->conditions;
    }

    /**
     * @param AbstractCondition $condition
     * @return $this
     */
    public function setConditions(AbstractCondition $condition): RateInterface
    {
        $this->conditions = $condition;
        return $this;
    }

    /**
     * Reset rule combine conditions
     *
     * @param ?Combine $conditions
     * @return Rate
     */
    protected function resetConditions(?Combine $conditions = null): self
    {

        $conditions ??= $this->getConditionsInstance();

        $conditions->setData('rule', $this)->setData('id', '1')->setData('prefix', 'conditions');
        $this->setConditions($conditions);

        return $this;
    }

    /**
     * @return AbstractCondition
     */
    protected function getConditionsInstance(): AbstractCondition
    {
        return $this->combineConditionFactory->create();
    }

    /**
     * @inheritDoc
     */
    public function getConditionsSerialized(): string
    {
        return $this->getData(static::CONDITIONS_SERIALIZED);
    }

    /**
     * @inheritDoc
     */
    public function setConditionsSerialized(string|null $conditionalsSerialized): RateInterface
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
    public function getForm(): Form
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
    public function getConditionsFieldSetId(string $formName = ''): string
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
    public function loadPost(array $data): static
    {
        $arr = $this->convertFlatToRecursive($data);
        if (isset($arr['conditions'])) {
            $this->setConditions(
                $this->getConditions()->setData('conditions', [])->loadArray($arr['conditions'][1])
            );
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
    protected function convertFlatToRecursive(array $data): array
    {
        $arr = [];
        foreach ($data as $key => $value) {
            if ($key === 'conditions' && is_array($value)) {
                foreach ($value as $id => $data) {
                    $path = explode('--', (string) $id);
                    $node = &$arr;
                    for ($i = 0, $l = count($path); $i < $l; $i++) {
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

    /**
     * @inheritDoc
     */
    public function getStoreId(): string
    {
        return $this->getData(static::STORE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setStoreId($storeIds): RateInterface
    {
        return $this->setData(static::STORE_ID, $storeIds);
    }

    /**
     * @return string
     */
    public function getCustomerGroups(): string
    {
        return $this->getData(static::CUSTOMER_GROUPS);
    }

    /**
     * @param string $groups
     * @return RateInterface
     */
    public function setCustomerGroups(string $groups): RateInterface
    {
        return $this->setData(static::CUSTOMER_GROUPS, $groups);
    }
}
