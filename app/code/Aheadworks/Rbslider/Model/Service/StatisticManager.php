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

namespace Aheadworks\Rbslider\Model\Service;

use Aheadworks\Rbslider\Api\Data\StatisticInterface;
use Aheadworks\Rbslider\Api\StatisticManagementInterface;
use Aheadworks\Rbslider\Api\StatisticRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class StatisticManager
 * @package Aheadworks\Rbslider\Model\Service
 *
 * @deprecated Not being used since visitor logs have been converted to new format
 * @see \Aheadworks\Rbslider\Model\ActionLogManagement
 */
class StatisticManager implements StatisticManagementInterface
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var StatisticRepositoryInterface
     */
    private $statisticRepository;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param StatisticRepositoryInterface $statisticRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        StatisticRepositoryInterface $statisticRepository
    ) {
        $this->statisticRepository = $statisticRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function addView(int $bannerId, int $slideId): bool
    {
        $result = true;
        $statisticList = $this->getStatisticList($bannerId, $slideId);
        if (empty($statisticList)) {
            $result = false;
        }
        foreach ($statisticList as $statistic) {
            $statistic->setViewCount((int)$statistic->getViewCount() + 1);
            $this->statisticRepository->save($statistic);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function addClick(int $bannerId, int $slideId): bool
    {
        $result = true;
        $statisticList = $this->getStatisticList($bannerId, $slideId);
        if (empty($statisticList)) {
            $result = false;
        }
        foreach ($statisticList as $statistic) {
            $statistic->setClickCount((int)$statistic->getClickCount() + 1);
            $this->statisticRepository->save($statistic);
        }

        return $result;
    }

    /**
     * Retrieve statistic list
     *
     * @param int $bannerId
     * @param int $slideId
     * @return StatisticInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getStatisticList(int $bannerId, int $slideId): array
    {
        $this->searchCriteriaBuilder
            ->addFilter('banner_id', $bannerId)
            ->addFilter('slide_id', $slideId);

        return $this->statisticRepository
            ->getList($this->searchCriteriaBuilder->create())
            ->getItems();
    }
}
