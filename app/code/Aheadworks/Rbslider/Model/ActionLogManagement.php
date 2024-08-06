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

use Aheadworks\Rbslider\Api\ActionLogManagementInterface;
use Aheadworks\Rbslider\Api\ActionLogRepositoryInterface;
use Aheadworks\Rbslider\Api\Data\ActionLogInterface;
use Aheadworks\Rbslider\Api\Data\ActionLogInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;

class ActionLogManagement implements ActionLogManagementInterface
{
    /**
     * @var ActionLogInterfaceFactory
     */
    private $actionLogFactory;

    /**
     * @var ActionLogRepositoryInterface
     */
    private $actionLogRepository;

    /**
     * @param ActionLogInterfaceFactory $actionLogFactory
     * @param ActionLogRepositoryInterface $actionLogRepository
     */
    public function __construct(
        ActionLogInterfaceFactory $actionLogFactory,
        ActionLogRepositoryInterface $actionLogRepository
    ) {
        $this->actionLogFactory = $actionLogFactory;
        $this->actionLogRepository = $actionLogRepository;
    }

    /**
     * Save slide banner view to the action log
     *
     * @param int $bannerId
     * @param int $slideId
     * @param int|null $customerId
     * @return void
     * @throws CouldNotSaveException
     */
    public function addView(int $bannerId, int $slideId, ?int $customerId): void
    {
        $actionType = ActionLogInterface::ACTION_TYPE_VIEW;
        $this->addAction($bannerId, $slideId, $actionType, $customerId);
    }

    /**
     * Save slide banner click to the action log
     *
     * @param int $bannerId
     * @param int $slideId
     * @param int|null $customerId
     * @return void
     * @throws CouldNotSaveException
     */
    public function addClick(int $bannerId, int $slideId, ?int $customerId): void
    {
        $actionType = ActionLogInterface::ACTION_TYPE_CLICK;
        $this->addAction($bannerId, $slideId, $actionType, $customerId);
    }

    /**
     * Save slide banner action to the action log
     *
     * @param int $bannerId
     * @param int $slideId
     * @param int $actionType
     * @param int|null $customerId
     * @return void
     * @throws CouldNotSaveException
     */
    private function addAction(int $bannerId, int $slideId, int $actionType, ?int $customerId): void
    {
        $actionLog = $this->actionLogFactory->create()
            ->setSlideId($slideId)
            ->setBannerId($bannerId)
            ->setActionType($actionType);

        if ($customerId) {
            $actionLog->setCustomerId($customerId);
        }

        $this->actionLogRepository->save($actionLog);
    }
}
