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
namespace Aheadworks\Rbslider\Controller\Adminhtml\Banner;

use Aheadworks\Rbslider\Api\BannerRepositoryInterface;
use Aheadworks\Rbslider\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class AbstractMassAction
 * @package Aheadworks\Rbslider\Controller\Adminhtml\Banner
 */
abstract class AbstractMassAction extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Rbslider::banners';

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var BannerRepositoryInterface
     */
    protected $bannerRepository;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     * @param BannerRepositoryInterface $bannerRepository
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        Filter $filter,
        BannerRepositoryInterface $bannerRepository
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
        $this->bannerRepository = $bannerRepository;
    }

    /**
     * Run mass action
     *
     * @return $this
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $this->massAction($collection);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/index');
        return $resultRedirect;
    }

    /**
     * Performs mass action
     *
     * @param CollectionFactory $collection
     * @return void
     */
    abstract protected function massAction($collection);
}
