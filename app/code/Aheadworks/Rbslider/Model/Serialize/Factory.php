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
namespace Aheadworks\Rbslider\Model\Serialize;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\ProductMetadataInterface;

/**
 * Class Factory
 * @package Aheadworks\Rbslider\Model\Serialize
 */
class Factory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param ProductMetadataInterface $productMetadata
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        ProductMetadataInterface $productMetadata
    ) {
        $this->objectManager = $objectManager;
        $this->productMetadata = $productMetadata;
    }

    /**
     * Create serializer instance
     *
     * @return SerializeInterface
     */
    public function create()
    {
        $magentoVersion = $this->productMetadata->getVersion();
        $serializerInterface = version_compare($magentoVersion, '2.2.0', '>=')
            ? \Magento\Framework\Serialize\SerializerInterface::class
            : SerializeInterface::class;

        return $this->objectManager->create($serializerInterface);
    }
}
