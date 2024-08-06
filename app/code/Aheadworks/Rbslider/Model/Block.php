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
namespace Aheadworks\Rbslider\Model;

use Aheadworks\Rbslider\Api\Data\BlockInterface;
use Magento\Framework\Api\AbstractExtensibleObject;
use Aheadworks\Rbslider\Api\Data\BlockExtensionInterface;

/**
 * Class Block
 *
 * @package Aheadworks\Rbslider\Model
 */
class Block extends AbstractExtensibleObject implements BlockInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBanner()
    {
        return $this->_get(self::BANNER);
    }

    /**
     * {@inheritdoc}
     */
    public function setBanner($banner)
    {
        return $this->setData(self::BANNER, $banner);
    }

    /**
     * {@inheritdoc}
     */
    public function getSlides()
    {
        return $this->_get(self::SLIDES);
    }

    /**
     * {@inheritdoc}
     */
    public function setSlides($slides)
    {
        return $this->setData(self::SLIDES, $slides);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(BlockExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
