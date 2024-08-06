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

use Aheadworks\Rbslider\Api\Data\SlideInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Slide
 * @package Aheadworks\Rbslider\Model\ResourceModel
 */
class Slide extends AbstractDb
{
    /**#@+
     * Constants defined for table names
     */
    const MAIN_TABLE_NAME = 'aw_rbslider_slide';
    const SEGMENTS_TABLE_NAME = 'aw_rbslider_slide_segment';
    /**#@-*/

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE_NAME, SlideInterface::ID);
    }

    /**
     * Clear segment associations
     *
     * @param int $segmentId
     */
    public function clearSegmentAssociations($segmentId)
    {
        $this->getConnection()->delete(
            $this->getTable(self::SEGMENTS_TABLE_NAME),
            'segment_id = ' . $segmentId
        );
    }
}
