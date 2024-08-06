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

namespace Aheadworks\Rbslider\Controller\Adminhtml\Statistic;

use Aheadworks\Rbslider\Api\Data\ActionLogInterface;
use Aheadworks\Rbslider\Model\ResourceModel\ActionLog as ActionLogResource;
use Aheadworks\Rbslider\Model\ResourceModel\ActionLog\CollectionFactory as ActionLogCollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Ui\Component\MassAction\Filter as MassActionFilter;

/**
 * Class MassReset
 * @package Aheadworks\Rbslider\Controller\Adminhtml\Statistic
 */
class MassReset extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Rbslider::statistics';

    /**
     * @var MassActionFilter
     */
    private $massActionFilter;

    /**
     * @var ActionLogResource
     */
    private $actionLogResource;

    /**
     * @var ActionLogCollectionFactory
     */
    private $actionLogCollectionFactory;

    /**
     * @param Context $context
     * @param MassActionFilter $massActionFilter
     * @param ActionLogResource $actionLogResource
     * @param ActionLogCollectionFactory $actionLogCollectionFactory
     */
    public function __construct(
        Context $context,
        MassActionFilter $massActionFilter,
        ActionLogResource $actionLogResource,
        ActionLogCollectionFactory $actionLogCollectionFactory
    ) {
        parent::__construct($context);

        $this->massActionFilter = $massActionFilter;
        $this->actionLogResource = $actionLogResource;
        $this->actionLogCollectionFactory = $actionLogCollectionFactory;
    }

    /**
     * Reset mass action execution
     *
     * @return ResultInterface
     */
    public function execute()
    {
        try {
            /** @var AbstractCollection $actionLogCollection */
            $actionLogCollection = $this->massActionFilter->getCollection(
                $this->actionLogCollectionFactory->create()
            );

            /** @var ActionLogInterface $actionLog */
            foreach ($actionLogCollection as $actionLog) {
                $this->actionLogResource->deleteMultiple(
                    $actionLog->getBannerId(),
                    $actionLog->getSlideId()
                );
            }

            $this->getMessageManager()->addSuccessMessage(
                __('A total of %1 record(s) have been removed', $actionLogCollection->count())->render()
            );
        } catch (\Exception $e) {
            $this->getMessageManager()->addErrorMessage($e->getMessage());
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index');
    }
}
