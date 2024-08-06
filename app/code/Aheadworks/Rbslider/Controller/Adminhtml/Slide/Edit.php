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
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Edit
 * @package Aheadworks\Rbslider\Controller\Adminhtml\Slide
 */
class Edit extends Action implements HttpGetActionInterface
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
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @param Context $context
     * @param SlideRepositoryInterface $slideRepository
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        SlideRepositoryInterface $slideRepository,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->slideRepository = $slideRepository;
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Edit Slide
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->slideRepository->get($id);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('Something went wrong while editing the slide')
                );
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('*/*/index');
                return $resultRedirect;
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage
            ->setActiveMenu('Aheadworks_Rbslider::slides')
            ->getConfig()->getTitle()->prepend(
                $id ? __('Edit Slide') : __('New Slide')
            );

        return $resultPage;
    }
}
