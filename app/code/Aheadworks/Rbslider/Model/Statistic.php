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

use Aheadworks\Rbslider\Model\ResourceModel\Statistic as ResourceStatistic;
use Aheadworks\Rbslider\Api\Data\StatisticInterface;
use Aheadworks\Rbslider\Api\Data\StatisticExtensionInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Statistic
 * @package Aheadworks\Rbslider\Model
 *
 * @deprecated Not being used since visitor logs have been converted to new format
 * @see \Aheadworks\Rbslider\Model\ActionLog
 */
class Statistic extends AbstractModel implements StatisticInterface
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(ResourceStatistic::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
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
    public function getSlideBannerId()
    {
        return $this->getData(self::SLIDE_BANNER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setSlideBannerId($slideBannerId)
    {
        return $this->setData(self::SLIDE_BANNER_ID, $slideBannerId);
    }

    /**
     * {@inheritdoc}
     */
    public function getViewCount()
    {
        return $this->getData(self::VIEW_COUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setViewCount($viewCount)
    {
        return $this->setData(self::VIEW_COUNT, $viewCount);
    }

    /**
     * {@inheritdoc}
     */
    public function getClickCount()
    {
        return $this->getData(self::CLICK_COUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setClickCount($clickCount)
    {
        return $this->setData(self::CLICK_COUNT, $clickCount);
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
    public function setExtensionAttributes(StatisticExtensionInterface $extensionAttributes)
    {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }
}
