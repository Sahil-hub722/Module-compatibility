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

namespace Aheadworks\Rbslider\Block\Adminhtml\Page\Menu;

/**
 * Page Menu Item
 *
 * @method string|null getPath()
 * @method string|null getLabel()
 * @method string|null getController()
 * @method array|null getLinkAttributes()
 *
 * @method Item setLinkAttributes(array $linkAttributes)
 *
 * @package Aheadworks\Rbslider\Block\Adminhtml\Page\Menu
 * @codeCoverageIgnore
 */
class Item extends \Magento\Backend\Block\Template
{
    /**
     * @var string
     */
    protected $_template = 'Aheadworks_Rbslider::page/menu/item.phtml';

    /**
     * Prepare html attributes of the link
     *
     * @return void
     */
    protected function prepareLinkAttributes()
    {
        $linkAttributes = is_array($this->getLinkAttributes()) ? $this->getLinkAttributes() : [];
        if (!isset($linkAttributes['href'])) {
            $linkAttributes['href'] = $this->getUrl($this->getPath());
        }
        $classes = [];
        if (isset($linkAttributes['class'])) {
            $classes = explode(' ', (string)$linkAttributes['class']);
        }
        if ($this->isCurrent()) {
            $classes[] = 'current';
        }
        $linkAttributes['class'] = implode(' ', $classes);
        $this->setLinkAttributes($linkAttributes);
    }

    /**
     * Retrieves string presentation of link attributes
     *
     * @return string
     */
    public function serializeLinkAttributes()
    {
        $nameValuePairs = [];
        foreach ($this->getLinkAttributes() as $attrName => $attrValue) {
            $nameValuePairs[] = sprintf('%s="%s"', $attrName, $attrValue);
        }
        return implode(' ', $nameValuePairs);
    }

    /**
     * @inheritdoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->prepareLinkAttributes();
        if ($this->isCurrent()) {
            /** @var \Aheadworks\Rbslider\Block\Adminhtml\Page\Menu $menu */
            $menu = $this->getParentBlock();
            if ($menu) {
                $menu->setTitle($this->getLabel());
            }
        }
    }

    /**
     * Checks whether the item is current
     *
     * @return bool
     */
    private function isCurrent()
    {
        return $this->getController() == $this->getRequest()->getControllerName();
    }
}
