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

namespace Aheadworks\Rbslider\Model\Source;

use Aheadworks\Rbslider\Model\ThirdPartyModule\Manager;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class CustomerSegments
 * @package Aheadworks\Rbslider\Model\Source
 */
class CustomerSegments implements OptionSourceInterface
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var Manager
     */
    private $moduleManager;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param Manager $moduleManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        Manager $moduleManager
    ) {
        $this->objectManager = $objectManager;
        $this->moduleManager = $moduleManager;
    }

    /**
     * {@inheritDoc}
     */
    public function toOptionArray()
    {
        $options = [];

        if ($this->moduleManager->isCustomerSegmentationModuleEnabled()) {
            $options = $this->getSegmentsSource()->toOptionArray();
        }

        return $options;
    }

    /**
     * Retrieve segments source model
     *
     * @return \Aheadworks\CustomerSegmentation\Model\Config\Source\Segments
     */
    private function getSegmentsSource()
    {
        return $this->objectManager->create(
            \Aheadworks\CustomerSegmentation\Model\Config\Source\Segments::class
        );
    }
}
