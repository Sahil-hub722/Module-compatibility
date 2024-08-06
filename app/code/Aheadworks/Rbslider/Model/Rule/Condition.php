<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Rbslider
 * @version    1.4.5
 * @copyright  Copyright (c) 2022 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Rbslider\Model\Rule;

use Aheadworks\Rbslider\Api\Data\ConditionInterface;
use Magento\Framework\Api\AbstractExtensibleObject;
use Aheadworks\Rbslider\Api\Data\ConditionExtensionInterface;

/**
 * Class Condition
 * @package Aheadworks\Rbslider\Model\Rule
 */
class Condition extends AbstractExtensibleObject implements ConditionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->_get(self::TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function getConditions()
    {
        return $this->_get(self::CONDITIONS);
    }

    /**
     * {@inheritdoc}
     */
    public function getAggregator()
    {
        return $this->_get(self::AGGREGATOR);
    }

    /**
     * {@inheritdoc}
     */
    public function getOperator()
    {
        return $this->_get(self::OPERATOR);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute()
    {
        return $this->_get(self::ATTRIBUTE);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->_get(self::VALUE);
    }

    /**
     * {@inheritdoc}
     */
    public function getValueType()
    {
        return $this->_get(self::VALUE_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function setConditions(array $conditions = null)
    {
        return $this->setData(self::CONDITIONS, $conditions);
    }

    /**
     * {@inheritdoc}
     */
    public function setAggregator($aggregator)
    {
        return $this->setData(self::AGGREGATOR, $aggregator);
    }

    /**
     * {@inheritdoc}
     */
    public function setOperator($operator)
    {
        return $this->setData(self::OPERATOR, $operator);
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute($attribute)
    {
        return $this->setData(self::ATTRIBUTE, $attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function setValueType($valueType)
    {
        return $this->setData(self::VALUE_TYPE, $valueType);
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(ConditionExtensionInterface $extensionAttributes)
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}
