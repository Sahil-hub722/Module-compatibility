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
namespace Aheadworks\Rbslider\Model\Cache;

use Magento\PageCache\Model\Cache\Type;
use Zend_Cache;

/**
 * Class CacheProcessor
 * @package Aheadworks\Rbslider\Model\Cache
 */
class CacheProcessor
{
    /**
     * @var Type
     */
    private $pageCache;

    /**
     * @param Type $pageCache
     */
    public function __construct(
        Type $pageCache
    ) {
        $this->pageCache = $pageCache;
    }

    /**
     * Clean cache for some slides
     *
     * @param string[] $identities
     * @return void
     */
    public function cleanCache($identities)
    {
        if (!empty($identities)) {
            $this->pageCache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, $identities);
        }
    }
}
