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

namespace Aheadworks\Rbslider\Api;

/**
 * Interface StatisticManagerInterface
 * @api
 *
 * @deprecated Not being used since visitor logs have been converted to new format
 * @see \Aheadworks\Rbslider\Api\ActionLogManagementInterface
 */
interface StatisticManagementInterface
{
    /**
     * Add one more view to slide-banner statistic
     *
     * @param int $bannerId
     * @param int $slideId
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addView(int $bannerId, int $slideId): bool;

    /**
     * Add one more click to slide-banner statistic
     *
     * @param int $bannerId
     * @param int $slideId
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addClick(int $bannerId, int $slideId): bool;
}
