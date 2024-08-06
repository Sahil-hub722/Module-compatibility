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
namespace Aheadworks\Rbslider\Model\Sample\Reader;

/**
 * Class Xml
 * @package Aheadworks\Rbslider\Model\Sample\Reader
 */
class Xml extends \Magento\Framework\Config\Reader\Filesystem
{
    /**
     * @param \Magento\Framework\Config\FileResolverInterface $fileResolver
     * @param \Aheadworks\Rbslider\Model\Sample\Converter\Xml $converter
     * @param \Aheadworks\Rbslider\Model\Sample\SchemaLocator $schemaLocator
     * @param \Magento\Framework\Config\ValidationStateInterface $validationState
     * @param string $fileName
     * @param array $idAttributes
     * @param string $domDocumentClass
     * @param string $defaultScope
     */
    public function __construct(
        \Magento\Framework\Config\FileResolverInterface $fileResolver,
        \Aheadworks\Rbslider\Model\Sample\Converter\Xml $converter,
        \Aheadworks\Rbslider\Model\Sample\SchemaLocator $schemaLocator,
        \Magento\Framework\Config\ValidationStateInterface $validationState,
        $fileName = 'aw_rbslider_sample_data.xml',
        $idAttributes = [],
        $domDocumentClass = \Magento\Framework\Config\Dom::class,
        $defaultScope = 'global'
    ) {
        parent::__construct(
            $fileResolver,
            $converter,
            $schemaLocator,
            $validationState,
            $fileName,
            $idAttributes,
            $domDocumentClass,
            $defaultScope
        );
    }
}
