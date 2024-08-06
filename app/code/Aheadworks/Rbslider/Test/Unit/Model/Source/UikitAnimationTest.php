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
namespace Aheadworks\Rbslider\Test\Unit\Model\Source;

use Aheadworks\Rbslider\Model\Source\AnimationEffect;
use Aheadworks\Rbslider\Model\Source\UikitAnimation;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test \Aheadworks\Rbslider\Model\Source\UikitAnimation
 */
class UikitAnimationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var UikitAnimation|MockObject
     */
    private $model;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->model = $objectManager->getObject(
            UikitAnimation::class,
            []
        );
    }

    /**
     * Testing of getAnimationEffectByKey method
     *
     * @param int $key
     * @param string $expected
     * @dataProvider getAnimationEffectByKeyDataProvider
     */
    public function testGetAnimationEffectByKey($key, $expected)
    {
        $this->assertEquals($expected, $this->model->getAnimationEffectByKey($key));
    }

    /**
     * Data provider for testGetAnimationEffectByKey method
     *
     * @return array
     */
    public function getAnimationEffectByKeyDataProvider()
    {
        return [
            [AnimationEffect::SLIDE, 'scroll'],
            [AnimationEffect::FADE_OUT_IN, 'fade'],
            [AnimationEffect::SCALE, 'scale']
        ];
    }
}
