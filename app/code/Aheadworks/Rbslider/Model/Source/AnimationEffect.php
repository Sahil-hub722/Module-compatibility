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

/**
 * Class AnimationEffect
 * @package Aheadworks\Rbslider\Model\Source
 */
class AnimationEffect implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Animation effect values
     */
    const SLIDE = 0;
    const FADE_OUT_IN = 1;
    const SCALE = 2;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::SLIDE,  'label' => __('Slide')],
            ['value' => self::FADE_OUT_IN,  'label' => __('Fade Out / In')],
            ['value' => self::SCALE,  'label' => __('Scale')],
        ];
    }
}
