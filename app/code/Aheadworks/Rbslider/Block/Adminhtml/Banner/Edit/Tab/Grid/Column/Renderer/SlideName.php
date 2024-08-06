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
namespace Aheadworks\Rbslider\Block\Adminhtml\Banner\Edit\Tab\Grid\Column\Renderer;

/**
 * Class SlideName
 * @package Aheadworks\Rbslider\Block\Adminhtml\Banner\Edit\Tab\Grid\Column\Renderer
 */
class SlideName extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{
    /**
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $url = $this->getUrl(
            'aw_rbslider_admin/slide/edit',
            ['id' => $row->getId()]
        );
        return '<a href="' . $url . '" target="_blank">' . $row->getName() . '</a>';
    }
}
