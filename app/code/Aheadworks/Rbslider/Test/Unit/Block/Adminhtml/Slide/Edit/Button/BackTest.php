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

use Aheadworks\Rbslider\Block\Adminhtml\Slide\Edit\Button\Back;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\UrlInterface;

/**
 * Test for \Aheadworks\Rbslider\Block\Adminhtml\Slide\Edit\Button\Back
 */
class BackTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Back
     */
    private $button;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $backUrl = 'https://ecommerce.aheadworks.com/index.php/admin/aw_rbslider_admin/slide';
        $urlBuilderMock = $this->getMockForAbstractClass(UrlInterface::class);
        $urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with('*/*/')
            ->willReturn($backUrl);
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'urlBuilder' => $urlBuilderMock
            ]
        );
        $this->button = $objectManager->getObject(
            Back::class,
            [
                'context' => $contextMock
            ]
        );
    }

    /**
     * Testing of return value of getButtonData method
     */
    public function testGetButtonData()
    {
        $this->assertTrue(is_array($this->button->getButtonData()));
    }
}
