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
namespace Aheadworks\Rbslider\Model\Block\Listing\Banner;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Model\Source\Status;
use Aheadworks\Rbslider\Api\BannerRepositoryInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;

/**
 * Class Builder
 * @package Aheadworks\Rbslider\Model\Block\Listing\Banner
 */
class Builder
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var BannerRepositoryInterface
     */
    private $bannerRepository;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param BannerRepositoryInterface $bannerRepository
     * @param SortOrderBuilder $sortOrderBuilder
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        BannerRepositoryInterface $bannerRepository,
        SortOrderBuilder $sortOrderBuilder
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->bannerRepository = $bannerRepository;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    /**
     * Retrieve actual banner list
     *
     * @return BannerInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function prepareActualBannerList()
    {
        return $this->bannerRepository
            ->getList($this->buildSearchCriteria())
            ->getItems();
    }

    /**
     * Retrieves search criteria builder
     *
     * @return SearchCriteriaBuilder
     */
    public function getSearchCriteriaBuilder()
    {
        return $this->searchCriteriaBuilder;
    }

    /**
     * Build search criteria
     *
     * @return \Magento\Framework\Api\SearchCriteria
     */
    private function buildSearchCriteria()
    {
        $this->prepareSearchCriteriaBuilder();
        return $this->searchCriteriaBuilder->create();
    }

    /**
     * Prepares search criteria builder
     *
     * @return void
     */
    private function prepareSearchCriteriaBuilder()
    {
        $sortOrder = $this->sortOrderBuilder
            ->setField(BannerInterface::SORT_ORDER)
            ->setDirection(SortOrder::SORT_ASC)
            ->create();

        $this->searchCriteriaBuilder
            ->addFilter(BannerInterface::STATUS, Status::STATUS_ENABLED)
            ->addSortOrder($sortOrder);
    }
}
