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
namespace Aheadworks\Rbslider\Model\ResourceModel\Indexer\BannerProduct\DataCollector;

use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Model\Source\PageType;
use Aheadworks\Rbslider\Model\Source\Status;
use Magento\Catalog\Api\Data\ProductInterface;
use Aheadworks\Rbslider\Model\Rule\Condition\Loader as ConditionLoader;

/**
 * Class Validator
 * @package Aheadworks\Rbslider\Model\ResourceModel\Indexer\BannerProduct\DataCollector
 */
class Validator
{
    /**
     * @var ConditionLoader
     */
    private $conditionLoader;

    /**
     * @param ConditionLoader $conditionLoader
     */
    public function __construct(
        ConditionLoader $conditionLoader
    ) {
        $this->conditionLoader = $conditionLoader;
    }

    /**
     * Check if banner is valid to be processed
     *
     * @param BannerInterface $banner
     * @return bool
     */
    public function isBannerValid($banner)
    {
        return $banner->getStatus() == Status::STATUS_ENABLED
            && $banner->getPageType() == PageType::PRODUCT_PAGE;
    }

    /**
     * Check if product is valid
     *
     * @param ProductInterface $product
     * @param BannerInterface $banner
     * @return bool
     */
    public function isProductValid($product, $banner)
    {
        $productConditionBanner = $this->conditionLoader->loadProductCondition($banner);
        $productConditions = $productConditionBanner->getConditions();

        return $productConditions->validate($product);
    }
}
