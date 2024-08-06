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

use Aheadworks\Rbslider\Model\Source\AnimationEffect;

/**
 * Class UikitAnimation
 * @package Aheadworks\Rbslider\Model\Source
 */
class UikitAnimation
{
    /**
     * @var array
     */
    private $animation = [
        AnimationEffect::SLIDE => 'scroll',
        AnimationEffect::FADE_OUT_IN => 'fade',
        AnimationEffect::SCALE => 'scale'
    ];

    /**
     * Retrieve animation effect name by key
     *
     * @param int $key
     * @return string
     */
    public function getAnimationEffectByKey($key)
    {
        return $this->animation[$key];
    }
}
