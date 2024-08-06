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
namespace Aheadworks\Rbslider\Model\ResourceModel\Slide;

use Aheadworks\Rbslider\Api\Data\SlideInterface;
use Aheadworks\Rbslider\Model\ResourceModel\AbstractCollection;
use Aheadworks\Rbslider\Model\ResourceModel\Slide as ResourceSlide;
use Aheadworks\Rbslider\Model\Slide;
use Magento\Framework\DB\Select;
use Magento\Store\Model\Store;

/**
 * Class Collection
 * @package Aheadworks\Rbslider\Model\ResourceModel\Slide
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
    const BANNER_NAMES = 'banner_names';
    const CUSTOMER_GROUP_NAMES = 'customer_group_names';
    /**#@-*/

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
        self::BANNER_NAMES => 'banner.names',
        SlideInterface::BANNER_IDS => 'banner.ids',
        self::CUSTOMER_GROUP_NAMES => 'customer_group.names',
        SlideInterface::CUSTOMER_GROUP_IDS => 'customer_group.ids',
        SlideInterface::STORE_IDS => 'store.ids',
        'position' => 'slide_banner.position'
    ];

    /**
     * Resource initialization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(Slide::class, ResourceSlide::class);
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
                'main_table.id = slide_banner.slide_id',
                []
            )
            ->joinLeft(
                ['slide_customer_group' => $this->getTable('aw_rbslider_slide_customer_group')],
                'main_table.id = slide_customer_group.slide_id',
                []
            )
            ->joinLeft(
                ['slide_customer_segment' => $this->getTable('aw_rbslider_slide_segment')],
                'main_table.id = slide_customer_segment.slide_id',
                []
            )
            ->joinLeft(
                ['slide_store' => $this->getTable('aw_rbslider_slide_store')],
                'main_table.id = slide_store.slide_id',
                []
            )
            ->joinLeft(
                ['banner' => $this->getBannersAggregateSubQuery()],
                'main_table.id = banner.slide_id',
                []
            )
            ->joinLeft(
                ['customer_group' => $this->getCustomerGroupsAggregateSubQuery()],
                'main_table.id = customer_group.slide_id',
                []
            )
            ->joinLeft(
                ['store' => $this->getStoreAggregateSubQuery()],
                'main_table.id = store.slide_id',
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
     * Retrieve banners aggregation sub query
     *
     * @return Select
     */
    private function getBannersAggregateSubQuery()
    {
        return $this->getConnection()->select()
            ->from(
                ['slide_banner' => $this->getTable('aw_rbslider_slide_banner')],
                [
                    'slide_id' => 'slide_id',
                    'ids' => 'GROUP_CONCAT(DISTINCT banners.id SEPARATOR "' . self::SEPARATOR . '")',
                    'names' => 'GROUP_CONCAT(DISTINCT banners.name SEPARATOR "' . self::SEPARATOR . '")'
                ]
            )
            ->joinLeft(
                ['banners' => $this->getTable('aw_rbslider_banner')],
                'slide_banner.banner_id = banners.id',
                []
            )
            ->group('slide_banner.slide_id');
    }

    /**
     * Retrieve customer groups aggregation sub query
     *
     * @return Select
     */
    private function getCustomerGroupsAggregateSubQuery()
    {
        return $this->getConnection()->select()
            ->from(
                ['slide_group' => $this->getTable('aw_rbslider_slide_customer_group')],
                [
                    'slide_id' => 'slide_id',
                    'ids' => 'GROUP_CONCAT(DISTINCT groups.customer_group_id SEPARATOR "' . self::SEPARATOR . '")',
                    'names' => 'GROUP_CONCAT(DISTINCT groups.customer_group_code SEPARATOR ", ")'
                ]
            )
            ->joinLeft(
                ['groups' => $this->getTable('customer_group')],
                'slide_group.customer_group_id = groups.customer_group_id',
                []
            )
            ->group('slide_group.slide_id');
    }

    /**
     * Retrieve store aggregation sub query
     *
     * @return Select
     */
    private function getStoreAggregateSubQuery()
    {
        return $this->getConnection()->select()
            ->from(
                [$this->getTable('aw_rbslider_slide_store')],
                [
                    'slide_id' => 'slide_id',
                    'ids' => 'GROUP_CONCAT(DISTINCT store_id SEPARATOR "' . self::SEPARATOR . '")',
                ]
            )
            ->group('slide_id');
    }

    /**
     * @inheritDoc
     */
    protected function _afterLoad()
    {
        $this->convertAggregatedValuesToArrayInAllItems(
            [
                self::BANNER_NAMES,
                SlideInterface::BANNER_IDS,
                SlideInterface::CUSTOMER_GROUP_IDS,
                SlideInterface::STORE_IDS
            ],
            self::SEPARATOR
        );

        $this->attachSegmentsData();

        return parent::_afterLoad();
    }

    /**
     * @inheritdoc
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field == 'display_from' || $field == 'display_to') {
            // Fix if apply filter on display_from or display_to in grid
            $resultCondition = $this->_translateCondition($field, ['null' => ''])
                . ' OR ' . $this->_translateCondition($field, $condition);
            return $this->getSelect()->where($resultCondition, null, Select::TYPE_CONDITION);
        }
        if ($field == 'customer_group_id') {
            return $this->addCustomerGroupFilter($condition);
        }
        if ($field == 'store_id') {
            return $this->addStoreFilter($condition);
        }
        if ($field == 'banner_id') {
            return $this->addBannerFilter($condition);
        }
        if ($field == SlideInterface::CUSTOMER_SEGMENT_IDS) {
            return $this->addCustomerSegmentFilter($condition);
        }

        $filters = is_array($field) ? $field : [$field];
        foreach ($filters as $filterItem) {
            if (isset($this->joinColumns[$filterItem])) {
                $this->addFilterToMap($filterItem, $this->joinColumns[$filterItem]);
            } else {
                $this->addFilterToMap($filterItem, 'main_table.' . $filterItem);
            }
        }

        return parent::addFieldToFilter($field, $condition);
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

        $slides = parent::_toOptionArray('id', 'name');
        if (!count($slides)) {
            array_unshift(
                $slides,
                ['value' => 0, 'label' => __('No slides found')]
            );
        }
        return $slides;
    }

    /**
     * Join slide position in banner
     *
     * @param int $bannerId
     * @return $this
     */
    public function joinPositions($bannerId)
    {
        if (!$this->getFlag('slide_positions_joined')) {
            $this->getSelect()->joinLeft(
                ['pos' => $this->getTable('aw_rbslider_slide_banner')],
                'main_table.id = pos.slide_id AND pos.banner_id = ' . $bannerId,
                ['position' => 'IFNULL(pos.position, 0)']
            );
            $this->addFilterToMap('position', 'pos.position');
            $this->setFlag('slide_positions_joined', true);
        }

        return $this;
    }

    /**
     * Add banner filter
     *
     * @param int|array $banner
     * @return $this
     */
    public function addBannerFilter($banner)
    {
        if (!is_array($banner)) {
            $banner = [$banner];
        }
        $this->addFilter('slide_banner.banner_id', ['in' => $banner], 'public');

        return $this;
    }

    /**
     * Add store filter
     *
     * @param int|array $store
     * @return $this
     */
    public function addStoreFilter($store)
    {
        if (!is_array($store)) {
            $store = [$store];
        }
        $store[] = Store::DEFAULT_STORE_ID;
        $this->addFilter('slide_store.store_id', ['in' => $store], 'public');

        return $this;
    }

    /**
     * Add customer group filter
     *
     * @param int|array $customerGroup
     * @return $this
     */
    public function addCustomerGroupFilter($customerGroup)
    {
        if (!is_array($customerGroup)) {
            $customerGroup = [$customerGroup];
        }
        $this->addFilter('slide_customer_group.customer_group_id', ['in' => $customerGroup], 'public');

        return $this;
    }

    /**
     * Add date filter
     *
     * @param string $currentDate
     * @return $this
     */
    public function addDateFilter($currentDate)
    {
        $this
            ->getSelect()
            ->where(
                '(main_table.display_from IS NULL OR main_table.display_from <= "' . $currentDate . '")
                AND (main_table.display_to IS NULL OR main_table.display_to >= "' . $currentDate . '")'
            );
        return $this;
    }

    /**
     * Attach customer segments data
     *
     * @return $this
     */
    private function attachSegmentsData()
    {
        $select = $this->getConnection()->select()->from($this->getTable('aw_rbslider_slide_segment'));
        $segmentsData = $this->getConnection()->fetchAll($select);

        /** @var SlideInterface $item */
        foreach ($this->getItems() as $item) {
            $dataToAttach = [];
            $popupId = $item->getId();
            foreach ($segmentsData as $segmentData) {
                if ($segmentData['slide_id'] == $popupId) {
                    $dataToAttach[] = $segmentData['segment_id'];
                }
            }
            $item->setCustomerSegmentIds($dataToAttach);
        }

        return $this;
    }

    /**
     * Add customer segment filter
     *
     * @param int|array $customerSegment
     * @return $this
     */
    public function addCustomerSegmentFilter($customerSegment)
    {
        if (!is_array($customerSegment)) {
            $customerSegment = [$customerSegment];
        }

        if (empty($customerSegment)) {
            $this->getSelect()->where('slide_customer_segment.segment_id IS NULL');
        } else {
            $this
                ->getSelect()
                ->where(
                    'slide_customer_segment.segment_id IS NULL OR slide_customer_segment.segment_id IN (?)',
                    $customerSegment
                );
        }

        return $this;
    }
}
