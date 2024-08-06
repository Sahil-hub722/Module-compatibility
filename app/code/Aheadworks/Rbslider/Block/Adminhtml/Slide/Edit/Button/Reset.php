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
namespace Aheadworks\Rbslider\Block\Adminhtml\Slide\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;

/**
 * Class Reset
 * @package Aheadworks\Rbslider\Block\Adminhtml\Slide\Edit\Button
 */
class Reset implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [
            'label' => __('Reset'),
            'class' => 'reset',
            'on_click' => sprintf("location.href = '%s';", $this->getUrl('*/*/*', ['_current' => true])),
            'sort_order' => 30,
            'id' => ''
        ];
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
