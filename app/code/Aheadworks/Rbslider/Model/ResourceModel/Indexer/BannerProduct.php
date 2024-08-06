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
namespace Aheadworks\Rbslider\Model\ResourceModel\Indexer;

use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Model\ResourceModel\Indexer\BannerProduct\BannerProductInterface;
use Aheadworks\Rbslider\Model\Source\Status;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Indexer\Table\StrategyInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Indexer\Model\ResourceModel\AbstractResource;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Catalog\Model\Product as ProductModel;
use Aheadworks\Rbslider\Model\ResourceModel\Indexer\BannerProduct\DataCollector as DataCollector;
use Aheadworks\Rbslider\Model\ResourceModel\Banner as BannerResourceModel;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Aheadworks\Rbslider\Api\BannerRepositoryInterface;
use Aheadworks\Rbslider\Model\Indexer\Banner\ProductLoader;

/**
 * Class BannerProduct
 * @package Aheadworks\Rbslider\Model\ResourceModel\Indexer
 */
class BannerProduct extends AbstractResource implements IdentityInterface
{
    /**
     * @var int
     */
    const LIMIT = 500;

    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * @var DataCollector;
     */
    private $dataCollector;

    /**
     * @var BannerRepositoryInterface
     */
    private $bannerRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var ProductLoader
     */
    private $productLoader;

    /**
     * @var array
     */
    private $entities = [];

    /**
     * @param Context $context
     * @param StrategyInterface $tableStrategy
     * @param EventManagerInterface $eventManager
     * @param DataCollector $dataCollector
     * @param BannerRepositoryInterface $bannerRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ProductLoader $productLoader
     * @param string|null $connectionName
     */
    public function __construct(
        Context $context,
        StrategyInterface $tableStrategy,
        EventManagerInterface $eventManager,
        DataCollector $dataCollector,
        BannerRepositoryInterface $bannerRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProductLoader $productLoader,
        $connectionName = null
    ) {
        parent::__construct($context, $tableStrategy, $connectionName);
        $this->dataCollector = $dataCollector;
        $this->eventManager = $eventManager;
        $this->bannerRepository = $bannerRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productLoader = $productLoader;
    }

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(
            BannerResourceModel::PRODUCT_INDEX_TABLE_NAME,
            BannerProductInterface::BANNER_PRODUCT_ID
        );
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function reindexAll()
    {
        $this->clearTemporaryIndexTable();
        try {
            $toInsert = $this->getDataToInsert();
            $this->beginTransaction();
            $this->performInsertData($toInsert, true);
            $this->commit();
        } catch (\Exception $e) {
            $this->rollBack();
            throw $e;
        }
        $this->syncData();
        $this->dispatchCleanCacheByTags($toInsert);
        return $this;
    }

    /**
     * Reindex product banner data for defined ids
     *
     * @param array|int $ids
     * @return $this
     * @throws \Exception
     */
    public function reindexRows($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $toUpdate = $this->getDataToUpdate($ids);
        $this->beginTransaction();
        try {
            $this->getConnection()->delete(
                $this->getMainTable(),
                [BannerProductInterface::PRODUCT_ID . ' IN (?)' => $ids]
            );
            $this->performInsertData($toUpdate);
            $this->commit();
            $this->dispatchCleanCacheByTags($toUpdate);
        } catch (\Exception $e) {
            $this->rollBack();
            throw $e;
        }
        return $this;
    }

    /**
     * Reindex product banner data for defined banner
     *
     * @param BannerInterface $banner
     * @return $this
     * @throws \Exception
     */
    public function reindexForBanner($banner)
    {
        $toUpdate = $this->dataCollector->prepareBannerData($banner);
        $this->beginTransaction();
        try {
            $this->getConnection()->delete(
                $this->getMainTable(),
                [BannerProductInterface::BANNER_ID . ' IN (?)' => [$banner->getId()]]
            );
            $this->performInsertData($toUpdate);
            $this->commit();
            $this->dispatchCleanCacheByTags($toUpdate);
        } catch (\Exception $e) {
            $this->rollBack();
            throw $e;
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getIdxTable($table = null)
    {
        return $this->getTable(BannerResourceModel::PRODUCT_INDEX_IDX_TABLE_NAME);
    }

    /**
     * Return data to insert
     *
     * @return array
     * @throws LocalizedException
     */
    private function getDataToInsert()
    {
        $banners = $this->getActiveBanners();

        $result = [];
        foreach ($banners as $banner) {
            $data = $this->dataCollector->prepareBannerData($banner);
            $result = array_merge($result, $data);
        }
        return $result;
    }

    /**
     * Return data to update
     *
     * @param int[] $productIds
     * @return array
     * @throws LocalizedException
     */
    private function getDataToUpdate($productIds)
    {
        $banners = $this->getActiveBanners();
        $products = $this->productLoader->getProducts($productIds);
        $result = [];

        foreach ($banners as $banner) {
            foreach ($products as $product) {
                $data = $this->dataCollector->prepareBannerProductData($banner, $product);
                $result = array_merge($result, $data);
            }
        }
        return $result;
    }

    /**
     * Perform partial insert data to index table
     *
     * @param array $data
     * @param bool $intoIndexTable
     * @return $this
     * @throws LocalizedException
     */
    private function performInsertData($data, $intoIndexTable = false)
    {
        $counter = 0;
        $toInsert = [];
        foreach ($data as $row) {
            $counter++;
            $toInsert[] = $row;
            if ($counter % self::LIMIT == 0) {
                $this->insertToTable($toInsert, $intoIndexTable);
                $toInsert = [];
            }
        }
        $this->insertToTable($toInsert, $intoIndexTable);
        return $this;
    }

    /**
     * Insert to index table
     *
     * @param $toInsert
     * @param bool $intoIndexTable
     * @return $this
     * @throws LocalizedException
     */
    private function insertToTable($toInsert, $intoIndexTable = false)
    {
        $table = $intoIndexTable
            ? $this->getTable($this->getIdxTable())
            : $this->getMainTable();
        if (count($toInsert)) {
            $this->getConnection()->insertMultiple(
                $table,
                $toInsert
            );
        }
        return $this;
    }

    /**
     * Dispatch clean_cache_by_tags event
     *
     * @param array $entities
     * @return void
     */
    private function dispatchCleanCacheByTags($entities = [])
    {
        $this->entities = $entities;
        $this->eventManager->dispatch('clean_cache_by_tags', ['object' => $this]);
    }

    /**
     * Get affected cache tags
     *
     * @return array
     * @codeCoverageIgnore
     */
    public function getIdentities()
    {
        $identities = [];
        foreach ($this->entities as $entity) {
            $identities[] = ProductModel::CACHE_TAG . '_' . $entity[BannerProductInterface::PRODUCT_ID];
        }

        return array_unique($identities);
    }

    /**
     * Retrieve active banners
     *
     * @return BannerInterface[]
     * @throws LocalizedException
     */
    private function getActiveBanners()
    {
        $this->searchCriteriaBuilder->addFilter(BannerInterface::STATUS, ['eq' => Status::STATUS_ENABLED]);

        return $this->bannerRepository
            ->getList($this->searchCriteriaBuilder->create())
            ->getItems();
    }
}
