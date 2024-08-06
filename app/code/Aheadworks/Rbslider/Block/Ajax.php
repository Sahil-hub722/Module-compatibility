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

namespace Aheadworks\Rbslider\Block;

use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Ajax
 * @package Aheadworks\Rbslider\Block
 */
class Ajax extends Template
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param SerializerInterface $serializer
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        SerializerInterface $serializer,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->serializer = $serializer;
    }

    /**
     * Retrieve script options encoded to json
     *
     * @return string
     */
    public function getScriptOptions(): string
    {
        $urlParams = $this->getUrlParams();
        $params = [
            'url' => $this->getUrl(
                'aw_rbslider/statistic/view/',
                $urlParams
            )
        ];

        return $this->serializer->serialize($params);
    }

    /**
     * Retrieve parameters for script options url
     *
     * @return array
     */
    private function getUrlParams(): array
    {
        return [
            '_current' => true,
            '_secure' => $this->templateContext->getRequest()->isSecure()
        ];
    }
}
