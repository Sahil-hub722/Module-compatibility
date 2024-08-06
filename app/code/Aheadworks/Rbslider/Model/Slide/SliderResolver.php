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
namespace Aheadworks\Rbslider\Model\Slide;

use Aheadworks\Rbslider\Api\Data\SlideInterface;
use Aheadworks\Rbslider\Api\SlideRepositoryInterface;
use Aheadworks\Rbslider\Model\Source\Status;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Aheadworks\Rbslider\Model\Date\DateComparer;

/**
 * Class SliderResolver
 * @package Aheadworks\Rbslider\Model\Slide
 */
class SliderResolver
{
    /**
     * @var SlideRepositoryInterface
     */
    private $slideRepository;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var DateComparer
     */
    private $dateComparer;

    /**
     * @param SlideRepositoryInterface $slideRepository
     * @param DateTime $dateTime
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param DateComparer $dateComparer
     */
    public function __construct(
        SlideRepositoryInterface $slideRepository,
        DateTime $dateTime,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DateComparer $dateComparer
    ) {
        $this->slideRepository = $slideRepository;
        $this->dateTime = $dateTime;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->dateComparer = $dateComparer;
    }

    /**
     * Retrieve list of datetime for banner by assigned slide Ids
     *
     * @param array $slideIds
     * @return array
     */
    public function getDateTimeListBySlideIds($slideIds)
    {
        $schedule = [];
        foreach ($this->getActiveSlides($slideIds) as $slide) {
            if (!empty($slide->getDisplayFrom())) {
                $schedule[] = $slide->getDisplayFrom();
            }
            if (!empty($slide->getDisplayTo())) {
                $schedule[] = $slide->getDisplayTo();
            }
        }

        return $this->dateComparer->sortDate($schedule);
    }

    /**
     * Retrieve all slides for current date
     *
     * @param array $slideIds
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getActiveSlides($slideIds = [])
    {
        $this->searchCriteriaBuilder
            ->addFilter(SlideInterface::STATUS, Status::STATUS_ENABLED);

        if (!empty($slideIds)) {
            $this->searchCriteriaBuilder
                ->addFilter(SlideInterface::ID, $slideIds, 'in');
        }

        $slideList = $this->slideRepository
            ->getList($this->searchCriteriaBuilder->create())
            ->getItems();

        return $slideList;
    }

    /**
     * Retrieve only slides which will update
     *
     * @param array $slideList
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getOnlySlidesForUpdate($slideList)
    {
        $now = $this->dateTime->timestamp();
        $updatedSlides = [];

        foreach ($slideList as $slide) {
            if ($this->dateComparer->checkDisplayDate($now, $slide->getDisplayFrom())) {
                $updatedSlides[] = $slide;
            }
            if ($this->dateComparer->checkDisplayDate($now, $slide->getDisplayTo())) {
                $updatedSlides[] = $slide;
            }
        }

        return $updatedSlides;
    }

    /**
     * Retrieve only slides which will disable
     *
     * @param array $slideIds
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSlidesForDisabling($slideList)
    {
        $now = $this->dateTime->timestamp();
        $disableSlides = [];

        foreach ($slideList as $slide) {
            if ($this->dateComparer->checkDisplayToDate($now, $slide->getDisplayTo())) {
                $disableSlides[] = $slide;
            }
        }

        return $disableSlides;
    }
}
