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

use Aheadworks\Rbslider\Model\Source\Status;
use Aheadworks\Rbslider\Api\Data\SlideInterface;
use Aheadworks\Rbslider\Api\SlideRepositoryInterface;
use Aheadworks\Rbslider\Model\Slide\SliderResolver;

/**
 * Class SlideManager
 * @package Aheadworks\Rbslider\Model\Slide
 */
class SlideManager
{
    /**
     * @var SlideRepositoryInterface
     */
    private $slideRepository;

    /**
     * @var SliderResolver
     */
    private $sliderResolver;

    /**
     * @param SlideRepositoryInterface $slideRepository
     * @param SliderResolver $sliderResolver
     */
    public function __construct(
        SlideRepositoryInterface $slideRepository,
        SliderResolver $sliderResolver
    ) {
        $this->slideRepository = $slideRepository;
        $this->sliderResolver = $sliderResolver;
    }

    /**
     * Disable slides with expired date
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function disabledExpiredSlides()
    {
        $allSlides = $this->sliderResolver->getActiveSlides();
        $slides = $this->sliderResolver->getSlidesForDisabling($allSlides);
        foreach ($slides as $slide) {
            $slide->setStatus(SlideInterface::STATUS, Status::STATUS_DISABLED);
            $this->slideRepository->save($slide);
        }
    }
}
