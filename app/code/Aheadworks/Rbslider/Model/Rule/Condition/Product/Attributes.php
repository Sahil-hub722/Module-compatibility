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
namespace Aheadworks\Rbslider\Model\Rule\Condition\Product;

use Magento\Rule\Model\Condition\Context as ConditionContext;
use Magento\Backend\Helper\Data as BackendHelperData;
use Magento\Eav\Model\Config as EavModelConfig;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product as ProductResourceModel;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection as AttrSetCollection;
use Magento\Framework\Locale\FormatInterface;
use Magento\CatalogRule\Model\Rule\Condition\Product as CatalogRuleCondition;

/**
 * Class Attributes
 * @package Aheadworks\Rbslider\Model\Rule\Condition\Product
 */
class Attributes extends CatalogRuleCondition
{
    /**
     * @param ConditionContext $context
     * @param BackendHelperData $backendData
     * @param EavModelConfig $config
     * @param ProductFactory $productFactory
     * @param ProductRepositoryInterface $productRepository
     * @param ProductResourceModel $productResource
     * @param AttrSetCollection $attrSetCollection
     * @param FormatInterface $localeFormat
     * @param array $data
     */
    public function __construct(
        ConditionContext $context,
        BackendHelperData $backendData,
        EavModelConfig $config,
        ProductFactory $productFactory,
        ProductRepositoryInterface $productRepository,
        ProductResourceModel $productResource,
        AttrSetCollection $attrSetCollection,
        FormatInterface $localeFormat,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $backendData,
            $config,
            $productFactory,
            $productRepository,
            $productResource,
            $attrSetCollection,
            $localeFormat,
            $data
        );
        $this->setType(Attributes::class);
        $this->setValue(null);
    }

    /**
     * Prepare child rules option list
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $attributes = $this->loadAttributeOptions()->getAttributeOption();
        $conditions = [];
        foreach ($attributes as $code => $label) {
            $conditions[] = ['value' => $this->getType() . '|' . $code, 'label' => $label];
        }

        return ['value' => $conditions, 'label' => __('Product Attributes')];
    }
}
