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
namespace Aheadworks\Rbslider\Model\ResourceModel;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Aheadworks\Rbslider\Api\BlockRepositoryInterface;
use Aheadworks\Rbslider\Api\Data\BlockSearchResultsInterfaceFactory;
use Aheadworks\Rbslider\Model\Block\Factory as BlockFactory;
use Aheadworks\Rbslider\Api\Data\BlockSearchResultsInterface;
use Aheadworks\Rbslider\Model\Block\Listing\Processor as ListingProcessor;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class BlockRepository
 * @package Aheadworks\Rbslider\Model\ResourceModel
 */
class BlockRepository implements BlockRepositoryInterface
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var BlockSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * @var ListingProcessor
     */
    private $listingProcessor;

    /**
     * @var array
     */
    private $registryByType = [];

    /**
     * @var array
     */
    private $registryById = [];

    /**
     * @var array
     */
    private $registryByIdForWidget = [];

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param BlockSearchResultsInterfaceFactory $searchResultsFactory
     * @param BlockFactory $blockFactory
     * @param ListingProcessor $listingProcessor
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        BlockSearchResultsInterfaceFactory $searchResultsFactory,
        BlockFactory $blockFactory,
        ListingProcessor $listingProcessor
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->blockFactory = $blockFactory;
        $this->listingProcessor = $listingProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function getList($blockType, $storeId, $customerGroupId)
    {
        $cacheKey = implode('-', [$blockType, $storeId, $customerGroupId]);
        if (!isset($this->registryByType[$cacheKey])) {
            $blockItems = [];
            $bannerList = $this->listingProcessor->getBannerList($blockType);
            if (!empty($bannerList)) {
                $slideList = $this->listingProcessor->getSlideListForBanners(
                    $bannerList,
                    $storeId,
                    $customerGroupId
                );
                $blockItems = $this->blockFactory->createBlocks($bannerList, $slideList);
            }

            /** @var BlockSearchResultsInterface $blockSearchResults */
            $blockSearchResults = $this->searchResultsFactory->create()
                ->setSearchCriteria($this->searchCriteriaBuilder->create());
            $blockSearchResults->setItems($blockItems);
            $blockSearchResults->setTotalCount(count($blockItems));
            $this->registryByType[$cacheKey] = $blockSearchResults;
        }

        return $this->registryByType[$cacheKey];
    }

    /**
     * {@inheritdoc}
     */
    public function getByBannerId($bannerId, $storeId, $customerGroupId)
    {
        $cacheKey = implode('-', [$bannerId, $storeId, $customerGroupId]);
        if (!isset($this->registryById[$cacheKey])) {
            $banner = $this->listingProcessor->getBannerById($bannerId);
            if ($banner) {
                $slideList = $this->listingProcessor->getSlideListForBanners([$banner], $storeId, $customerGroupId);
                $block = $this->blockFactory->createBlock($banner, $slideList);
            } else {
                throw NoSuchEntityException::singleField('bannerId', $bannerId);
            }
            $this->registryById[$cacheKey] = $block;
        }

        return $this->registryById[$cacheKey];
    }

    /**
     * {@inheritdoc}
     */
    public function getByBannerIdForWidget($bannerId, $storeId, $customerGroupId)
    {
        $cacheKey = implode('-', [$bannerId, $storeId, $customerGroupId]);
        if (!isset($this->registryByIdForWidget[$cacheKey])) {
            $banner = $this->listingProcessor->getBannerByIdForWidget($bannerId);
            if ($banner) {
                $slideList = $this->listingProcessor->getSlideListForBanners([$banner], $storeId, $customerGroupId);
                $block = $this->blockFactory->createBlock($banner, $slideList);
            } else {
                throw NoSuchEntityException::singleField('bannerId', $bannerId);
            }
            $this->registryByIdForWidget[$cacheKey] = $block;
        }

        return $this->registryByIdForWidget[$cacheKey];
    }
}
