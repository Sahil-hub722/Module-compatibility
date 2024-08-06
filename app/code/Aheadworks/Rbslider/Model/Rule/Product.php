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

use Aheadworks\Rbslider\Model\Rule\Condition\Combine;
use Aheadworks\Rbslider\Model\Rule\Condition\CombineFactory;
use Magento\CatalogRule\Model\Rule\Action\CollectionFactory as ActionCollectionFactory;
use Aheadworks\Rbslider\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Aheadworks\Rbslider\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Rule\Model\AbstractModel;
use Magento\CatalogRule\Model\Rule\Action\Collection as ActionCollection;
use Magento\Catalog\Model\ProductFactory;

/**
 * Class Product
 *
 * @package Aheadworks\Rbslider\Model\Rule
 */
class Product extends AbstractModel
{
    /**
     * Store matched product Ids
     *
     * @var array
     */
    private $productIds = null;

    /**
     * @var CombineFactory
     */
    private $combineFactory;

    /**
     * @var ActionCollectionFactory
     */
    private $actionCollectionFactory;

    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param TimezoneInterface $localeDate
     * @param CombineFactory $combineFactory
     * @param ActionCollectionFactory $actionCollectionFactory
     * @param ProductCollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        TimezoneInterface $localeDate,
        CombineFactory $combineFactory,
        ActionCollectionFactory $actionCollectionFactory,
        ProductCollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->combineFactory = $combineFactory;
        $this->actionCollectionFactory = $actionCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $registry, $formFactory, $localeDate, null, null, $data);
    }

    /**
     * Getter for rule conditions collection
     *
     * @return Combine
     */
    public function getConditionsInstance()
    {
        return $this->combineFactory->create();
    }

    /**
     * Getter for rule actions collection
     *
     * @return ActionCollection
     */
    public function getActionsInstance()
    {
        return $this->actionCollectionFactory->create();
    }

    /**
     * Reset rule combine conditions
     *
     * @param Combine|null $conditions
     * @return $this
     */
    protected function _resetConditions($conditions = null)
    {
        parent::_resetConditions($conditions);
        $this->getConditions($conditions)
            ->setId('1')
            ->setPrefix('rbslider');
        return $this;
    }

    /**
     * Get validated product ids
     *
     * @return array
     */
    public function getMatchingProductIds()
    {
        if ($this->productIds === null) {
            $this->productIds = [];
            $productIds = [];
            $this->setCollectedAttributes([]);

            /** @var ProductCollection $productCollection */
            $productCollection = $this->productCollectionFactory->create();
            $this->getConditions()->collectValidatedAttributes($productCollection);

            foreach ($productCollection->getItems() as $product) {
                if ($this->getConditions()->validate($product)) {
                    $productIds[] = $product->getEntityId();
                }
            }
            $this->productIds = array_unique($productIds);
        }

        return $this->productIds;
    }
}
