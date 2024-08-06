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

namespace Aheadworks\Rbslider\Test\Unit\Block\Adminhtml\Page\Menu;

use Aheadworks\Rbslider\Block\Adminhtml\Page\Menu\Item;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\UrlInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Rbslider\Block\Adminhtml\Page\Menu\Item
 */
class ItemTest extends TestCase
{
    /**
     * @var Item
     */
    private $item;

    /**
     * @var Http|MockObject
     */
    private $requestMock;

    /**
     * @var UrlInterface|MockObject
     */
    private $urlBuilderMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->requestMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->setMethods(['getControllerName'])
            ->getMock();
        $this->urlBuilderMock = $this->getMockForAbstractClass(UrlInterface::class);
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'request' => $this->requestMock,
                'urlBuilder' => $this->urlBuilderMock
            ]
        );
        $this->item = $objectManager->getObject(
            Item::class,
            ['context' => $contextMock]
        );
    }

    /**
     * Testing of prepareLinkAttributes method for the use getUrl method
     */
    public function testPrepareLinkAttributes()
    {
        $linkAttributes = [
            'class' => 'separator',
        ];
        $path = '*/rule/index';

        $this->item->setLinkAttributes($linkAttributes);
        $this->item->setPath($path);

        $this->urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with($path);

        $class = new \ReflectionClass($this->item);
        $method = $class->getMethod('prepareLinkAttributes');
        $method->setAccessible(true);

        $method->invoke($this->item);
    }

    /**
     * Testing of serializeLinkAttributes method
     */
    public function testSerializeLinkAttributes()
    {
        $linkAttributes = [
            'attr' => 'attr_value',
            'attr_1' => 'attr_value_1',
        ];
        $expected = 'attr="attr_value" attr_1="attr_value_1"';
        $this->item->setLinkAttributes($linkAttributes);

        $this->assertEquals($expected, $this->item->serializeLinkAttributes());
    }

    /**
     * Testing of isCurrent method
     *
     * @param string $controllerName
     * @param string $requestControllerName
     * @param bool $expected
     * @dataProvider isCurrentDataProvider
     */
    public function testIsCurrent($controllerName, $requestControllerName, $expected)
    {
        $this->requestMock->expects($this->once())
            ->method('getControllerName')
            ->willReturn($requestControllerName);
        $this->item->setController($controllerName);

        $class = new \ReflectionClass($this->item);
        $method = $class->getMethod('isCurrent');
        $method->setAccessible(true);

        $this->assertEquals($expected, $method->invoke($this->item));
    }

    /**
     * @return array
     */
    public function isCurrentDataProvider()
    {
        return [
            ['test', 'test', true],
            ['test', 'test_test', false]
        ];
    }
}
