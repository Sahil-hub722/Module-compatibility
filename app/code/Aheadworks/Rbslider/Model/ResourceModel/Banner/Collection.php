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
namespace Aheadworks\Rbslider\Model\ResourceModel\Banner;

use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Model\Banner;
use Aheadworks\Rbslider\Model\ResourceModel\AbstractCollection;
use Aheadworks\Rbslider\Model\ResourceModel\Banner as ResourceBanner;
use Aheadworks\Rbslider\Model\Source\PageType;
use Aheadworks\Rbslider\Model\Source\Position;
use Magento\Framework\DB\Select;

/**
 * Class Collection
 * @package Aheadworks\Rbslider\Model\ResourceModel\Banner
 */
class Collection extends AbstractCollection
{
    /**
     * Value separator
     */
    const SEPARATOR = '<|sep|>';

    /**#@+
     * Constants defined custom fields in query.
     */
    const SLIDE_NAMES = 'slide_names';

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
        self::SLIDE_NAMES => 'slide.names',
        BannerInterface::SLIDE_IDS => 'slide.ids',
    ];

    /**
     * Resource initialization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(Banner::class, ResourceBanner::class);
    }

    /**
     * @inheritDoc
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()
            ->joinLeft(
                ['slide_banner' => $this->getTable('aw_rbslider_slide_banner')],
                'main_table.id = slide_banner.banner_id',
                []
            )
            ->joinLeft(
                ['slide' => $this->getSlidesAggregateSubQuery()],
                'main_table.id = slide.banner_id',
                []
            )
            ->group('main_table.id')
            ->columns($this->joinColumns);

        foreach ($this->joinColumns as $filter => $alias) {
            $this->addFilterToMap($filter, $alias);
        }

        return $this;
    }

    /**
     * Retrieve slides aggregation sub query
     *
     * @return Select
     */
    private function getSlidesAggregateSubQuery()
    {
        return $this->getConnection()->select()
            ->from(
                ['slide_banner' => $this->getTable('aw_rbslider_slide_banner')],
                [
                    'banner_id' => 'banner_id',
                    'ids' => 'GROUP_CONCAT(DISTINCT slides.id SEPARATOR "' . self::SEPARATOR . '")',
                    'names' => 'GROUP_CONCAT(DISTINCT slides.name SEPARATOR "' . self::SEPARATOR . '")'
                ]
            )
            ->joinLeft(
                ['slides' => $this->getTable('aw_rbslider_slide')],
                'slide_banner.slide_id = slides.id',
                []
            )
            ->group('slide_banner.banner_id');
    }

    /**
     * @inheritDoc
     */
    protected function _afterLoad()
    {
        $this->convertAggregatedValuesToArrayInAllItems(
            [self::SLIDE_NAMES, BannerInterface::SLIDE_IDS],
            self::SEPARATOR
        );

        return parent::_afterLoad();
    }

    /**
     * @inheritdoc
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field == 'slide_id') {
            $this->addFilter('slide_banner.slide_id', $condition, 'public');
            return $this;
        }

        $fields = is_array($field) ? $field : [$field];
        foreach ($fields as $fieldItem) {
            if (isset($this->joinColumns[$fieldItem])) {
                $this->addFilterToMap($fieldItem, $this->joinColumns[$fieldItem]);
            } else {
                $this->addFilterToMap($fieldItem, 'main_table.' . $fieldItem);
            }
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * {@inheritdoc}
     */
    public function setOrder($field, $direction = self::SORT_ORDER_DESC)
    {
        if ($field == 'position') {
            // Fix if apply sorting on position
            $field = 'sort_position';
            $this->getSelect()->columns(
                new \Zend_Db_Expr(
                    'IF(main_table.position = ' . Position::CONTENT_TOP
                    . ' AND main_table.page_type = ' . PageType::CUSTOM_WIDGET
                    . ', "", main_table.position) as ' . $field
                )
            );
        }

        return parent::setOrder($field, $direction);
    }

    /**
     * Retrieve option array
     *
     * @return array
     */
    public function getOptionArray()
    {
        $optionArray = ['' => ' '];
        foreach ($this->toOptionArray() as $option) {
            $optionArray[$option['value']] = $option['label'];
        }
        return $optionArray;
    }

    /**
     * Retrieve option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->isLoaded()) {
            $this->getSelect()->reset();
            parent::_initSelect();
        }

        $banners = parent::_toOptionArray('id', 'name');
        if (!count($banners)) {
            array_unshift(
                $banners,
                ['value' => 0, 'label' => __('No banners found')]
            );
        }
        return $banners;
    }
}
