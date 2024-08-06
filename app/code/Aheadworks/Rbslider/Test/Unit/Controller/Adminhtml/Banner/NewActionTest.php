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

use Aheadworks\Rbslider\Controller\Adminhtml\Banner\NewAction;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Forward;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test for \Aheadworks\Rbslider\Controller\Adminhtml\Banner\NewAction
 */
class NewActionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var NewAction
     */
    private $controller;

    /**
     * @var ForwardFactory|MockObject
     */
    private $forwardFactoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->forwardFactoryMock = $this->getMockBuilder(ForwardFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $contextMock = $objectManager->getObject(
            Context::class,
            []
        );

        $this->controller = $objectManager->getObject(
            NewAction::class,
            [
                'context' => $contextMock,
                'resultForwardFactory' => $this->forwardFactoryMock
            ]
        );
    }

    /**
     * Testing of execute method
     */
    public function testExecute()
    {
        $resultForwardMock = $this->getMockBuilder(Forward::class)
            ->disableOriginalConstructor()
            ->setMethods(['forward'])
            ->getMock();
        $resultForwardMock->expects($this->once())
            ->method('forward')
            ->willReturnSelf();
        $this->forwardFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultForwardMock);

        $this->assertSame($resultForwardMock, $this->controller->execute());
    }
}
