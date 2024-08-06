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

namespace Aheadworks\Rbslider\Model;

use Aheadworks\Rbslider\Api\ActionLogRepositoryInterface;
use Aheadworks\Rbslider\Api\Data\ActionLogInterface;
use Aheadworks\Rbslider\Model\ResourceModel\ActionLog as ActionLogResource;
use Aheadworks\Rbslider\Model\ResourceModel\ActionLog\CollectionFactory as ActionLogCollectionFactory;
use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

class ActionLogRepository implements ActionLogRepositoryInterface
{
    /**
     * @var ActionLogResource
     */
    private $actionLogResource;

    /**
     * @var ActionLogCollectionFactory
     */
    private $actionLogCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param ActionLogResource $actionLogResource
     * @param ActionLogCollectionFactory $actionLogCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ActionLogResource $actionLogResource,
        ActionLogCollectionFactory $actionLogCollectionFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->actionLogResource = $actionLogResource;
        $this->actionLogCollectionFactory = $actionLogCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Retrieve logs matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ActionLogInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array
    {
        $actionLogCollection = $this->actionLogCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $actionLogCollection);

        /** @var ActionLogInterface[] $actionLogs */
        $actionLogs = $actionLogCollection->getItems();

        return $actionLogs;
    }

    /**
     * Save action log
     *
     * @param ActionLogInterface $actionLog
     * @return void
     * @throws CouldNotSaveException
     */
    public function save(ActionLogInterface $actionLog): void
    {
        try {
            /** @var ActionLog $actionLog */
            $this->actionLogResource->save($actionLog);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(
                __($exception->getMessage())
            );
        }
    }

    /**
     * Delete action log
     *
     * @param ActionLogInterface $actionLog
     * @return void
     * @throws CouldNotDeleteException
     */
    public function delete(ActionLogInterface $actionLog): void
    {
        try {
            /** @var ActionLog $actionLog */
            $this->actionLogResource->delete($actionLog);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(
                __($exception->getMessage())
            );
        }
    }
}
