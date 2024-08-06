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
namespace Aheadworks\Rbslider\Api;

/**
 * Statistic CRUD interface.
 * @api
 *
 * @deprecated Not being used since visitor logs have been converted to new format
 * @see \Aheadworks\Rbslider\Api\ActionLogRepositoryInterface
 */
interface StatisticRepositoryInterface
{
    /**
     * Save statistic.
     *
     * @param \Aheadworks\Rbslider\Api\Data\StatisticInterface $statistic
     * @return \Aheadworks\Rbslider\Api\Data\StatisticInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Aheadworks\Rbslider\Api\Data\StatisticInterface $statistic);

    /**
     * Retrieve statistic.
     *
     * @param int $statisticId
     * @return \Aheadworks\Rbslider\Api\Data\StatisticInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($statisticId);

    /**
     * Retrieve statistics matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\Rbslider\Api\Data\StatisticSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete statistic.
     *
     * @param \Aheadworks\Rbslider\Api\Data\StatisticInterface $statistic
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Aheadworks\Rbslider\Api\Data\StatisticInterface $statistic);

    /**
     * Delete statistic by ID.
     *
     * @param int $statisticId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($statisticId);
}
