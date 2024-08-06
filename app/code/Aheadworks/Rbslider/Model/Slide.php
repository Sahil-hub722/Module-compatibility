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

use Aheadworks\Rbslider\Model\ResourceModel\Slide as ResourceSlide;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use Aheadworks\Rbslider\Api\Data\SlideInterface;
use Aheadworks\Rbslider\Api\Data\SlideExtensionInterface;

/**
 * Class Slide
 * @package Aheadworks\Rbslider\Model
 */
class Slide extends AbstractModel implements SlideInterface, IdentityInterface
{
    /**
     * Slide cache tag
     */
    const CACHE_TAG = 'aw_rbslider_slide';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(ResourceSlide::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return (int) $this->getData(self::ID) ?: null;
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreIds()
    {
        return $this->getData(self::STORE_IDS);
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreIds($storeIds)
    {
        return $this->setData(self::STORE_IDS, $storeIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerGroupIds()
    {
        return $this->getData(self::CUSTOMER_GROUP_IDS);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerGroupIds($customerGroupIds)
    {
        return $this->setData(self::CUSTOMER_GROUP_IDS, $customerGroupIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerSegmentIds()
    {
        return $this->getData(self::CUSTOMER_SEGMENT_IDS);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerSegmentIds($customerSegmentIds)
    {
        return $this->setData(self::CUSTOMER_SEGMENT_IDS, $customerSegmentIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayFrom()
    {
        return $this->getData(self::DISPLAY_FROM);
    }

    /**
     * {@inheritdoc}
     */
    public function setDisplayFrom($displayFrom)
    {
        return $this->setData(self::DISPLAY_FROM, $displayFrom);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayTo()
    {
        return $this->getData(self::DISPLAY_TO);
    }

    /**
     * {@inheritdoc}
     */
    public function setDisplayTo($displayTo)
    {
        return $this->setData(self::DISPLAY_TO, $displayTo);
    }

    /**
     * {@inheritdoc}
     */
    public function getImgType()
    {
        return $this->getData(self::IMG_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setImgType($imgType)
    {
        return $this->setData(self::IMG_TYPE, $imgType);
    }

    /**
     * {@inheritdoc}
     */
    public function getImgFile()
    {
        return $this->getData(self::IMG_FILE);
    }

    /**
     * {@inheritdoc}
     */
    public function setImgFile($imgFile)
    {
        return $this->setData(self::IMG_FILE, $imgFile);
    }

    /**
     * {@inheritdoc}
     */
    public function getMobileImgFile()
    {
        return $this->getData(self::MOBILE_IMG_FILE);
    }

    /**
     * {@inheritdoc}
     */
    public function setMobileImgFile($imgFile)
    {
        return $this->setData(self::MOBILE_IMG_FILE, $imgFile);
    }

    /**
     * {@inheritdoc}
     */
    public function getImgUrl()
    {
        return $this->getData(self::IMG_URL);
    }

    /**
     * {@inheritdoc}
     */
    public function setImgUrl($imgUrl)
    {
        return $this->setData(self::IMG_URL, $imgUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function getMobileImgUrl()
    {
        return $this->getData(self::MOBILE_IMG_URL);
    }

    /**
     * {@inheritdoc}
     */
    public function setMobileImgUrl($imgUrl)
    {
        return $this->setData(self::MOBILE_IMG_URL, $imgUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function getImgTitle()
    {
        return $this->getData(self::IMG_TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setImgTitle($imgTitle)
    {
        return $this->setData(self::IMG_TITLE, $imgTitle);
    }

    /**
     * {@inheritdoc}
     */
    public function getImgAlt()
    {
        return $this->getData(self::IMG_ALT);
    }

    /**
     * {@inheritdoc}
     */
    public function setImgAlt($imgAlt)
    {
        return $this->setData(self::IMG_ALT, $imgAlt);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    /**
     * {@inheritdoc}
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsOpenUrlInNewWindow()
    {
        return $this->getData(self::IS_OPEN_URL_IN_NEW_WINDOW);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsOpenUrlInNewWindow($isOpenUrlInNewWindow)
    {
        return $this->setData(self::IS_OPEN_URL_IN_NEW_WINDOW, $isOpenUrlInNewWindow);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsAddNofollowToUrl()
    {
        return $this->getData(self::IS_ADD_NOFOLLOW_TO_URL);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsAddNofollowToUrl($isAddNofollowToUrl)
    {
        return $this->setData(self::IS_ADD_NOFOLLOW_TO_URL, $isAddNofollowToUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function getBannerIds()
    {
        return $this->getData(self::BANNER_IDS);
    }

    /**
     * {@inheritdoc}
     */
    public function setBannerIds($bannerIds)
    {
        return $this->setData(self::BANNER_IDS, $bannerIds);
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setOverlayPosition($overlayPosition)
    {
        return $this->setData(self::OVERLAY_POSITION, $overlayPosition);
    }

    /**
     * {@inheritdoc}
     */
    public function getOverlayPosition()
    {
        return $this->getData(self::OVERLAY_POSITION);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->getData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(SlideExtensionInterface $extensionAttributes)
    {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        return [
            self::CACHE_TAG . '_' . $this->getId()
        ];
    }
}
