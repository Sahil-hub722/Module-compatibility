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
namespace Aheadworks\Rbslider\Test\Unit\Model\Source;

use Aheadworks\Rbslider\Model\Source\Status;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test \Aheadworks\Rbslider\Model\Source\Status
 */
class StatusTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Status|MockObject
     */
    private $model;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->model = $objectManager->getObject(
            Status::class,
            []
        );
    }

    /**
     * Testing of toOptionArray method
     */
    public function testToOptionArray()
    {
        $this->assertTrue(is_array($this->model->toOptionArray()));
    }

    /**
     * Testing of getOptionArray method
     */
    public function testGetOptionArray()
    {
        $this->assertTrue(is_array($this->model->getOptionArray()));
    }
}
