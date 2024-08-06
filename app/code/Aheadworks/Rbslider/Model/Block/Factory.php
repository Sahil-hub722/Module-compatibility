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
namespace Aheadworks\Rbslider\Model\Block;

use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Api\Data\BlockInterfaceFactory;
use Aheadworks\Rbslider\Api\Data\BlockInterface;
use Aheadworks\Rbslider\Api\Data\SlideInterface;

/**
 * Class Factory
 * @package Aheadworks\Rbslider\Model\Block
 */
class Factory
{
    /**
     * @var BlockInterfaceFactory
     */
    private $blockFactory;

    /**
     * @param BlockInterfaceFactory $blockFactory
     */
    public function __construct(
        BlockInterfaceFactory $blockFactory
    ) {
        $this->blockFactory = $blockFactory;
    }

    /**
     * Create blocks
     *
     * @param BannerInterface[] $bannerList
     * @param SlideInterface[] $slideList
     * @return BlockInterface[]
     */
    public function createBlocks($bannerList, $slideList)
    {
        $blockItems = [];
        foreach ($bannerList as $banner) {
            $slides = $this->prepareSlidesForBanner($banner, $slideList);
            $blockItems[] = $this->createBlock($banner, $slides);
        }

        return $blockItems;
    }

    /**
     * Create block
     *
     * @param BannerInterface $banner
     * @param SlideInterface[] $slides
     * @return BlockInterface
     */
    public function createBlock($banner, $slides)
    {
        /** @var BlockInterface $blockDataModel */
        $blockDataModel = $this->blockFactory->create();
        $blockDataModel->setBanner($banner);
        $blockDataModel->setSlides($slides);

        return $blockDataModel;
    }

    /**
     * Prepare slides for banner
     *
     * @param BannerInterface $banner
     * @param SlideInterface[] $slideList
     * @return SlideInterface[]
     */
    private function prepareSlidesForBanner($banner, $slideList)
    {
        $slides = [];
        foreach ($slideList as $slide) {
            if (in_array($slide->getId(), $banner->getSlideIds())) {
                $slides[] = $slide;
            }
        }

        return $slides;
    }
}
