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
namespace Aheadworks\Rbslider\Model\Banner\Validator;

use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Model\ResourceModel\Banner as BannerResource;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Rbslider\Model\Banner\ValidatorInterface;

/**
 * Class Validator
 * @package Aheadworks\Rbslider\Model\Rule
 */
class Product implements ValidatorInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var BannerResource
     */
    private $bannerResource;

    /**
     * @param RequestInterface $request
     * @param BannerResource $bannerResource
     */
    public function __construct(
        RequestInterface $request,
        BannerResource $bannerResource
    ) {
        $this->request = $request;
        $this->bannerResource = $bannerResource;
    }

    /**
     * {@inheritDoc}
     */
    public function canShow(BannerInterface $banner)
    {
        $currentProductId = $this->request->getParam('id');
        if (!$currentProductId) {
            return false;
        }

        return $this->bannerResource->isBannerAvailableForProduct($currentProductId, $banner->getId());
    }
}
