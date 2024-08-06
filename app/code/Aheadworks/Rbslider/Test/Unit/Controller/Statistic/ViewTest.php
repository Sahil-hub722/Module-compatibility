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
declare(strict_types=1);

namespace Aheadworks\Rbslider\Test\Unit\Controller\Statistic;

use Aheadworks\Rbslider\Api\ActionLogManagementInterface;
use Aheadworks\Rbslider\Api\BlockRepositoryInterface;
use Aheadworks\Rbslider\Api\Data\BlockInterface;
use Aheadworks\Rbslider\Api\Data\SlideInterface;
use Aheadworks\Rbslider\Controller\Statistic\View;
use Aheadworks\Rbslider\Model\Statistic\SessionManager as StatisticSession;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Controller\Result\Json as ResultJson;
use Magento\Framework\Controller\Result\Redirect as ResultRedirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Rbslider\Controller\Statistic\View
 */
class ViewTest extends TestCase
{
    /**
     * @var View
     */
    private $controller;

    /**
     * @var ResultFactory|MockObject
     */
    private $resultFactoryMock;

    /**
     * @var CustomerSession|MockObject
     */
    private $customerSessionMock;

    /**
     * @var StatisticSession|MockObject
     */
    private $statisticSessionMock;

    /**
     * @var HttpRequest|MockObject
     */
    private $requestMock;

    /**
     * @var StoreManagerInterface|MockObject
     */
    private $storeManagerMock;

    /**
     * @var BlockRepositoryInterface|MockObject
     */
    private $blockRepositoryMock;

    /**
     * @var ActionLogManagementInterface|MockObject
     */
    private $actionLogManagementMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->resultFactoryMock = $this->createMock(ResultFactory::class);
        $this->customerSessionMock = $this->createMock(CustomerSession::class);
        $this->statisticSessionMock = $this->createMock(StatisticSession::class);
        $this->requestMock = $this->createMock(HttpRequest::class);
        $this->storeManagerMock = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $this->blockRepositoryMock = $this->getMockForAbstractClass(BlockRepositoryInterface::class);
        $this->actionLogManagementMock = $this->getMockForAbstractClass(ActionLogManagementInterface::class);

        $this->controller = $objectManager->getObject(
            View::class,
            [
                'resultFactory' => $this->resultFactoryMock,
                'customerSession' => $this->customerSessionMock,
                'statisticSession' => $this->statisticSessionMock,
                'request' => $this->requestMock,
                'storeManager' => $this->storeManagerMock,
                'blockRepository' => $this->blockRepositoryMock,
                'actionLogManagement' => $this->actionLogManagementMock
            ]
        );
    }

    /**
     * Testing of execute method, without ajax
     *
     * @return void
     * @throws LocalizedException
     */
    public function testExecuteWithoutAjax(): void
    {
        $this->requestMock->expects($this->once())
            ->method('isAjax')
            ->willReturn(false);

        $resultRedirectMock = $this->createMock(ResultRedirect::class);
        $resultRedirectMock->expects($this->once())
            ->method('setRefererOrBaseUrl')
            ->willReturnSelf();

        $this->resultFactoryMock->expects($this->once())
            ->method('create')
            ->with(ResultFactory::TYPE_REDIRECT)
            ->willReturn($resultRedirectMock);

        $this->assertSame($resultRedirectMock, $this->controller->execute());
    }

    /**
     * Testing of execute method, with passed data
     *
     * @return void
     * @throws LocalizedException
     */
    public function testExecuteWithPassedData(): void
    {
        $bannerIdsJson = '[1,2]';
        $bannerId1 = 1;
        $bannerId2 = 2;
        $storeId = 1;
        $customerId = 1;
        $customerGroupId = 1;
        $slideId = 26;

        $this->requestMock->expects($this->once())
            ->method('isAjax')
            ->willReturn(true);
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('bannerIds')
            ->willReturn($bannerIdsJson);

        $storeMock = $this->getMockForAbstractClass(StoreInterface::class);
        $storeMock->expects($this->once())
            ->method('getId')
            ->willReturn($storeId);
        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($storeMock);

        $customerMock = $this->createMock(Customer::class);
        $customerMock->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);
        $customerMock->expects($this->exactly(2))
            ->method('getGroupId')
            ->willReturn($customerGroupId);
        $this->customerSessionMock->expects($this->once())
            ->method('getCustomer')
            ->willReturn($customerMock);

        $block1 = $this->getMockForAbstractClass(BlockInterface::class);
        $block2 = $this->getMockForAbstractClass(BlockInterface::class);

        $this->blockRepositoryMock->expects($this->exactly(2))
            ->method('getByBannerId')
            ->withConsecutive([$bannerId1, $storeId, $customerGroupId], [$bannerId2, $storeId, $customerGroupId])
            ->willReturnOnConsecutiveCalls($block1, $block2);
        $slide1 = $this->getMockForAbstractClass(SlideInterface::class);
        $slides = [$slide1];
        $block1->expects($this->once())
            ->method('getSlides')
            ->willReturn($slides);
        $block2->expects($this->once())
            ->method('getSlides')
            ->willReturn($slides);
        $slide1->expects($this->any())
            ->method('getId')
            ->willReturn($slideId);

        $this->statisticSessionMock->expects($this->exactly(2))
            ->method('isUniqueAction')
            ->withConsecutive([$bannerId1, $slideId, 'view'], [$bannerId2, $slideId, 'view'])
            ->willReturn(true);
        $this->actionLogManagementMock->expects($this->exactly(2))
            ->method('addView')
            ->withConsecutive([$bannerId1, $slideId, $customerId], [$bannerId2, $slideId, $customerId]);

        $resultJsonMock = $this->createMock(ResultJson::class);
        $resultJsonMock->expects($this->once())
            ->method('setData')
            ->willReturnSelf();
        $this->resultFactoryMock->expects($this->once())
            ->method('create')
            ->with(ResultFactory::TYPE_JSON)
            ->willReturn($resultJsonMock);

        $this->assertSame($resultJsonMock, $this->controller->execute());
    }
}
