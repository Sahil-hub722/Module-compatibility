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
namespace Aheadworks\Rbslider\Api\Data;

/**
 * Block interface
 * @api
 */
interface BlockInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const BANNER = 'banner';
    const SLIDES = 'slides';
    /**#@-*/

    /**
     * Get banner
     *
     * @return \Aheadworks\Rbslider\Api\Data\BannerInterface|null
     */
    public function getBanner();

    /**
     * Set banner
     *
     * @param \Aheadworks\Rbslider\Api\Data\BannerInterface $banner
     * @return BlockInterface
     */
    public function setBanner($banner);

    /**
     * Get slides
     *
     * @return \Aheadworks\Rbslider\Api\Data\SlideInterface[]|null
     */
    public function getSlides();

    /**
     * Set slides
     *
     * @param \Aheadworks\Rbslider\Api\Data\SlideInterface[] $slides
     * @return BlockInterface
     */
    public function setSlides($slides);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Rbslider\Api\Data\BlockExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Rbslider\Api\Data\BlockExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Aheadworks\Rbslider\Api\Data\BlockExtensionInterface $extensionAttributes);
}
