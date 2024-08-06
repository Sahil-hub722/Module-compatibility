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
namespace Aheadworks\Rbslider\Test\Unit\Block;

use Aheadworks\Rbslider\Api\BlockRepositoryInterface;
use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Api\Data\BlockInterface;
use Aheadworks\Rbslider\Api\Data\BlockSearchResultsInterface;
use Aheadworks\Rbslider\Api\Data\SlideInterface;
use Aheadworks\Rbslider\Block\Banner;
use Aheadworks\Rbslider\Model\Slide\ImageFileUploader;
use Aheadworks\Rbslider\Model\Source\ImageType;
use Aheadworks\Rbslider\Model\Source\UikitAnimation;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Rbslider\Block\Banner
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BannerTest extends TestCase
{
    /**
     * @var Banner
     */
    private $block;

    /**
     * @var BlockRepositoryInterface|MockObject
     */
    private $blocksRepositoryMock;

    /**
     * @var ImageFileUploader|MockObject
     */
    private $imageFileUploaderMock;

    /**
     * @var UikitAnimation|MockObject
     */
    private $uikitAnimationMock;

    /**
     * @var UrlInterface|MockObject
     */
    private $urlBuilderMock;

    /**
     * @var RequestInterface|MockObject
     */
    private $requestMock;

    /**
     * @var HttpContext|MockObject
     */
    private $httpContext;

    /**
     * @var StoreManagerInterface|MockObject
     */
    private $storeManager;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->blocksRepositoryMock = $this->getMockForAbstractClass(BlockRepositoryInterface::class);
        $this->imageFileUploaderMock = $this->getMockBuilder(ImageFileUploader::class)
            ->disableOriginalConstructor()
            ->setMethods(['getMediaUrl'])
            ->getMock();
        $this->uikitAnimationMock = $this->getMockBuilder(UikitAnimation::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAnimationEffectByKey'])
            ->getMock();
        $this->httpContext = $this->createPartialMock(HttpContext::class, ['getValue']);
        $this->urlBuilderMock = $this->getMockForAbstractClass(UrlInterface::class);
        $this->requestMock = $this->getMockForAbstractClass(
            RequestInterface::class,
            [],
            '',
            false,
            true,
            true,
            ['isAjax']
        );
        $this->storeManager = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'urlBuilder' => $this->urlBuilderMock,
                'request' => $this->requestMock,
                'storeManager' => $this->storeManager
            ]
        );

        $this->block = $objectManager->getObject(
            Banner::class,
            [
                'context' => $contextMock,
                'blocksRepository' => $this->blocksRepositoryMock,
                'imageFileUploader' => $this->imageFileUploaderMock,
                'uikitAnimation' => $this->uikitAnimationMock,
                'httpContext' => $this->httpContext
            ]
        );
    }

    /**
     * Testing of getBlocks method
     */
    public function testGetBlocks()
    {
        $storeId = 1;
        $customerGroupId = 0;
        $blockType = 3;
        $position = 2;
        $this->block->setBlockType($blockType);
        $this->block->setBlockPosition($position);
        $storeMock = $this->getMockForAbstractClass(StoreInterface::class);
        $storeMock->expects($this->once())
            ->method('getId')
            ->willReturn($storeId);
        $this->storeManager->expects($this->once())
            ->method('getStore')
            ->willReturn($storeMock);
        $this->httpContext->expects($this->once())
            ->method('getValue')
            ->with(CustomerContext::CONTEXT_GROUP)
            ->willReturn($customerGroupId);
        $this->block->setNameInLayout('banner_category_menu_top');
        $blockMock1 = $this->getMockForAbstractClass(BlockInterface::class);
        $banner1 = $this->getMockForAbstractClass(BannerInterface::class);
        $blockMock1->expects($this->once())
            ->method('getBanner')
            ->willReturn($banner1);
        $banner1->expects($this->once())
            ->method('getPosition')
            ->willReturn(1);
        $blockMock2 = $this->getMockForAbstractClass(BlockInterface::class);
        $banner2 = $this->getMockForAbstractClass(BannerInterface::class);
        $blockMock2->expects($this->once())
            ->method('getBanner')
            ->willReturn($banner2);
        $banner2->expects($this->once())
            ->method('getPosition')
            ->willReturn($position);
        $blockSearchResultsMock = $this->getMockForAbstractClass(BlockSearchResultsInterface::class);
        $blockSearchResultsMock->expects($this->once())
            ->method('getItems')
            ->willReturn([$blockMock1, $blockMock2]);
        $this->blocksRepositoryMock->expects($this->once())
            ->method('getList')
            ->with($blockType, $storeId, $customerGroupId)
            ->willReturn($blockSearchResultsMock);

        $this->assertSame([$blockMock2], $this->block->getBlocks());
    }

    /**
     * Testing of getSlideImgUrl method
     *
     * @param int $imgType
     * @param string $imgFile
     * @param string $imgUrl
     * @param bool $expected
     * @dataProvider getSlideImgUrlDataProvider
     */
    public function testGetSlideImgUrl($imgType, $imgFile, $imgUrl, $expected)
    {
        $impPath = 'https://ecommerce.aheadworks.com/pub/media/aw_rbslider/slides/';
        $slideMock = $this->getMockForAbstractClass(SlideInterface::class);
        $slideMock->expects($this->once())
            ->method('getImgType')
            ->willReturn($imgType);
        $slideMock->expects($this->any())
            ->method('getImgFile')
            ->willReturn($imgFile);
        $slideMock->expects($this->any())
            ->method('getImgUrl')
            ->willReturn($imgUrl);
        $this->imageFileUploaderMock->expects($this->any())
            ->method('getMediaUrl')
            ->with($imgFile)
            ->willReturn($impPath . $imgFile);

        $this->assertEquals($expected, $this->block->getSlideImgUrl($slideMock));
    }

    /**
     * Data provider for testGetSlideImgUrl method
     *
     * @return array
     */
    public function getSlideImgUrlDataProvider()
    {
        return [
            [
                ImageType::TYPE_FILE,
                '1.png',
                '',
                'https://ecommerce.aheadworks.com/pub/media/aw_rbslider/slides/1.png'
            ],
            [
                ImageType::TYPE_URL,
                '',
                'https://ecommerce.aheadworks.com/my_img.png',
                'https://ecommerce.aheadworks.com/my_img.png'
            ]
        ];
    }
}
