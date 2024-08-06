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

namespace Aheadworks\Rbslider\Controller\Cache;

use Aheadworks\Rbslider\Model\Banner as ModelBanner;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json as ResultJson;
use Magento\Framework\Controller\Result\Redirect as ResultRedirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\PageCache\Model\Cache\Type;
use Zend_Cache;

/**
 * Class Clean
 * @package Aheadworks\Rbslider\Controller\Cache
 */
class Clean implements HttpPostActionInterface
{
    /**
     * @var Type
     */
    private $pageCache;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param Type $pageCache
     * @param ResultFactory $resultFactory
     * @param RequestInterface $request
     */
    public function __construct(
        Type $pageCache,
        ResultFactory $resultFactory,
        RequestInterface $request
    ) {
        $this->pageCache = $pageCache;
        $this->resultFactory = $resultFactory;
        $this->request = $request;
    }

    /**
     * Clean cache for banner
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

        $bannerId = $this->request->getParam('bannerId');
        if (!empty($bannerId)) {
            $this->pageCache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array_unique([ModelBanner::CACHE_TAG . '_' . $bannerId]));
        }

        /** @var ResultJson $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        return $resultJson->setData([]);
    }
}
