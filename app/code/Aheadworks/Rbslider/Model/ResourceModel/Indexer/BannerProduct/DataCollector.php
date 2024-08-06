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
namespace Aheadworks\Rbslider\Model\ResourceModel\Indexer\BannerProduct;

use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Aheadworks\Rbslider\Model\ResourceModel\Indexer\BannerProduct\DataCollector\Validator;
use Aheadworks\Rbslider\Model\Rule\Condition\Loader as ConditionLoader;

/**
 * Class DataCollector
 * @package Aheadworks\Rbslider\Model\ResourceModel\Indexer\BannerProduct
 */
class DataCollector
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var ConditionLoader
     */
    private $conditionLoader;

    /**
     * @param Validator $validator
     * @param ConditionLoader $conditionLoader
     */
    public function __construct(
        Validator $validator,
        ConditionLoader $conditionLoader
    ) {
        $this->validator = $validator;
        $this->conditionLoader = $conditionLoader;
    }

    /**
     * Prepare banner data for all matching products
     *
     * @param BannerInterface $banner
     * @return array
     */
    public function prepareBannerData(BannerInterface $banner)
    {
        $data = [];

        if ($this->validator->isBannerValid($banner)) {
            $bannerId = $banner->getId();
            $productIds = $this->conditionLoader->loadProductCondition($banner)->getMatchingProductIds();

            foreach ($productIds as $productId) {
                $data[] = [
                    BannerProductInterface::BANNER_ID => $bannerId,
                    BannerProductInterface::PRODUCT_ID => $productId
                ];
            }
        }

        return $data;
    }

    /**
     * Prepare banner product data for specific product
     *
     * @param BannerInterface $banner
     * @param ProductInterface $product
     * @return array
     */
    public function prepareBannerProductData(BannerInterface $banner, ProductInterface $product)
    {
        $data = [];

        if ($this->validator->isBannerValid($banner)
            && $this->validator->isProductValid($product, $banner)
        ) {
            $data = [
                BannerProductInterface::BANNER_ID => $banner->getId(),
                BannerProductInterface::PRODUCT_ID => $product->getId()
            ];
        } else {
            return $data;
        }

        return [$data];
    }
}
