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

namespace Aheadworks\Rbslider\Model\ResourceModel\Statistic\Report\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    /**
     * @var array
     */
    protected $_map = [
        'fields' => [
            'img_type' => 'slide.img_type',
            'img_file' => 'slide.img_file',
            'img_url' => 'slide.img_url',
            'slide_name' => 'slide.name',
            'banner_name' => 'banner.name',
            'customer_email' => 'customer.email',
            'action_count' => 'statistic_report.action_count',
            'first_action_time' => 'statistic_report.first_action_time',
            'last_action_time' => 'statistic_report.last_action_time'
        ]
    ];

    /**
     * Prepare select for query
     *
     * @return void
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        $subQuery = $this->getConnection()->select()
            ->from(
                $this->getMainTable(),
                [
                    'statistic_report_id' => 'action_log_id',
                    'action_count' => 'COUNT(*)',
                    'first_action_time' => 'MIN(action_time)',
                    'last_action_time' => 'MAX(action_time)'
                ]
            )
            ->group([
                'banner_id',
                'slide_id',
                'customer_id',
                'action_type'
            ]);

        $this->getSelect()
            ->join(
                ['statistic_report' => $subQuery],
                'main_table.action_log_id = statistic_report.statistic_report_id',
                []
            )
            ->join(
                ['slide' => $this->getTable('aw_rbslider_slide')],
                'main_table.slide_id = slide.id',
                []
            )
            ->join(
                ['banner' => $this->getTable('aw_rbslider_banner')],
                'main_table.banner_id = banner.id',
                []
            )
            ->joinLeft(
                ['customer' => $this->getTable('customer_entity')],
                'main_table.customer_id = customer.entity_id',
                []
            )
            ->columns($this->_map['fields']);
    }
}
