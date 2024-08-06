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

namespace Aheadworks\Rbslider\Setup\Patch\Data;

use Aheadworks\Rbslider\Api\Data\ActionLogInterface;
use Aheadworks\Rbslider\Api\Data\StatisticInterface;
use Aheadworks\Rbslider\Model\ResourceModel\Statistic\CollectionFactory as StatisticCollectionFactory;
use Aheadworks\Rbslider\Model\Statistic;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class MigrateStatisticToNewFormat implements DataPatchInterface
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var StatisticCollectionFactory
     */
    private $statisticCollectionFactory;

    /**
     * @param ResourceConnection $resourceConnection
     * @param StatisticCollectionFactory $statisticCollectionFactory
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        StatisticCollectionFactory $statisticCollectionFactory
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->statisticCollectionFactory = $statisticCollectionFactory;
    }

    /**
     * Declare dependencies for the patch
     *
     * @return string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Declare aliases for the patch
     *
     * @return string[]
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Run statistic migration
     *
     * @return $this
     */
    public function apply()
    {
        /** @var Statistic[] $statistics */
        $statistics = $this->statisticCollectionFactory->create();

        $countFields = [
            StatisticInterface::VIEW_COUNT => ActionLogInterface::ACTION_TYPE_VIEW,
            StatisticInterface::CLICK_COUNT => ActionLogInterface::ACTION_TYPE_CLICK
        ];

        $insertData = [];
        foreach ($statistics as $statistic) {
            foreach ($countFields as $countField => $actionType) {
                for ($count = $statistic->getData($countField); $count; $count--) {
                    $insertData[] = [
                        'slide_id' => $statistic->getData('slide_id'),
                        'banner_id' => $statistic->getData('banner_id'),
                        'action_type' => $actionType
                    ];

                    if (count($insertData) > 1000) {
                        $this->insertData($insertData);
                        $insertData = [];
                    }
                }
            }
        }

        if ($insertData) {
            $this->insertData($insertData);
        }

        return $this;
    }

    /**
     * Insert data to the new table
     *
     * @param array $data
     * @return void
     */
    private function insertData(array $data): void
    {
        $connection = $this->resourceConnection->getConnection();
        foreach (array_chunk($data, 50) as $dataSlice) {
            $connection->insertMultiple(
                $connection->getTableName('aw_rbslider_action_log'),
                $dataSlice
            );
        }
    }
}
