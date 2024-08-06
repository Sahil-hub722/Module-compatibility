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

use Aheadworks\Rbslider\Api\BlockRepositoryInterface;
use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Api\Data\BlockInterface;
use Aheadworks\Rbslider\Api\Data\SlideInterface;
use Aheadworks\Rbslider\Model\Banner as BannerModel;
use Aheadworks\Rbslider\Model\Slide as SlideModel;
use Aheadworks\Rbslider\Model\Slide\ImageFileUploader;
use Aheadworks\Rbslider\Model\Slide\SliderResolver;
use Aheadworks\Rbslider\Model\Source\ImageType;
use Aheadworks\Rbslider\Model\Source\PageType as PageTypeSource;
use Aheadworks\Rbslider\Model\Source\UikitAnimation;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Banner
 *
 * @method string getBlockPosition()
 * @method string getBlockType()
 * @package Aheadworks\Rbslider\Block
 */
class Banner extends Template implements IdentityInterface
{
    /**
     * Path to template file in theme
     * @var string
     */
    protected $_template = 'Aheadworks_Rbslider::block.phtml';

    /**
     * @var BlockRepositoryInterface
     */
    protected $blocksRepository;

    /**
     * @var HttpContext
     */
    protected $httpContext;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ImageFileUploader
     */
    private $imageFileUploader;

    /**
     * @var UikitAnimation
     */
    private $uikitAnimation;

    /**
     * @var PageTypeSource
     */
    private $pageTypeSource;

    /**
     * @var SliderResolver
     */
    private $sliderResolver;

    /**
     * @param Context $context
     * @param BlockRepositoryInterface $blocksRepository
     * @param SerializerInterface $serializer
     * @param ImageFileUploader $imageFileUploader
     * @param UikitAnimation $uikitAnimation
     * @param HttpContext $httpContext
     * @param PageTypeSource $pageTypeSource
     * @param SliderResolver $sliderResolver
     * @param array $data
     */
    public function __construct(
        Context $context,
        BlockRepositoryInterface $blocksRepository,
        SerializerInterface $serializer,
        ImageFileUploader $imageFileUploader,
        UikitAnimation $uikitAnimation,
        HttpContext $httpContext,
        PageTypeSource $pageTypeSource,
        SliderResolver $sliderResolver,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->blocksRepository = $blocksRepository;
        $this->serializer = $serializer;
        $this->imageFileUploader = $imageFileUploader;
        $this->uikitAnimation = $uikitAnimation;
        $this->httpContext = $httpContext;
        $this->pageTypeSource = $pageTypeSource;
        $this->sliderResolver = $sliderResolver;
    }

    /**
     * Retrieve banners for current block position and type
     *
     * @return BlockInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlocks()
    {
        $blocks = [];
        try {
            $currentStoreId = $this->_storeManager->getStore()->getId();
            $currentCustomerGroupId = $this->httpContext->getValue(CustomerContext::CONTEXT_GROUP);
            $loadedBlocks = $this->blocksRepository
                ->getList($this->getBlockType(), $currentStoreId, $currentCustomerGroupId)
                ->getItems();
            foreach ($loadedBlocks as $loadedBlock) {
                if ($loadedBlock->getBanner()->getPosition() == $this->getBlockPosition()) {
                    $blocks[] = $loadedBlock;
                }
            }
        } catch (NoSuchEntityException $e) {
        }

        return $blocks;
    }

    /**
     * Retrieve slide image url
     *
     * @param SlideInterface $slide
     * @return string
     */
    public function getSlideImgUrl(SlideInterface $slide)
    {
        return $slide->getImgType() == ImageType::TYPE_FILE
            ? $this->imageFileUploader->getMediaUrl($slide->getImgFile())
            : $slide->getImgUrl();
    }

    /**
     * Retrieve slide mobile image url
     *
     * @param SlideInterface $slide
     * @return string
     */
    public function getSlideMobileImgUrl(SlideInterface $slide)
    {
        if (!$slide->getMobileImgFile() && !$slide->getMobileImgUrl()) {
            return $this->getSlideImgUrl($slide);
        }
        return $slide->getImgType() == ImageType::TYPE_FILE
            ? $this->imageFileUploader->getMediaUrl($slide->getMobileImgFile())
            : $slide->getMobileImgUrl();
    }

    /**
     * Retrieve mage init params for specific block
     *
     * @param BlockInterface $block
     * @return array
     */
    public function getBannerMageInitParams($block)
    {
        $banner = $block->getBanner();
        $bannerInitParams = [
            'autoplay' => $banner->getPauseTimeBetweenTransitions(),
            'pauseTimeBetweenTransitions' => $banner->getPauseTimeBetweenTransitions(),
            'slideTransitionSpeed' => $banner->getSlideTransitionSpeed(),
            'isStopAnimationMouseOnBanner' => $banner->getIsStopAnimationMouseOnBanner(),
            'animation' => $this->uikitAnimation->getAnimationEffectByKey($banner->getAnimationEffect()),
            'isRandomOrderImage' => $banner->getIsRandomOrderImage(),
            'bannerSchedule' => $this->getSheduledUpdateDateTimeList($banner),
            'bannerId' => $banner->getId(),
            'cacheCleanUrl' => $this->getCacheCleanUrl()
        ];

        return ['awRbslider' => $bannerInitParams];
    }

    /**
     * Retrieve send click statistics params for widget initialization
     *
     * @param BannerInterface $banner
     * @param SlideInterface $slide
     * @return array
     */
    public function getSendClickStatisticsMageInitParams($banner, $slide)
    {
        return [
            'awRbsliderSendClickStatistics' => [
                'slideId' => $slide->getId(),
                'bannerId' => $banner->getId(),
                'url' => $this->getUrl('aw_rbslider/statistic/click')
            ]
        ];
    }

    /**
     * Encode params to json
     *
     * @param array $params
     * @return string
     */
    public function jsonEncode(array $params): string
    {
        return $this->serializer->serialize($params);
    }

    /**
     * Retrieve timetable slide of banner
     *
     * @param BannerInterface $banner
     * @return array
     */
    private function getSheduledUpdateDateTimeList($banner)
    {
        return $this->sliderResolver->getDateTimeListBySlideIds($banner->getSlideIds());
    }

    /**
     * Retrieve cache clean url
     *
     * @return string
     */
    public function getCacheCleanUrl()
    {
        $urlParams = $this->getUrlParams();
        return $this->getUrl(
            'aw_rbslider/cache/clean/',
            $urlParams
        );
    }

    /**
     * Retrieve parameters for cache clean url
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

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        $identities = [];
        $identityTagsMap = $this->pageTypeSource->getIdentityTagsMapByPageType();
        foreach ($this->getBlocks() as $block) {
            $banner = $block->getBanner();
            $identities = array_merge($identities, $identityTagsMap[$banner->getPageType()]);
            $identities[] = BannerModel::CACHE_TAG . '_' . $banner->getId();
            foreach ($banner->getSlideIds() as $slideId) {
                $identities[] = SlideModel::CACHE_TAG . '_' . $slideId;
            }
        }

        $identities = array_unique($identities);
        return $identities;
    }
}
