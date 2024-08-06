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
namespace Aheadworks\Rbslider\Test\Unit\Model\Statistic;

use Aheadworks\Rbslider\Model\Statistic\SessionManager;
use Magento\Framework\Session\SessionManager as MagentoSessionManager;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class SessionManagerTest
 * @package Aheadworks\Rbslider\Test\Unit\Model\Statistic
 */
class SessionManagerTest extends TestCase
{
    /**
     * @var SessionManager
     */
    private $model;

    /**
     * @var MagentoSessionManager|MockObject
     */
    private $magentoSessionManagerMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $sessionData = [
            'slide_view_1' => time()+50000,
            'slide_view_2' => time()+50000,
            'slide_view_3' => time(),
            'slide_click_1' => time()+50000,
            'slide_click_3' => time(),
        ];

        $this->magentoSessionManagerMock = $this->getMockBuilder(MagentoSessionManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getData', 'setData'])
            ->getMock();
        $this->magentoSessionManagerMock->expects($this->any())
            ->method('getData')
            ->willReturn($sessionData);

        $this->model = $objectManager->getObject(
            SessionManager::class,
            ['magentoSessionManager' => $this->magentoSessionManagerMock]
        );
    }

    /**
     * Testing of getSlidesAction method
     */
    public function testGetSlidesAction()
    {
        $count = 3;
        $class = new \ReflectionClass($this->model);
        $method = $class->getMethod('getSlidesAction');
        $method->setAccessible(true);

        $this->assertCount($count, $method->invoke($this->model));
    }

    /**
     * Testing of isSetSlideAction method
     *
     * @param string $name
     * @param bool $expected
     * @dataProvider isSetSlideActionDataProvider
     */
    public function testIsSetSlideAction($name, $expected)
    {
        $this->assertEquals($expected, $this->model->isSetSlideAction($name));
    }

    /**
     * Data provider for testIsSetSlideAction method
     *
     * @return array
     */
    public function isSetSlideActionDataProvider()
    {
        return [
            ['slide_view_1', true],
            ['slide_view_2', true],
            ['slide_click_1', true],
            ['slide_view_3', false],
            ['slide_click_2', false],
            ['slide_click_3', false]
        ];
    }

    /**
     * Testing of addSlideAction method
     */
    public function testAddSlideAction()
    {
        $count = 4;
        $name = 'slide_view_5';
        $class = new \ReflectionClass($this->model);
        $method = $class->getMethod('getSlidesAction');
        $method->setAccessible(true);
        $method->invoke($this->model);

        $this->model->addSlideAction($name);
        $this->assertCount($count, $method->invoke($this->model));
    }

    /**
     * Testing of save method
     */
    public function testSave()
    {
        $this->magentoSessionManagerMock->expects($this->once())
            ->method('setData')
            ->willReturnSelf();
        $this->model->save();
    }
}
