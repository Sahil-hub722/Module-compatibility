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

use Aheadworks\Rbslider\Block\Adminhtml\Banner\Edit\Button\SaveAndContinue;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Test for \Aheadworks\Rbslider\Block\Adminhtml\Banner\Edit\Button\SaveAndContinue
 */
class SaveAndContinueTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SaveAndContinue
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
        $this->button = $objectManager->getObject(
            SaveAndContinue::class,
            []
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
