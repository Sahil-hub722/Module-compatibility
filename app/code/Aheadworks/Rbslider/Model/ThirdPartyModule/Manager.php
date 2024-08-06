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
namespace Aheadworks\Rbslider\Model\ThirdPartyModule;

use Magento\Framework\Module\ModuleListInterface;

/**
 * Class Manager
 * @package Aheadworks\Rbslider\Model\ThirdPartyModule
 */
class Manager
{
    /**
     * Aheadworks Blog module name
     */
    const AW_BLOG_MODULE_NAME = 'Aheadworks_Blog';

    /**
     * Customer Segmentation module name
     */
    const CUSTOMER_SEGMENTATION_MODULE_NAME = 'Aheadworks_CustomerSegmentation';

    /**
     * @var ModuleListInterface
     */
    private $moduleList;

    /**
     * @param ModuleListInterface $moduleList
     */
    public function __construct(
        ModuleListInterface $moduleList
    ) {
        $this->moduleList = $moduleList;
    }

    /**
     * Check if Aheadworks Blog module enabled
     *
     * @return bool
     */
    public function isAwBlogEnabled()
    {
        return $this->moduleList->has(self::AW_BLOG_MODULE_NAME);
    }

    /**
     * Check if Customer Segmentation module enabled
     *
     * @return bool
     */
    public function isCustomerSegmentationModuleEnabled()
    {
        return $this->moduleList->has(self::CUSTOMER_SEGMENTATION_MODULE_NAME);
    }
}
