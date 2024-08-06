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

namespace Aheadworks\Rbslider\Test\Unit\Block;

use Aheadworks\Rbslider\Block\Ajax;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Rbslider\Block\Ajax
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AjaxTest extends TestCase
{
    /**
     * @var Ajax
     */
    private $block;

    /**
     * @var SerializerInterface|MockObject
     */
    private $serializer;

    /**
     * @var UrlInterface|MockObject
     */
    private $urlBuilderMock;

    /**
     * @var RequestInterface|MockObject
     */
    private $requestMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->serializer = $this->getMockForAbstractClass(SerializerInterface::class);
        $this->urlBuilderMock = $this->getMockForAbstractClass(UrlInterface::class);
        $this->requestMock = $this->getMockForAbstractClass(RequestInterface::class);

        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'urlBuilder' => $this->urlBuilderMock,
                'request' => $this->requestMock
            ]
        );

        $this->block = $objectManager->getObject(
            Ajax::class,
            [
                'serializer' => $this->serializer,
                'context' => $contextMock
            ]
        );
    }

    /**
     * Testing of getScriptOptions method
     */
    public function testGetScriptOptions()
    {
        $isSecure = false;
        $url = 'https://ecommerce.aheadworks.com/aw_rbslider/statistic/view/id/1369/';
        $expected = '{"url":"https:\/\/ecommerce.aheadworks.com\/aw_rbslider\/statistic\/view\/id\/1369\/"}';

        $this->requestMock->expects($this->once())
            ->method('isSecure')
            ->willReturn($isSecure);

        $this->urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with(
                'aw_rbslider/statistic/view/',
                [
                    '_current' => true,
                    '_secure' => $isSecure,
                ]
            )->willReturn($url);

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with(['url' => $url])
            ->willReturn($expected);

        $this->assertEquals($expected, $this->block->getScriptOptions());
    }
}
