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

namespace Aheadworks\Rbslider\Controller\Adminhtml\Statistic\Report;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page as ResultPage;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Rbslider::statistic_reports';

    /**
     * Run Statistic Reports controller
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var ResultPage $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage
            ->setActiveMenu('Aheadworks_Rbslider::statistic_reports')
            ->getConfig()->getTitle()->prepend(__('Statistic Reports'));

        return $resultPage;
    }
}
