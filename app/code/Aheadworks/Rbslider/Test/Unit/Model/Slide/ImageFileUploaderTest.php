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

namespace Aheadworks\Rbslider\Test\Unit\Model\Slide;

use Aheadworks\Rbslider\Model\Slide\ImageFileInfo;
use Aheadworks\Rbslider\Model\Slide\ImageFileUploader;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\MediaStorage\Model\File\Uploader as FileUploader;
use Magento\MediaStorage\Model\File\UploaderFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Rbslider\Model\Slide\ImageFileUploader
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ImageFileUploaderTest extends TestCase
{
    /**
     * @var ImageFileUploader|MockObject
     */
    private $model;

    /**
     * @var UploaderFactory|MockObject
     */
    private $uploaderFactoryMock;

    /**
     * @var ImageFileInfo|MockObject
     */
    private $imageFileInfoMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->uploaderFactoryMock = $this->getMockBuilder(UploaderFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->imageFileInfoMock = $this->createMock(ImageFileInfo::class);

        $this->model = $objectManager->getObject(
            ImageFileUploader::class,
            [
                'uploaderFactory' => $this->uploaderFactoryMock,
                'imageFileInfo' => $this->imageFileInfoMock
            ]
        );
    }

    /**
     * Testing of saveImageToMediaFolder method
     *
     * @return void
     */
    public function testSaveImageToMediaFolder(): void
    {
        $fileName = '1.png';
        $fileCode = 'image';
        $tmpMediaPath = '/tmp/media';

        $fileInfo = [
            'name' => $fileName,
            'size' => 123,
            'type' => 'image/jpg'
        ];

        $uploaderMock = $this->getMockBuilder(FileUploader::class)
            ->disableOriginalConstructor()
            ->setMethods(['setAllowRenameFiles', 'setFilesDispersion', 'setAllowedExtensions', 'save'])
            ->getMock();
        $uploaderMock->expects($this->once())
            ->method('setAllowRenameFiles')
            ->with(true)
            ->willReturnSelf();
        $uploaderMock->expects($this->once())
            ->method('setFilesDispersion')
            ->with(false)
            ->willReturnSelf();
        $uploaderMock->expects($this->once())
            ->method('setAllowedExtensions')
            ->with(['jpg', 'jpeg', 'gif', 'png'])
            ->willReturnSelf();
        $uploaderMock->expects($this->once())
            ->method('save')
            ->with($tmpMediaPath)
            ->willReturn(['file' => $fileName]);

        $this->uploaderFactoryMock->expects($this->once())
            ->method('create')
            ->with(['fileId' => $fileCode])
            ->willReturn($uploaderMock);

        $this->imageFileInfoMock->expects($this->once())
            ->method('getAbsolutePath')
            ->willReturn($tmpMediaPath);
        $this->imageFileInfoMock->expects($this->once())
            ->method('setFileName')
            ->with($fileName)
            ->willReturnSelf();
        $this->imageFileInfoMock->expects($this->once())
            ->method('getInfo')
            ->willReturn($fileInfo);

        $this->assertEquals($fileInfo, $this->model->saveImageToMediaFolder($fileCode));
    }

    /**
     * Testing of getMediaUrl method
     *
     * @return void
     */
    public function testGetMediaUrl(): void
    {
        $storeUrl = 'https://ecommerce.aheadworks.com/pub/media/';
        $fileName = '1.png';

        $this->imageFileInfoMock->expects($this->once())
            ->method('setFileName')
            ->with($fileName)
            ->willReturnSelf();
        $this->imageFileInfoMock->expects($this->once())
            ->method('getStoreUrl')
            ->willReturn($storeUrl);

        $this->assertEquals($storeUrl, $this->model->getMediaUrl($fileName));
    }
}
