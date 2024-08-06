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
namespace Aheadworks\Rbslider\Api;

use Magento\Framework\Exception\LocalizedException;

/**
 * Rbslider block repository interface
 *
 * @api
 */
interface BlockRepositoryInterface
{
    /**
     * Retrieve block(s) matching the specified blockType and blockPosition
     *
     * @param int $blockType
     * @param int $storeId
     * @param int $customerGroupId
     *
     * @return \Aheadworks\Rbslider\Api\Data\BlockSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList($blockType, $storeId, $customerGroupId);

    /**
     * Retrieve block by specified banner ID
     *
     * @param int $bannerId
     * @param int $storeId
     * @param int $customerGroupId
     *
     * @return \Aheadworks\Rbslider\Api\Data\BlockInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws LocalizedException
     */
    public function getByBannerId($bannerId, $storeId, $customerGroupId);

    /**
     * Retrieve block for widget by banner ID
     *
     * @param int $bannerId
     * @param int $storeId
     * @param int $customerGroupId
     *
     * @return \Aheadworks\Rbslider\Api\Data\BlockInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws LocalizedException
     */
    public function getByBannerIdForWidget($bannerId, $storeId, $customerGroupId);
}
