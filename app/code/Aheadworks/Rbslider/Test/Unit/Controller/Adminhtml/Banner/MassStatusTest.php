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

use Aheadworks\Rbslider\Api\BannerRepositoryInterface;
use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Controller\Adminhtml\Banner\MassStatus;
use Aheadworks\Rbslider\Model\Banner;
use Aheadworks\Rbslider\Model\ResourceModel\Banner\Collection;
use Aheadworks\Rbslider\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Ui\Component\MassAction\Filter;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test for \Aheadworks\Rbslider\Controller\Adminhtml\Banner\MassStatus
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class MassStatusTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MassStatus
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
     * @var BannerRepositoryInterface|MockObject
     */
    private $bannerRepositoryMock;

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

        $this->resultRedirectFactoryMock = $this->getMockBuilder(RedirectFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->bannerRepositoryMock = $this->getMockForAbstractClass(BannerRepositoryInterface::class);
        $this->messageManagerMock = $this->getMockForAbstractClass(ManagerInterface::class);
        $this->collectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->filterMock = $this->getMockBuilder(Filter::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCollection'])
            ->getMock();
        $this->requestMock = $this->getMockBuilder(RequestInterface::class)->getMock();
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'request' => $this->requestMock,
                'messageManager' => $this->messageManagerMock,
                'resultRedirectFactory' => $this->resultRedirectFactoryMock
            ]
        );

        $this->controller = $objectManager->getObject(
            MassStatus::class,
            [
                'context' => $contextMock,
                'collectionFactory' => $this->collectionFactoryMock,
                'filter' => $this->filterMock,
                'bannerRepository' => $this->bannerRepositoryMock
            ]
        );
    }

    /**
     * Testing of execute method
     */
    public function testExecute()
    {
        $bannerId = 1;
        $status = 1;
        $count = 1;

        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('status')
            ->willReturn($status);
        $bannerModelMock = $this->getMockBuilder(Banner::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();
        $bannerModelMock->expects($this->once())
            ->method('getId')
            ->willReturn($bannerId);
        $collectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getItems'])
            ->getMock();
        $collectionMock->expects($this->once())
            ->method('getItems')
            ->willReturn([$bannerModelMock]);
        $this->collectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($collectionMock);
        $this->filterMock->expects($this->once())
            ->method('getCollection')
            ->willReturn($collectionMock);

        $bannerMock = $this->getMockForAbstractClass(BannerInterface::class);
        $this->bannerRepositoryMock->expects($this->once())
            ->method('get')
            ->with($bannerId)
            ->willReturn($bannerMock);
        $this->bannerRepositoryMock->expects($this->once())
            ->method('save')
            ->with($bannerMock)
            ->willReturn($bannerMock);

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('A total of %1 record(s) have been updated', $count))
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
