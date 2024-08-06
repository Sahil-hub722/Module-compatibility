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
namespace Aheadworks\Rbslider\Model\ResourceModel\Statistic;

use Aheadworks\Rbslider\Model\ResourceModel\AbstractCollection;
use Aheadworks\Rbslider\Model\ResourceModel\Statistic as ResourceStatistic;
use Aheadworks\Rbslider\Model\Statistic;

/**
 * Class Collection
 * @package Aheadworks\Rbslider\Model\ResourceModel\Statistic
 *
 * @deprecated Not being used since visitor logs have been converted to new format
 * @see \Aheadworks\Rbslider\Model\ResourceModel\ActionLog\Collection
 */
class Collection extends AbstractCollection
{
    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * @var array
     */
    private $joinColumns = [
        'ctr' => 'stat.ctr',
        'slide_id' => 'slide.id',
        'slide_name' => 'slide.name',
        'img_type' => 'slide.img_type',
        'img_file' => 'slide.img_file',
        'img_url' => 'slide.img_url',
        'banner_id' => 'banner.id',
        'banner_name' => 'banner.name'
    ];

    /**
     * Resource initialization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(Statistic::class, ResourceStatistic::class);
    }

    /**
     * @inheritDoc
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        $ctrQuery = $this->getConnection()->select()
            ->from(
                [$this->getTable('aw_rbslider_statistic')],
                ['ctr_id' => 'id', 'ctr' => 'ROUND(click_count/IF(view_count > 0, view_count, 1) * 100, 0)']
            );
        $this->getSelect()
            ->joinLeft(
                ['stat' => $ctrQuery],
                'main_table.id = stat.ctr_id',
                []
            )
            ->joinLeft(
                ['slide_banner' => $this->getTable('aw_rbslider_slide_banner')],
                'main_table.slide_banner_id = slide_banner.id',
                []
            )
            ->joinLeft(
                ['banner' => $this->getTable('aw_rbslider_banner')],
                'slide_banner.banner_id = banner.id',
                []
            )
            ->joinLeft(
                ['slide' => $this->getTable('aw_rbslider_slide')],
                'slide_banner.slide_id = slide.id',
                []
            )
            ->columns($this->joinColumns);

        foreach ($this->joinColumns as $filter => $alias) {
            $this->addFilterToMap($filter, $alias);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if (isset($this->joinColumns[$field])) {
            $this->addFilterToMap($field, $this->joinColumns[$field]);
        } else {
            $this->addFilterToMap($field, 'main_table.' . $field);
        }

        return parent::addFieldToFilter($field, $condition);
    }
}
