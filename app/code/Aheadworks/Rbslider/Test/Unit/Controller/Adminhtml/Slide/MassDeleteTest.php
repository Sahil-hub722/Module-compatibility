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
use Aheadworks\Rbslider\Controller\Adminhtml\Slide\MassDelete;
use Aheadworks\Rbslider\Model\ResourceModel\Slide\Collection;
use Aheadworks\Rbslider\Model\ResourceModel\Slide\CollectionFactory;
use Aheadworks\Rbslider\Model\Slide;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Ui\Component\MassAction\Filter;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test for \Aheadworks\Rbslider\Controller\Adminhtml\Slide\MassDelete
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class MassDeleteTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MassDelete
     */
    private $controller;

    /**
     * @var RedirectFactory|MockObject
     */
    private $resultRedirectFactoryMock;

    /**
     * @var ManagerInterface|MockObject
     */
    private $messageManagerMock;

    /**
     * @var CollectionFactory|MockObject
     */
    private $collectionFactoryMock;

    /**
     * @var Filter|MockObject
     */
    private $filterMock;

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
        $this->messageManagerMock = $this->getMockForAbstractClass(ManagerInterface::class);
        $this->collectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->filterMock = $this->getMockBuilder(Filter::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCollection'])
            ->getMock();
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'messageManager' => $this->messageManagerMock,
                'resultRedirectFactory' => $this->resultRedirectFactoryMock
            ]
        );

        $this->controller = $objectManager->getObject(
            MassDelete::class,
            [
                'context' => $contextMock,
                'collectionFactory' => $this->collectionFactoryMock,
                'filter' => $this->filterMock,
                'slideRepository' => $this->slideRepositoryMock
            ]
        );
    }

    /**
     * Testing of execute method
     */
    public function testExecute()
    {
        $slideId = 1;
        $count = 1;

        $slideModelMock = $this->getMockBuilder(Slide::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();
        $slideModelMock->expects($this->once())
            ->method('getId')
            ->willReturn($slideId);
        $collectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getItems'])
            ->getMock();
        $collectionMock->expects($this->once())
            ->method('getItems')
            ->willReturn([$slideModelMock]);
        $this->collectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($collectionMock);
        $this->filterMock->expects($this->once())
            ->method('getCollection')
            ->willReturn($collectionMock);

        $this->slideRepositoryMock->expects($this->once())
            ->method('deleteById')
            ->with($slideId)
            ->willReturn(true);

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('A total of %1 record(s) were deleted', $count))
            ->willReturnSelf();

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

        $this->assertSame($resultRedirectMock, $this->controller->execute());
    }
}
