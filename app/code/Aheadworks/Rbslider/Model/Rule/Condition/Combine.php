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
namespace Aheadworks\Rbslider\Model\Rule\Condition;

use Magento\Rule\Model\Condition\Context;
use Magento\Rule\Model\Condition\Combine as RuleCombine;
use Aheadworks\Rbslider\Model\Rule\Condition\Product\AttributesFactory;

/**
 * Class Combine
 * @package Aheadworks\Rbslider\Model\Rule\Condition
 */
class Combine extends RuleCombine
{
    /**
     * @var AttributesFactory
     */
    private $attributeFactory;

    /**
     * @param Context $context
     * @param AttributesFactory $attributesFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        AttributesFactory $attributesFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->attributeFactory = $attributesFactory;
        $this->setType(Combine::class);
    }

    /**
     * Prepare child rules option list
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $conditions = [
            [
                'value' => $this->getType(),
                'label' => __('Conditions Combination')
            ],
            $this->attributeFactory->create()->getNewChildSelectOptions()
        ];

        $conditions = array_merge_recursive(parent::getNewChildSelectOptions(), $conditions);

        return $conditions;
    }

    /**
     * Return conditions
     *
     * @return array|mixed
     */
    public function getConditions()
    {
        if ($this->getData($this->getPrefix()) === null) {
            $this->setData($this->getPrefix(), []);
        }
        return $this->getData($this->getPrefix());
    }

    /**
     * Collect the valid attributes
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection
     * @return $this
     */
    public function collectValidatedAttributes($productCollection)
    {
        foreach ($this->getConditions() as $condition) {
            $condition->collectValidatedAttributes($productCollection);
        }
        return $this;
    }
}
