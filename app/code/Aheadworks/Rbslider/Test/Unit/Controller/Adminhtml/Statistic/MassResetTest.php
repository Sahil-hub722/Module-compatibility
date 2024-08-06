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

namespace Aheadworks\Rbslider\Test\Unit\Controller\Adminhtml\Statistic;

use Aheadworks\Rbslider\Controller\Adminhtml\Statistic\MassReset;
use Aheadworks\Rbslider\Model\ResourceModel\ActionLog\Collection as ActionLogCollection;
use Aheadworks\Rbslider\Model\ResourceModel\ActionLog\CollectionFactory as ActionLogCollectionFactory;
use ArrayIterator;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect as ResultRedirect;
use Magento\Backend\Model\View\Result\RedirectFactory as ResultRedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Ui\Component\MassAction\Filter as MassActionFilter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Rbslider\Controller\Adminhtml\Statistic\MassReset
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class MassResetTest extends TestCase
{
    /**
     * @var MassReset
     */
    private $controller;

    /**
     * @var ManagerInterface|MockObject
     */
    private $messageManagerMock;

    /**
     * @var ResultRedirectFactory|MockObject
     */
    private $resultRedirectFactoryMock;

    /**
     * @var MassActionFilter|MockObject
     */
    private $massActionFilterMock;

    /**
     * @var ActionLogCollectionFactory|MockObject
     */
    private $actionLogCollectionFactoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->messageManagerMock = $this->getMockForAbstractClass(ManagerInterface::class);
        $this->resultRedirectFactoryMock = $this->createMock(ResultRedirectFactory::class);
        $this->massActionFilterMock = $this->createMock(MassActionFilter::class);
        $this->actionLogCollectionFactoryMock = $this->createMock(ActionLogCollectionFactory::class);

        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'messageManager' => $this->messageManagerMock,
                'resultRedirectFactory' => $this->resultRedirectFactoryMock
            ]
        );

        $this->controller = $objectManager->getObject(
            MassReset::class,
            [
                'context' => $contextMock,
                'massActionFilter' => $this->massActionFilterMock,
                'actionLogCollectionFactory' => $this->actionLogCollectionFactoryMock
            ]
        );
    }

    /**
     * Testing of execute method
     *
     * @return void
     */
    public function testExecute(): void
    {
        $actionLogCollection = $this->createMock(ActionLogCollection::class);

        $this->actionLogCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($actionLogCollection);
        $this->massActionFilterMock->expects($this->once())
            ->method('getCollection')
            ->willReturn($actionLogCollection);

        $actionLogCollection->expects($this->once())
            ->method('getIterator')
            ->willReturn(new ArrayIterator([]));

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->willReturnSelf();

        $resultRedirectMock = $this->createMock(ResultRedirect::class);
        $resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->willReturnSelf();
        $this->resultRedirectFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultRedirectMock);

        $this->assertSame($resultRedirectMock, $this->controller->execute());
    }
}
