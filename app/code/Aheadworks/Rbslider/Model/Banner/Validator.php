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
namespace Aheadworks\Rbslider\Model\Banner;

use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Model\Banner;

/**
 * Class Validator
 * @package Aheadworks\Rbslider\Model\Rule
 */
class Validator
{
    /**
     * @var ValidatorInterface[]
     */
    private $validators;

    /**
     * @param array $validators
     */
    public function __construct(
        array $validators = []
    ) {
        $this->validators = $validators;
    }

    /**
     * Check can show banner
     *
     * @param BannerInterface|Banner $banner
     * @return bool
     */
    public function canShow(BannerInterface $banner)
    {
        $pageType = $banner->getPageType();
        $validator = isset($this->validators[$pageType]) ? $this->validators[$pageType] : null;

        if (!$validator) {
            return true;
        }

        return $validator instanceof ValidatorInterface
            ? $validator->canShow($banner)
            : false;
    }
}
