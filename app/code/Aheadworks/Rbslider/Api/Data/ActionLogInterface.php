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

namespace Aheadworks\Rbslider\Api\Data;

interface ActionLogInterface
{
    /**
     * Constants keys of the data model
     */
    const ACTION_LOG_ID = 'action_log_id';
    const SLIDE_ID = 'slide_id';
    const BANNER_ID = 'banner_id';
    const CUSTOMER_ID = 'customer_id';
    const ACTION_TYPE = 'action_type';
    const ACTION_TIME = 'action_time';

    /**
     * Definitions of different types of action
     */
    const ACTION_TYPE_VIEW = 1;
    const ACTION_TYPE_CLICK = 2;

    /**
     * Retrieve action log id
     *
     * @return int|null
     */
    public function getActionLogId(): ?int;

    /**
     * Set action log id
     *
     * @param int $actionLogId
     * @return $this
     */
    public function setActionLogId(int $actionLogId): self;

    /**
     * Retrieve slide id
     *
     * @return int|null
     */
    public function getSlideId(): ?int;

    /**
     * Set slide id
     *
     * @param int $slideId
     * @return $this
     */
    public function setSlideId(int $slideId): self;

    /**
     * Retrieve banner id
     *
     * @return int|null
     */
    public function getBannerId(): ?int;

    /**
     * Set banner id
     *
     * @param int $bannerId
     * @return $this
     */
    public function setBannerId(int $bannerId): self;

    /**
     * Retrieve customer id
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Set customer id
     *
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId(int $customerId): self;

    /**
     * Retrieve action type
     *
     * @return int|null
     */
    public function getActionType(): ?int;

    /**
     * Set action type
     *
     * @param int $actionType
     * @return $this
     */
    public function setActionType(int $actionType): self;

    /**
     * Retrieve action time
     *
     * @return string|null
     */
    public function getActionTime(): ?string;

    /**
     * Set action time
     *
     * @param string $actionTime
     * @return $this
     */
    public function setActionTime(string $actionTime): self;
}
