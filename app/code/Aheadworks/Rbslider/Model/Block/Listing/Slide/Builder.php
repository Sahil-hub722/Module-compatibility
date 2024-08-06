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
namespace Aheadworks\Rbslider\Model\Block\Listing\Slide;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Cms\Model\Template\FilterProvider;
use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Model\Source\Status;
use Aheadworks\Rbslider\Api\SlideRepositoryInterface;
use Aheadworks\Rbslider\Api\Data\SlideInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Api\SortOrder;

/**
 * Class Builder
 * @package Aheadworks\Rbslider\Model\Block\Listing\Slide
 */
class Builder
{
    /**
     * Segments key
     */
    const SEGMENTS_KEY = 'aw_cs_customer_segment_ids';

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FilterProvider
     */
    private $filterProvider;

    /**
     * @var SlideRepositoryInterface
     */
    private $slideRepository;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @var HttpContext
     */
    private $httpContext;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterProvider $filterProvider
     * @param SlideRepositoryInterface $slideRepository
     * @param DateTime $dateTime
     * @param SortOrderBuilder $sortOrderBuilder
     * @param HttpContext $httpContext
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterProvider $filterProvider,
        SlideRepositoryInterface $slideRepository,
        DateTime $dateTime,
        SortOrderBuilder $sortOrderBuilder,
        HttpContext $httpContext
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterProvider = $filterProvider;
        $this->slideRepository = $slideRepository;
        $this->dateTime = $dateTime;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->httpContext = $httpContext;
    }

    /**
     * Retrieve actual slide list for banner
     *
     * @param BannerInterface[] $bannerList
     * @param int $storeId
     * @return SlideInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function prepareActualSlideList($bannerList, $storeId)
    {
        $bannerIds = [];
        foreach ($bannerList as $banner) {
            $bannerIds[] = $banner->getId();
        }
        $this->searchCriteriaBuilder->addFilter('banner_id', $bannerIds, 'in');
        $slideList = $this->slideRepository
            ->getList($this->buildSearchCriteria())
            ->getItems();

        $filter = $this->filterProvider->getBlockFilter();
        $filter->setStoreId($storeId);
        foreach ($slideList as $slide) {
            $slide->setContent($filter->filter($slide->getContent()));
        }

        return $slideList;
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
        $currentDate = $this->dateTime->gmtDate(StdlibDateTime::DATETIME_PHP_FORMAT);
        $segmentIds = (array)$this->httpContext->getValue(self::SEGMENTS_KEY);

        $sortOrder = $this->sortOrderBuilder
            ->setField('position')
            ->setDirection(SortOrder::SORT_ASC)
            ->create();

        $this->searchCriteriaBuilder
            ->addFilter(SlideInterface::STATUS, Status::STATUS_ENABLED)
            ->addFilter('date', $currentDate)
            ->addSortOrder($sortOrder);

        $this->searchCriteriaBuilder->addFilter(SlideInterface::CUSTOMER_SEGMENT_IDS, $segmentIds, 'in');
    }
}
