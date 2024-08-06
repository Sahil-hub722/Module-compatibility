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
namespace Aheadworks\Rbslider\Test\Unit\Controller\Adminhtml\Slide;

use Aheadworks\Rbslider\Api\SlideRepositoryInterface;
use Aheadworks\Rbslider\Controller\Adminhtml\Slide\Delete;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test for \Aheadworks\Rbslider\Controller\Adminhtml\Slide\Delete
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DeleteTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Delete
     */
    private $controller;

    /**
     * @var RedirectFactory|MockObject
     */
    private $resultRedirectFactoryMock;

    /**
     * @var RequestInterface|MockObject
     */
    private $requestMock;

    /**
     * @var ManagerInterface|MockObject
     */
    private $messageManagerMock;

    /**
     * @var SlideRepositoryInterface|MockObject
     */
    private $slideRepositoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->resultRedirectFactoryMock = $this->getMockBuilder(RedirectFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->slideRepositoryMock = $this->getMockForAbstractClass(SlideRepositoryInterface::class);
        $this->requestMock = $this->getMockBuilder(RequestInterface::class)->getMock();
        $this->messageManagerMock = $this->getMockForAbstractClass(ManagerInterface::class);
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'request' => $this->requestMock,
                'messageManager' => $this->messageManagerMock,
                'resultRedirectFactory' => $this->resultRedirectFactoryMock
            ]
        );

        $this->controller = $objectManager->getObject(
            Delete::class,
            [
                'context' => $contextMock,
                'slideRepository' => $this->slideRepositoryMock
            ]
        );
    }

    /**
     * Testing of execute method
     */
    public function testExecuteChangeStatusWithoutAjax()
    {
        $slideId = 1;

        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('id')
            ->willReturn($slideId);

        $resultRedirectMock = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->setMethods(['setPath'])
            ->getMock();
        $resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/index')
            ->willReturnSelf();
        $this->resultRedirectFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultRedirectMock);

         $this->slideRepositoryMock->expects($this->once())
            ->method('deleteById')
            ->with($slideId);

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('Slide was successfully deleted'));

        $this->assertSame($resultRedirectMock, $this->controller->execute());
    }
}
