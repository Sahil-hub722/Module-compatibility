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
namespace Aheadworks\Rbslider\Cron;

use Aheadworks\Rbslider\Model\Cache\CacheProcessor;
use Aheadworks\Rbslider\Model\Slide\SliderResolver;
use Aheadworks\Rbslider\Model\Cache\SlideIdentities;

/**
 * Class RbsCacheProcessor
 * @package Aheadworks\Rbslider\Cron
 */
class RbsCacheProcessor
{
    const RUN_INTERVAL = 1800;

    /**
     * @var CacheProcessor
     */
    private $cacheProcessor;

    /**
     * @var SliderResolver
     */
    private $sliderResolver;

    /**
     * @var SlideIdentities
     */
    private $slideIdentities;

    /**
     * @param SliderResolver $sliderResolver
     * @param CacheProcessor $cacheProcessor
     * @param SlideIdentities $slideIdentities
     */
    public function __construct(
        SliderResolver $sliderResolver,
        CacheProcessor $cacheProcessor,
        SlideIdentities $slideIdentities
    ) {
        $this->cacheProcessor = $cacheProcessor;
        $this->sliderResolver = $sliderResolver;
        $this->slideIdentities = $slideIdentities;
    }

    /**
     * Clear cache for slides
     *
     * @return void
     */
    public function execute()
    {
        $allSlides = $this->sliderResolver->getActiveSlides();
        $slides = $this->sliderResolver->getOnlySlidesForUpdate($allSlides);
        $identities = $this->slideIdentities->getIdentities($slides);
        $this->cacheProcessor->cleanCache($identities);
    }
}
