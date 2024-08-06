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
namespace Aheadworks\Rbslider\Test\Unit\Block\Adminhtml\Slide\Edit\Button;

use Aheadworks\Rbslider\Api\Data\SlideInterface;
use Aheadworks\Rbslider\Api\SlideRepositoryInterface;
use Aheadworks\Rbslider\Block\Adminhtml\Slide\Edit\Button\Delete;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\UrlInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test for \Aheadworks\Rbslider\Block\Adminhtml\Slide\Edit\Button\Delete
 */
class DeleteTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Delete
     */
    private $button;

    /**
     * @var SlideRepositoryInterface|MockObject
     */
    private $slideRepositoryMock;

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
        $this->slideRepositoryMock = $this->getMockForAbstractClass(SlideRepositoryInterface::class);
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
                'slideRepository' => $this->slideRepositoryMock
            ]
        );
    }

    /**
     * Testing of return value of getButtonData method
     */
    public function testGetButtonData()
    {
        $slideId = 1;
        $deleteUrl = 'https://ecommerce.aheadworks.com/index.php/admin/aw_rbslider_admin/slide/delete/id/' . $slideId;

        $this->urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with(
                $this->equalTo('*/*/delete'),
                $this->equalTo(['id' => $slideId])
            )->willReturn($deleteUrl);

        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('id')
            ->willReturn($slideId);

        $slideMock = $this->getMockForAbstractClass(SlideInterface::class);
        $slideMock->expects($this->once())
            ->method('getId')
            ->willReturn($slideId);
        $this->slideRepositoryMock->expects($this->exactly(2))
            ->method('get')
            ->with($slideId)
            ->willReturn($slideMock);

        $this->assertTrue(is_array($this->button->getButtonData()));
    }
}
