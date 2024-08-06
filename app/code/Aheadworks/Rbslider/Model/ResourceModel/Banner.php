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
namespace Aheadworks\Rbslider\Model\ResourceModel;

use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Model\ResourceModel\Indexer\BannerProduct\BannerProductInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Banner
 * @package Aheadworks\Rbslider\Model\ResourceModel
 */
class Banner extends AbstractDb
{
    /**#@+
     * Constants for table names
     */
    const MAIN_TABLE_NAME = 'aw_rbslider_banner';
    const PRODUCT_INDEX_TABLE_NAME = 'aw_rbslider_banner_product';
    const PRODUCT_INDEX_IDX_TABLE_NAME = 'aw_rbslider_banner_product_idx';
    /**#@-*/

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE_NAME, BannerInterface::ID);
    }

    /**
     * Check is banner available for product
     *
     * @param int $bannerId
     * @param int $productId
     * @return bool
     */
    public function isBannerAvailableForProduct($productId, $bannerId)
    {
        $select = $this->getConnection()->select()
            ->from(
                $this->getTable(self::PRODUCT_INDEX_TABLE_NAME),
                [BannerProductInterface::BANNER_ID]
            )->where(
                BannerProductInterface::PRODUCT_ID . ' = ?',
                $productId
            )->where(
                BannerProductInterface::BANNER_ID . ' = ?',
                $bannerId
            );

        return (bool)$this->getConnection()->fetchOne($select);
    }
}
