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

namespace Aheadworks\Rbslider\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ActionLog extends AbstractDb
{
    /**
     * Set relation to database table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'aw_rbslider_action_log',
            'action_log_id'
        );
    }

    /**
     * Multiple log deleting by predefined filter
     *
     * @param int|null $bannerId
     * @param int|null $slideId
     * @return void
     * @throws LocalizedException
     */
    public function deleteMultiple(
        int $bannerId = null,
        int $slideId = null
    ): void {
        $filter = [
            'banner_id = ?' => $bannerId,
            'slide_id = ?' => $slideId
        ];

        $this->getConnection()->delete(
            $this->getMainTable(),
            array_filter($filter)
        );
    }
}
