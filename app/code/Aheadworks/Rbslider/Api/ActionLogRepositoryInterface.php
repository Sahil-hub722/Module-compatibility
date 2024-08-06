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

namespace Aheadworks\Rbslider\Api;

use Aheadworks\Rbslider\Api\Data\ActionLogInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface ActionLogRepositoryInterface
{
    /**
     * Retrieve logs matching the specified criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\Rbslider\Api\Data\ActionLogInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array;

    /**
     * Save action log
     *
     * @param \Aheadworks\Rbslider\Api\Data\ActionLogInterface $actionLog
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(ActionLogInterface $actionLog): void;

    /**
     * Delete action log
     *
     * @param \Aheadworks\Rbslider\Api\Data\ActionLogInterface $actionLog
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(ActionLogInterface $actionLog): void;
}
