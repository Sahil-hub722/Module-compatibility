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
namespace Aheadworks\Rbslider\Test\Unit\Block\Adminhtml\Banner\Edit\Button;

use Aheadworks\Rbslider\Api\BannerRepositoryInterface;
use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Block\Adminhtml\Banner\Edit\Button\Delete;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\UrlInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test for \Aheadworks\Rbslider\Block\Adminhtml\Banner\Edit\Button\Delete
 */
class DeleteTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Delete
     */
    private $button;

    /**
     * @var BannerRepositoryInterface|MockObject
     */
    private $bannerRepositoryMock;

    /**
     * @var UrlInterface|MockObject
     */
    private $urlBuilderMock;

    /**
     * @var Http|MockObject
     */
    private $requestMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->urlBuilderMock = $this->getMockForAbstractClass(UrlInterface::class);
        $this->requestMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->setMethods(['getParam'])
            ->getMock();
        $this->bannerRepositoryMock = $this->getMockForAbstractClass(BannerRepositoryInterface::class);
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'urlBuilder' => $this->urlBuilderMock,
                'request' => $this->requestMock
            ]
        );
        $this->button = $objectManager->getObject(
            Delete::class,
            [
                'context' => $contextMock,
                'bannerRepository' => $this->bannerRepositoryMock
            ]
        );
    }

    /**
     * Testing of return value of getButtonData method
     */
    public function testGetButtonData()
    {
        $bannerId = 1;
        $deleteUrl = 'https://ecommerce.aheadworks.com/index.php/admin/aw_rbslider_admin/banner/delete/id/' . $bannerId;

        $this->urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with(
                $this->equalTo('*/*/delete'),
                $this->equalTo(['id' => $bannerId])
            )->willReturn($deleteUrl);

        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('id')
            ->willReturn($bannerId);

        $bannerMock = $this->getMockForAbstractClass(BannerInterface::class);
        $bannerMock->expects($this->once())
            ->method('getId')
            ->willReturn($bannerId);
        $this->bannerRepositoryMock->expects($this->exactly(2))
            ->method('get')
            ->with($bannerId)
            ->willReturn($bannerMock);

        $this->assertTrue(is_array($this->button->getButtonData()));
    }
}
