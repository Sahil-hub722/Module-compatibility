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
namespace Aheadworks\Rbslider\Model\Block\Listing;

use Aheadworks\Rbslider\Model\Banner\Validator;
use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Api\Data\SlideInterface;
use Aheadworks\Rbslider\Model\Block\Listing\Banner\Builder as BannerBuilder;
use Aheadworks\Rbslider\Model\Block\Listing\Slide\Builder as SlideBuilder;
use Aheadworks\Rbslider\Model\Source\PageType;

/**
 * Class Processor
 * @package Aheadworks\Rbslider\Model\Block\Listing
 */
class Processor
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var BannerBuilder
     */
    private $bannerBuilder;

    /**
     * @var SlideBuilder
     */
    private $slideBuilder;

    /**
     * @param Validator $validator
     * @param BannerBuilder $bannerBuilder
     * @param SlideBuilder $slideBuilder
     */
    public function __construct(
        Validator $validator,
        BannerBuilder $bannerBuilder,
        SlideBuilder $slideBuilder
    ) {
        $this->validator = $validator;
        $this->bannerBuilder = $bannerBuilder;
        $this->slideBuilder = $slideBuilder;
    }

    /**
     * Prepare banner list for specified block type
     *
     * @param int $blockType
     * @return BannerInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBannerList($blockType)
    {
        $this->bannerBuilder
            ->getSearchCriteriaBuilder()
            ->addFilter(BannerInterface::PAGE_TYPE, $blockType);
        $bannerList = $this->bannerBuilder->prepareActualBannerList();
        $validBannerList = $this->prepareValidBanners($bannerList);

        return $validBannerList;
    }

    /**
     * Prepare banner by specified ID
     *
     * @param int $bannerId
     * @return BannerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBannerById($bannerId)
    {
        $this->bannerBuilder
            ->getSearchCriteriaBuilder()
            ->addFilter(BannerInterface::ID, $bannerId);
        $bannerList = $this->bannerBuilder->prepareActualBannerList();
        $validBannerList = $this->prepareValidBanners($bannerList);

        return reset($validBannerList);
    }

    /**
     * Prepare banner by specified ID for widget
     *
     * @param int $bannerId
     * @return BannerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBannerByIdForWidget($bannerId)
    {
        $this->bannerBuilder
            ->getSearchCriteriaBuilder()
            ->addFilter(BannerInterface::PAGE_TYPE, PageType::CUSTOM_WIDGET);
        return $this->getBannerById($bannerId);
    }

    /**
     * Prepare slide list for banners
     *
     * @param BannerInterface[] $bannerList
     * @param int $storeId
     * @param int $customerGroupId
     * @return SlideInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSlideListForBanners($bannerList, $storeId, $customerGroupId)
    {
        $this->slideBuilder
            ->getSearchCriteriaBuilder()
            ->addFilter(SlideInterface::STORE_IDS, $storeId)
            ->addFilter(SlideInterface::CUSTOMER_GROUP_IDS, $customerGroupId);
        $slideList = $this->slideBuilder->prepareActualSlideList($bannerList, $storeId);

        return $slideList;
    }

    /**
     * Prepare valid banners
     *
     * @param BannerInterface[] $bannerList
     * @return BannerInterface[]
     */
    private function prepareValidBanners($bannerList)
    {
        $validBanners = [];
        foreach ($bannerList as $banner) {
            if (!empty($banner->getSlideIds()) && $this->validator->canShow($banner)) {
                $validBanners[] = $banner;
            }
        }

        return $validBanners;
    }
}
