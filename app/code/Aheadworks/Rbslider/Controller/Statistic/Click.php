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

namespace Aheadworks\Rbslider\Controller\Statistic;

use Aheadworks\Rbslider\Api\ActionLogManagementInterface;
use Aheadworks\Rbslider\Model\Statistic\SessionManager as StatisticSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json as ResultJson;
use Magento\Framework\Controller\Result\Redirect as ResultRedirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Click
 * @package Aheadworks\Rbslider\Controller\Statistic
 */
class Click implements HttpPostActionInterface
{
    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var StatisticSession
     */
    private $statisticSession;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ActionLogManagementInterface
     */
    private $actionLogManagement;

    /**
     * @param ResultFactory $resultFactory
     * @param CustomerSession $customerSession
     * @param StatisticSession $statisticSession
     * @param RequestInterface $request
     * @param ActionLogManagementInterface $actionLogManagement
     */
    public function __construct(
        ResultFactory $resultFactory,
        CustomerSession $customerSession,
        StatisticSession $statisticSession,
        RequestInterface $request,
        ActionLogManagementInterface $actionLogManagement
    ) {
        $this->resultFactory = $resultFactory;
        $this->customerSession = $customerSession;
        $this->statisticSession = $statisticSession;
        $this->request = $request;
        $this->actionLogManagement = $actionLogManagement;
    }

    /**
     * Update clicks statistics
     *
     * @return ResultInterface
     */
    public function execute()
    {
        if (!$this->request->isAjax()) {
            /** @var ResultRedirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setRefererOrBaseUrl();
        }

        try {
            $bannerId = (int) $this->request->getParam('banner_id');
            $slideId = (int) $this->request->getParam('slide_id');

            if ($bannerId && $slideId) {
                if ($this->statisticSession->isUniqueAction($bannerId, $slideId, 'click')) {
                    $customerId = (int) $this->customerSession->getCustomerId() ?: null;
                    $this->actionLogManagement->addClick($bannerId, $slideId, $customerId);
                }
            }
        } catch (LocalizedException $e) {
        }

        /** @var ResultJson $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        return $resultJson->setData([]);
    }
}
