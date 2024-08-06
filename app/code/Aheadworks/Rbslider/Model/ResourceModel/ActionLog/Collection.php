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

namespace Aheadworks\Rbslider\Model\ResourceModel\ActionLog;

use Aheadworks\Rbslider\Model\ActionLog;
use Aheadworks\Rbslider\Model\ResourceModel\ActionLog as ActionLogResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Set relation to model and resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(
            ActionLog::class,
            ActionLogResource::class
        );
    }
}
