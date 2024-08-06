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
use Aheadworks\Rbslider\Controller\Statistic\Click;
use Aheadworks\Rbslider\Model\Statistic\SessionManager as StatisticSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Controller\Result\Json as ResultJson;
use Magento\Framework\Controller\Result\Redirect as ResultRedirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Rbslider\Controller\Statistic\Click
 */
class ClickTest extends TestCase
{
    /**
     * @var Click
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
        $this->actionLogManagementMock = $this->getMockForAbstractClass(ActionLogManagementInterface::class);

        $this->controller = $objectManager->getObject(
            Click::class,
            [
                'resultFactory' => $this->resultFactoryMock,
                'customerSession' => $this->customerSessionMock,
                'statisticSession' => $this->statisticSessionMock,
                'request' => $this->requestMock,
                'actionLogManagement' => $this->actionLogManagementMock
            ]
        );
    }

    /**
     * Testing of execute method, without ajax
     *
     * @return void
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
     */
    public function testExecuteWithPassedData(): void
    {
        $slideId = 4;
        $bannerId = 2;
        $customerId = 1;
        $isAjax = true;

        $this->requestMock->expects($this->once())
            ->method('isAjax')
            ->willReturn($isAjax);

        $this->requestMock->expects($this->exactly(2))
            ->method('getParam')
            ->withConsecutive(['banner_id'], ['slide_id'])
            ->willReturnOnConsecutiveCalls($bannerId, $slideId);

        $this->statisticSessionMock->expects($this->once())
            ->method('isUniqueAction')
            ->with($bannerId, $slideId, 'click')
            ->willReturn(true);

        $this->customerSessionMock->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);

        $this->actionLogManagementMock->expects($this->once())
            ->method('addClick')
            ->with($bannerId, $slideId, $customerId);

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
