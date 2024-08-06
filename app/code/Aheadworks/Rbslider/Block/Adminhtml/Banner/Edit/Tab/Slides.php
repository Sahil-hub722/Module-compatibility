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
namespace Aheadworks\Rbslider\Block\Adminhtml\Banner\Edit\Tab;

use Aheadworks\Rbslider\Block\Adminhtml\Banner\Edit\Tab\Grid\Slide;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Slides
 *
 * @package Aheadworks\Rbslider\Block\Adminhtml\Banner\Edit\Tab
 */
class Slides extends \Magento\Backend\Block\Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'banner/edit/slides.phtml';

    /**
     * @var Slide
     */
    private $blockGrid;

    /**
     * Retrieve instance of grid block
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    public function getBlockGrid()
    {
        if (!$this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                Slide::class,
                'slide.banner.grid'
            );
        }
        return $this->blockGrid;
    }
}
