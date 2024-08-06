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
namespace Aheadworks\Rbslider\Controller\Adminhtml\Slide;

use Aheadworks\Rbslider\Api\SlideRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Class Delete
 * @package Aheadworks\Rbslider\Controller\Adminhtml\Slide
 */
class Delete extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Rbslider::slides';

    /**
     * @var SlideRepositoryInterface
     */
    private $slideRepository;

    /**
     * @param Context $context
     * @param SlideRepositoryInterface $slideRepository
     */
    public function __construct(
        Context $context,
        SlideRepositoryInterface $slideRepository
    ) {
        parent::__construct($context);
        $this->slideRepository = $slideRepository;
    }

    /**
     * Delete slide action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = (int)$this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->slideRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('Slide was successfully deleted'));
                return $resultRedirect->setPath('*/*/index');
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            }
        }
        $this->messageManager->addErrorMessage(__('Slide could not be deleted'));
        return $resultRedirect->setPath('*/*/index');
    }
}
