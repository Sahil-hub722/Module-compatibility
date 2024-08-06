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
namespace Aheadworks\Rbslider\Model;

/**
 * class Sample
 * @package Aheadworks\Rbslider\Model
 */
class Sample extends \Magento\Framework\Config\Data
{
    /**
     * @param \Aheadworks\Rbslider\Model\Sample\Reader\Xml $reader
     * @param \Magento\Framework\Config\CacheInterface $cache
     * @param string $cacheId
     */
    public function __construct(
        \Aheadworks\Rbslider\Model\Sample\Reader\Xml $reader,
        \Magento\Framework\Config\CacheInterface $cache,
        $cacheId = 'aheadworks_rbslider_sample_data_cache'
    ) {
        parent::__construct($reader, $cache, $cacheId);
    }
}
