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
use Magento\Framework\App\RequestInterface;
use Aheadworks\Rbslider\Model\Banner\ValidatorInterface;

/**
 * Class Category
 * @package Aheadworks\Rbslider\Model\Banner\Validator
 */
class Category implements ValidatorInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * {@inheritDoc}
     */
    public function canShow(BannerInterface $banner)
    {
        $currentCategoryId = $this->request->getParam('id');
        if ($currentCategoryId
            && (!$banner->getCategoryIds()
                || in_array($currentCategoryId, explode(',', (string)$banner->getCategoryIds())))
        ) {
            return true;
        }

        return false;
    }
}
