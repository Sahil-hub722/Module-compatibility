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
namespace Aheadworks\Rbslider\Test\Unit\Model;

use Aheadworks\Rbslider\Api\Data\ConditionInterface;
use Aheadworks\Rbslider\Model\Banner;
use Aheadworks\Rbslider\Model\Converter\Condition as ConditionConverter;
use Aheadworks\Rbslider\Model\Rule\Product;
use Aheadworks\Rbslider\Model\Rule\ProductFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test for \Aheadworks\Rbslider\Model\Banner
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BannerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Banner
     */
    private $model;

    /**
     * @var ProductFactory|MockObject
     */
    private $productRuleFactoryMock;

    /**
     * @var ConditionConverter|MockObject
     */
    private $conditionConverterMock;

    /**
     * @var array
     */
    private $bannerData = [
        'id' => 1,
        'product_condition' => 'a:5:{s:4:"type";s:48:"Aheadworks\Rbslider\Model\Rule\Condition\Combine";'
            . 's:10:"conditions";a:1:{i:0;a:5:{s:4:"type";'
            . 's:59:"Aheadworks\Rbslider\Model\Rule\Condition\Product\Attributes";s:8:"operator";s:2:"==";'
            . 's:9:"attribute";s:12:"category_ids";s:5:"value";s:14:"20, 21, 23, 24";s:10:"value_type";N;}}'
            . 's:10:"aggregator";s:3:"all";s:5:"value";s:1:"1";s:10:"value_type";N;}',
    ];

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->productRuleFactoryMock = $this->getMockBuilder(ProductFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->conditionConverterMock = $this->getMockBuilder(ConditionConverter::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $this->model = $objectManager->getObject(
            Banner::class,
            [
                'productRuleFactory' => $this->productRuleFactoryMock,
                'conditionConverter' => $this->conditionConverterMock
            ]
        );
    }

    /**
     * Testing of getProductRule method
     */
    public function testGetProductRule()
    {
        $conditionMock = $this->getMockForAbstractClass(ConditionInterface::class);
        $conditionArray = $this->bannerData['product_condition'];
        $this->bannerData['product_condition'] = $conditionMock;
        $this->model->setData($this->bannerData);

        $this->conditionConverterMock->expects($this->once())
            ->method('dataModelToArray')
            ->with($conditionMock)
            ->willReturn($conditionArray);

        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods(['setConditions', 'getConditions', 'loadArray'])
            ->getMock();
        $productMock->expects($this->once())
            ->method('setConditions')
            ->willReturnSelf();
        $productMock->expects($this->once())
            ->method('getConditions')
            ->willReturnSelf();
        $productMock->expects($this->once())
            ->method('loadArray')
            ->with($conditionArray)
            ->willReturnSelf();
        $this->productRuleFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($productMock);

        $this->model->getProductRule();
    }
}
