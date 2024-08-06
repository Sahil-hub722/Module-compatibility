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

use Aheadworks\Rbslider\Controller\Adminhtml\Slide\UploadImage;
use Aheadworks\Rbslider\Model\Slide\ImageFileUploader;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Json as ResultJson;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test for \Aheadworks\Rbslider\Controller\Adminhtml\Slide\UploadImage
 */
class UploadImageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var UploadImage
     */
    private $controller;

    /**
     * @var ImageFileUploader|MockObject
     */
    private $imageFileUploaderMock;

    /**
     * @var ResultFactory|MockObject
     */
    private $resultFactoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->resultFactoryMock = $this->getMockBuilder(ResultFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->imageFileUploaderMock = $this->getMockBuilder(ImageFileUploader::class)
            ->disableOriginalConstructor()
            ->setMethods(['saveImageToMediaFolder'])
            ->getMock();
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'resultFactory' => $this->resultFactoryMock
            ]
        );

        $this->controller = $objectManager->getObject(
            UploadImage::class,
            [
                'context' => $contextMock,
                'imageFileUploader' => $this->imageFileUploaderMock
            ]
        );
    }

    /**
     * Testing of execute method
     */
    public function testExecute()
    {
        $result = [
            'size' => 282567,
            'file' => '1.png',
            'url' => 'https://ecommerce.aheadworks.com/pub/media/aw_rbslider/slides/1.png'
        ];

        $this->imageFileUploaderMock->expects($this->once())
            ->method('saveImageToMediaFolder')
            ->with('img_file')
            ->willReturn($result);
        $resultJsonMock = $this->getMockBuilder(ResultJson::class)
            ->disableOriginalConstructor()
            ->setMethods(['setData'])
            ->getMock();
        $resultJsonMock->expects($this->once())
            ->method('setData')
            ->with($result)
            ->willReturnSelf();
        $this->resultFactoryMock->expects($this->once())
            ->method('create')
            ->with(ResultFactory::TYPE_JSON)
            ->willReturn($resultJsonMock);

        $this->assertSame($resultJsonMock, $this->controller->execute());
    }
}
