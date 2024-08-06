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
namespace Aheadworks\Rbslider\Test\Unit\Controller\Adminhtml\Banner\Widget;

use Aheadworks\Rbslider\Block\Adminhtml\Banner\Widget\Chooser as BlockWidgetChooser;
use Aheadworks\Rbslider\Controller\Adminhtml\Banner\Widget\Chooser;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Layout;
use Magento\Framework\View\LayoutFactory;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test for \Aheadworks\Rbslider\Controller\Adminhtml\Banner\Widget\Chooser
 */
class ChooserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Chooser
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
     * @var RequestInterface|MockObject
     */
    private $requestMock;

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
        $this->requestMock = $this->getMockForAbstractClass(RequestInterface::class);
        $contextMock = $objectManager->getObject(
            Context::class,
            ['request' => $this->requestMock]
        );

        $this->controller = $objectManager->getObject(
            Chooser::class,
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
        $blockHtml = 'html content';
        $uniqId = 1;

        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('uniq_id')
            ->willReturn($uniqId);
        $blockWidgetChooserMock = $this->getMockBuilder(BlockWidgetChooser::class)
            ->disableOriginalConstructor()
            ->setMethods(['toHtml'])
            ->getMock();
        $blockWidgetChooserMock->expects($this->once())
            ->method('toHtml')
            ->willReturn($blockHtml);
        $layoutMock = $this->getMockBuilder(Layout::class)
            ->disableOriginalConstructor()
            ->setMethods(['createBlock'])
            ->getMock();
        $layoutMock->expects($this->once())
            ->method('createBlock')
            ->with(BlockWidgetChooser::class, '', ['data' => ['id' => $uniqId]])
            ->willReturn($blockWidgetChooserMock);
        $this->layoutFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($layoutMock);

        $resultRawMock = $this->getMockBuilder(Raw::class)
            ->disableOriginalConstructor()
            ->setMethods(['setContents'])
            ->getMock();
        $resultRawMock->expects($this->any())
            ->method('setContents')
            ->with($blockHtml)
            ->willReturnSelf();
        $this->resultRawFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultRawMock);

        $this->assertSame($resultRawMock, $this->controller->execute());
    }
}
