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
namespace Aheadworks\Rbslider\Test\Unit\Block\Widget;

use Aheadworks\Rbslider\Api\BlockRepositoryInterface;
use Aheadworks\Rbslider\Api\Data\BlockInterface;
use Aheadworks\Rbslider\Block\Widget\Banner;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Rbslider\Block\Widget\Banner
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

        $this->httpContext = $this->createPartialMock(HttpContext::class, ['getValue']);
        $this->storeManager = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'storeManager' => $this->storeManager
            ]
        );

        $this->blocksRepositoryMock = $this->getMockForAbstractClass(BlockRepositoryInterface::class);
        $this->block = $objectManager->getObject(
            Banner::class,
            [
                'context' => $contextMock,
                'blocksRepository' => $this->blocksRepositoryMock,
                'httpContext' => $this->httpContext
            ]
        );
    }

    /**
     * Testing of getBlocks method
     */
    public function testGetBlocks()
    {
        $bannerId = 1;
        $storeId = 1;
        $customerGroupId = 0;
        $this->block->setData('banner_id', $bannerId);
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
        $blockMock = $this->getMockForAbstractClass(BlockInterface::class);
        $this->blocksRepositoryMock->expects($this->once())
            ->method('getByBannerIdForWidget')
            ->with($bannerId, $storeId, $customerGroupId)
            ->willReturn($blockMock);

        $this->assertSame([$blockMock], $this->block->getBlocks());
    }

    /**
     * Testing of getNameInLayout method
     */
    public function testGetNameInLayout()
    {
        $bannerId = 1;
        $expected = Banner::WIDGET_NAME_PREFIX . $bannerId;

        $this->block->setData('banner_id', $bannerId);
        $this->assertEquals($expected, $this->block->getNameInLayout());
    }
}
