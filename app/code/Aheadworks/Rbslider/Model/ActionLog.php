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

namespace Aheadworks\Rbslider\Model;

use Aheadworks\Rbslider\Api\Data\ActionLogInterface;
use Aheadworks\Rbslider\Model\ResourceModel\ActionLog as ActionLogResource;
use Magento\Framework\Model\AbstractModel;

class ActionLog extends AbstractModel implements ActionLogInterface
{
    /**
     * Set relation to resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ActionLogResource::class);
    }

    /**
     * Retrieve action log id
     *
     * @return int|null
     */
    public function getActionLogId(): ?int
    {
        return (int) $this->getData(self::ACTION_LOG_ID) ?: null;
    }

    /**
     * Set action log id
     *
     * @param int $actionLogId
     * @return $this
     */
    public function setActionLogId(int $actionLogId): ActionLogInterface
    {
        return $this->setData(self::ACTION_LOG_ID, $actionLogId);
    }

    /**
     * Retrieve slide id
     *
     * @return int|null
     */
    public function getSlideId(): ?int
    {
        return (int) $this->getData(self::SLIDE_ID) ?: null;
    }

    /**
     * Set slide id
     *
     * @param int $slideId
     * @return $this
     */
    public function setSlideId(int $slideId): ActionLogInterface
    {
        return $this->setData(self::SLIDE_ID, $slideId);
    }

    /**
     * Retrieve banner id
     *
     * @return int|null
     */
    public function getBannerId(): ?int
    {
        return (int) $this->getData(self::BANNER_ID) ?: null;
    }

    /**
     * Set banner id
     *
     * @param int $bannerId
     * @return $this
     */
    public function setBannerId(int $bannerId): ActionLogInterface
    {
        return $this->setData(self::BANNER_ID, $bannerId);
    }

    /**
     * Retrieve customer id
     *
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return (int) $this->getData(self::CUSTOMER_ID) ?: null;
    }

    /**
     * Set customer id
     *
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId(int $customerId): ActionLogInterface
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Retrieve action type
     *
     * @return int|null
     */
    public function getActionType(): ?int
    {
        return (int) $this->getData(self::ACTION_TYPE) ?: null;
    }

    /**
     * Set action type
     *
     * @param int $actionType
     * @return $this
     */
    public function setActionType(int $actionType): ActionLogInterface
    {
        return $this->setData(self::ACTION_TYPE, $actionType);
    }

    /**
     * Retrieve action time
     *
     * @return string|null
     */
    public function getActionTime(): ?string
    {
        return $this->getData(self::ACTION_TIME);
    }

    /**
     * Set action time
     *
     * @param string $actionTime
     * @return $this
     */
    public function setActionTime(string $actionTime): ActionLogInterface
    {
        return $this->setData(self::ACTION_TIME, $actionTime);
    }
}
