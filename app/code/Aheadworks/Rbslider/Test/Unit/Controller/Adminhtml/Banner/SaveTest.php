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
use Aheadworks\Rbslider\Api\Data\BannerInterfaceFactory;
use Aheadworks\Rbslider\Api\Data\ConditionInterface;
use Aheadworks\Rbslider\Controller\Adminhtml\Banner\Save;
use Aheadworks\Rbslider\Model\Converter\Condition as ConditionConverter;
use Aheadworks\Rbslider\Model\Source\PageType;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test for \Aheadworks\Rbslider\Controller\Adminhtml\Banner\Save
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SaveTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Save
     */
    private $controller;

    /**
     * @var BannerRepositoryInterface|MockObject
     */
    private $bannerRepositoryMock;

    /**
     * @var BannerInterfaceFactory|MockObject
     */
    private $bannerDataFactoryMock;

    /**
     * @var DataObjectHelper|MockObject
     */
    private $dataObjectHelperMock;

    /**
     * @var DataPersistorInterface|MockObject
     */
    private $dataPersistorMock;

    /**
     * @var ConditionConverter|MockObject
     */
    private $conditionConverterMock;

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
     * @var array
     */
    private $formData = [
        'id' => 1,
        'page_type' => PageType::PRODUCT_PAGE,
        'rule' => [
            'rbslider' => [
                '1' => [
                    'type' => \Aheadworks\Rbslider\Model\Rule\Condition\Combine::class,
                    'aggregator' => 'all',
                    'value' => '1',
                    'new_child' => ''
                ],
                '1--1' =>[
                    'type' => \Aheadworks\Rbslider\Model\Rule\Condition\Product\Attributes::class,
                    'attribute' => 'category_ids',
                    'operator' => '==',
                    'value' => '23'
                ]
            ]
        ],
        'slide_position' => '{"1":"0","2":"0"}'
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
        $this->bannerRepositoryMock = $this->getMockForAbstractClass(BannerRepositoryInterface::class);
        $this->bannerDataFactoryMock = $this->getMockBuilder(BannerInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->dataObjectHelperMock = $this->getMockBuilder(DataObjectHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(['populateWithArray'])
            ->getMock();
        $this->dataPersistorMock = $this->getMockForAbstractClass(DataPersistorInterface::class);
        $this->conditionConverterMock = $this->getMockBuilder(ConditionConverter::class)
            ->disableOriginalConstructor()
            ->setMethods(['arrayToDataModel'])
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
                'bannerRepository' => $this->bannerRepositoryMock,
                'bannerDataFactory' => $this->bannerDataFactoryMock,
                'dataObjectHelper' => $this->dataObjectHelperMock,
                'dataPersistor' => $this->dataPersistorMock,
                'conditionConverter' => $this->conditionConverterMock
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
        $conditionMock = $this->getMockForAbstractClass(ConditionInterface::class);
        $this->conditionConverterMock->expects($this->once())
            ->method('arrayToDataModel')
            ->willReturn($conditionMock);
        $bannerMock = $this->getMockForAbstractClass(BannerInterface::class);
        $this->bannerRepositoryMock->expects($this->once())
            ->method('get')
            ->with($this->formData['id'])
            ->willReturn($bannerMock);
        $this->bannerRepositoryMock->expects($this->once())
            ->method('save')
            ->with($bannerMock)
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
}
