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
namespace Aheadworks\Rbslider\Model\Source;

use Magento\Catalog\Model\Product as CatalogProduct;
use Magento\Catalog\Model\Category as CatalogCategory;
use Magento\Framework\Data\OptionSourceInterface;
use Aheadworks\Rbslider\Model\ThirdPartyModule\Manager;

/**
 * Class PageType
 * @package Aheadworks\Rbslider\Model\Source
 */
class PageType implements OptionSourceInterface
{
    /**
     * Page type values
     */
    const HOME_PAGE = 1;
    const PRODUCT_PAGE = 2;
    const CATEGORY_PAGE = 3;
    const CUSTOM_WIDGET = 4;
    const AW_BLOG_HOME_PAGE = 5;

    /**
     * @var Manager
     */
    private $thirdPartyModuleManager;

    /**
     * @param Manager $thirdPartyModuleManager
     */
    public function __construct(
        Manager $thirdPartyModuleManager
    ) {
        $this->thirdPartyModuleManager = $thirdPartyModuleManager;
    }

    /**
     * @return array
     */
    public function getOptionArray()
    {
        $optionArray = [];
        foreach ($this->toOptionArray() as $option) {
            $optionArray[$option['value']] = $option['label'];
        }
        return $optionArray;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            ['value' => self::HOME_PAGE,  'label' => __('Home Page')],
            ['value' => self::PRODUCT_PAGE,  'label' => __('Product Pages')],
            ['value' => self::CATEGORY_PAGE,  'label' => __('Catalog Pages')],
            ['value' => self::CUSTOM_WIDGET,  'label' => __('Custom Widget')],
        ];

        if ($this->thirdPartyModuleManager->isAwBlogEnabled()) {
            $blogOptions = [
                ['value' => self::AW_BLOG_HOME_PAGE,  'label' => __('Aheadworks Blog Home Page')],
            ];
            $options = array_merge($options, $blogOptions);
        }

        return $options;
    }

    /**
     * Get identity tags map by page type
     *
     * @return array
     */
    public function getIdentityTagsMapByPageType()
    {
        return [
            self::HOME_PAGE => [],
            self::PRODUCT_PAGE => [CatalogProduct::CACHE_TAG],
            self::CATEGORY_PAGE => [CatalogCategory::CACHE_TAG],
            self::CUSTOM_WIDGET => [CatalogProduct::CACHE_TAG, CatalogCategory::CACHE_TAG],
            self::AW_BLOG_HOME_PAGE => []
        ];
    }
}
