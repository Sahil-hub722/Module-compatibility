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

use Aheadworks\Rbslider\Model\Slide\SlideManager;

/**
 * Class RbsCacheProcessor
 * @package Aheadworks\Rbslider\Cron
 */
class RbsStatusProcessor
{
    /**
     * @var SlideManager
     */
    private $slideManager;

    /**
     * @param SlideManager $slideManager
     */
    public function __construct(
        SlideManager $slideManager
    ) {
        $this->slideManager = $slideManager;
    }

    /**
     * Disable expired sliders
     *
     * @return void
     */
    public function execute()
    {
        $this->slideManager->disabledExpiredSlides();
    }
}
