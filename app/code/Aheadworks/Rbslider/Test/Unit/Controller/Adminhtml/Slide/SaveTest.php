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

namespace Aheadworks\Rbslider\Test\Unit\Controller\Adminhtml\Slide;

use Aheadworks\Rbslider\Api\Data\SlideInterface;
use Aheadworks\Rbslider\Api\Data\SlideInterfaceFactory;
use Aheadworks\Rbslider\Api\SlideRepositoryInterface;
use Aheadworks\Rbslider\Controller\Adminhtml\Slide\Save;
use Aheadworks\Rbslider\Model\Source\ImageType;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Rbslider\Controller\Adminhtml\Slide\Save
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SaveTest extends TestCase
{
    /**
     * @var Save
     */
    private $controller;

    /**
     * @var SlideRepositoryInterface|MockObject
     */
    private $slideRepositoryMock;

    /**
     * @var SlideInterfaceFactory|MockObject
     */
    private $slideDataFactoryMock;

    /**
     * @var DataObjectHelper|MockObject
     */
    private $dataObjectHelperMock;

    /**
     * @var DataPersistorInterface|MockObject
     */
    private $dataPersistorMock;

    /**
     * @var StoreManagerInterface|MockObject
     */
    private $storeManagerMock;

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
     * @var DateTime|MockObject
     */
    private $dateTimeMock;

    /**
     * @var array
     */
    private $formData = [
        'id' => 1,
        'img_type' => ImageType::TYPE_FILE,
        'img_file' => [
            0 => [
                'name' => '1.png'
            ]
        ],
        'banner_ids' => [1, 2],
        'store_ids' => [0, 1]
    ];

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
        $this->requestMock = $this->getMockForAbstractClass(
            RequestInterface::class,
            [],
            '',
            false,
            true,
            true,
            ['getPostValue']
        );
        $this->messageManagerMock = $this->getMockForAbstractClass(ManagerInterface::class);
        $this->slideRepositoryMock = $this->getMockForAbstractClass(SlideRepositoryInterface::class);
        $this->slideDataFactoryMock = $this->getMockBuilder(SlideInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->dataObjectHelperMock = $this->getMockBuilder(DataObjectHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(['populateWithArray'])
            ->getMock();
        $this->storeManagerMock = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $this->dataPersistorMock = $this->getMockForAbstractClass(DataPersistorInterface::class);
        $this->dateTimeMock = $this->getMockBuilder(DateTime::class)
            ->disableOriginalConstructor()
            ->setMethods(['gmtDate'])
            ->getMock();
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'request' => $this->requestMock,
                'messageManager' => $this->messageManagerMock,
                'resultRedirectFactory' => $this->resultRedirectFactoryMock
            ]
        );

        $this->controller = $objectManager->getObject(
            Save::class,
            [
                'context' => $contextMock,
                'slideRepository' => $this->slideRepositoryMock,
                'slideDataFactory' => $this->slideDataFactoryMock,
                'dataObjectHelper' => $this->dataObjectHelperMock,
                'dataPersistor' => $this->dataPersistorMock,
                'storeManager' => $this->storeManagerMock,
                'dateTime' => $this->dateTimeMock
            ]
        );
    }

    /**
     * Testing of execute method, redirect if get data from form is empty
     */
    public function testExecuteRedirect()
    {
        $this->requestMock->expects($this->once())
            ->method('getPostValue')
            ->willReturn(null);

        $resultRedirectMock = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->setMethods(['setPath'])
            ->getMock();
        $resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();
        $this->resultRedirectFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultRedirectMock);

        $this->assertSame($resultRedirectMock, $this->controller->execute());
    }

    /**
     * Testing of execute method, redirect if error is occur
     */
    public function testExecuteRedirectError()
    {
        $exception = new \Exception;

        $this->requestMock->expects($this->once())
            ->method('getPostValue')
            ->willReturn($this->formData);
        $slideMock = $this->getMockForAbstractClass(SlideInterface::class);
        $this->slideRepositoryMock->expects($this->once())
            ->method('get')
            ->with($this->formData['id'])
            ->willReturn($slideMock);
        $this->slideRepositoryMock->expects($this->once())
            ->method('save')
            ->with($slideMock)
            ->willThrowException($exception);

        $this->messageManagerMock->expects($this->once())
            ->method('addExceptionMessage')
            ->with($exception);
        $resultRedirectMock = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->setMethods(['setPath'])
            ->getMock();
        $resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/edit')
            ->willReturnSelf();
        $this->resultRedirectFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultRedirectMock);

        $this->assertSame($resultRedirectMock, $this->controller->execute());
    }

    /**
     * Testing of convertDate method
     */
    public function testConvertDate()
    {
        $expected = '2017-03-16 00:00:00';
        $this->dateTimeMock->expects($this->once())
            ->method('gmtDate')
            ->willReturn($expected);

        $class = new \ReflectionClass($this->controller);
        $method = $class->getMethod('convertDate');
        $method->setAccessible(true);

        $this->assertEquals($expected, $method->invoke($this->controller, '2017-03-16 00:00:00'));
    }
}
