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

use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Model\Rule\ProductFactory as ProductRuleFactory;
use Aheadworks\Rbslider\Model\Rule\Product as ProductRule;
use Aheadworks\Rbslider\Model\Converter\Condition as ConditionConverter;

/**
 * Class Loader
 * @package Aheadworks\Rbslider\Model\Rule\Condition
 */
class Loader
{
    /**
     * @var ProductRule
     */
    private $productRules = [];

    /**
     * @var ProductRuleFactory
     */
    private $productRuleFactory;

    /**
     * @var ConditionConverter
     */
    private $conditionConverter;

    /**
     * @param ConditionConverter $conditionConverter
     * @param ProductFactory $productRuleFactory
     */
    public function __construct(
        ConditionConverter $conditionConverter,
        ProductRuleFactory $productRuleFactory
    ) {
        $this->conditionConverter = $conditionConverter;
        $this->productRuleFactory = $productRuleFactory;
    }

    /**
     * Load product conditions by banner
     *
     * @param BannerInterface $banner
     * @return ProductRule
     */
    public function loadProductCondition($banner)
    {
        if (!isset($this->productRules[$banner->getId()])) {
            /** @var ProductRule $productRule */
            $productRule = $this->productRuleFactory->create();
            if ($conditions = $banner->getProductCondition()) {
                $conditionArray = $this->conditionConverter->dataModelToArray($conditions);
                $productRule->setConditions([])
                    ->getConditions()
                    ->loadArray($conditionArray);
            } else {
                $productRule->setConditions([])
                    ->getConditions()
                    ->asArray();
            }
            $this->productRules[$banner->getId()] = $productRule;
        }

        return $this->productRules[$banner->getId()];
    }
}
