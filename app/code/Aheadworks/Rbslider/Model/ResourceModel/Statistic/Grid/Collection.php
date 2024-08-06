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

namespace Aheadworks\Rbslider\Model\ResourceModel\Statistic\Grid;

use Aheadworks\Rbslider\Api\Data\ActionLogInterface;
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
            'view_count' => 'statistic.view_count',
            'click_count' => 'statistic.click_count',
            'ctr' => 'statistic.ctr'
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

        $countExpression = 'COUNT(IF(action_type = %d, \'*\', NULL))';
        $viewCountExpression = sprintf($countExpression, ActionLogInterface::ACTION_TYPE_VIEW);
        $clickCountExpression = sprintf($countExpression, ActionLogInterface::ACTION_TYPE_CLICK);

        $subQuery = $this->getConnection()->select()
            ->from(
                $this->getMainTable(),
                [
                    'statistic_id' => 'action_log_id',
                    'view_count' => $viewCountExpression,
                    'click_count' => $clickCountExpression,
                    'ctr' => str_replace(
                        ['%view_count%', '%click_count%'],
                        [$viewCountExpression, $clickCountExpression],
                        'ROUND(%click_count%/IF(%view_count% > 0, %view_count%, 1) * 100, 0)'
                    )
                ]
            )
            ->group([
                'banner_id',
                'slide_id'
            ]);

        $this->getSelect()
            ->join(
                ['statistic' => $subQuery],
                'main_table.action_log_id = statistic.statistic_id',
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
            ->columns($this->_map['fields']);
    }
}
