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
namespace Aheadworks\Rbslider\Test\Unit\Controller\Adminhtml\Banner;

use Aheadworks\Rbslider\Block\Adminhtml\Banner\Edit\Tab\Grid\Slide;
use Aheadworks\Rbslider\Controller\Adminhtml\Banner\Grid;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Layout;
use Magento\Framework\View\LayoutFactory;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test for \Aheadworks\Rbslider\Controller\Adminhtml\Banner\Grid
 */
class GridTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Grid
     */
    private $controller;

    /**
     * @var LayoutFactory|MockObject
     */
    private $layoutFactoryMock;

    /**
     * @var RawFactory|MockObject
     */
    private $resultRawFactoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->layoutFactoryMock = $this->getMockBuilder(LayoutFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->resultRawFactoryMock = $this->getMockBuilder(RawFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $contextMock = $objectManager->getObject(
            Context::class,
            []
        );

        $this->controller = $objectManager->getObject(
            Grid::class,
            [
                'context' => $contextMock,
                'layoutFactory' => $this->layoutFactoryMock,
                'resultRawFactory' => $this->resultRawFactoryMock
            ]
        );
    }

    /**
     * Testing of execute method
     */
    public function testExecute()
    {
        $slideGridHtml = 'html content';

        $slideMock = $this->getMockBuilder(Slide::class)
            ->disableOriginalConstructor()
            ->setMethods(['toHtml'])
            ->getMock();
        $slideMock->expects($this->once())
            ->method('toHtml')
            ->willReturn($slideGridHtml);
        $layoutMock = $this->getMockBuilder(Layout::class)
            ->disableOriginalConstructor()
            ->setMethods(['createBlock'])
            ->getMock();
        $layoutMock->expects($this->once())
            ->method('createBlock')
            ->with(Slide::class, 'slide.banner.grid')
            ->willReturn($slideMock);
        $this->layoutFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($layoutMock);

        $resultRawMock = $this->getMockBuilder(Raw::class)
            ->disableOriginalConstructor()
            ->setMethods(['setContents'])
            ->getMock();
        $resultRawMock->expects($this->any())
            ->method('setContents')
            ->with($slideGridHtml)
            ->willReturnSelf();
        $this->resultRawFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultRawMock);

        $this->assertSame($resultRawMock, $this->controller->execute());
    }
}
