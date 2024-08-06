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
namespace Aheadworks\Rbslider\Model\Indexer\Banner\Action;

use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Model\Indexer\Banner\AbstractAction;
use Aheadworks\Rbslider\Model\Banner;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class RowBanner
 * @package Aheadworks\Rbslider\Model\Indexer\Banner\Action
 */
class RowBanner extends AbstractAction
{
    /**
     * Execute Row reindex for banner
     *
     * @param BannerInterface|Banner $banner
     * @return void
     * @throws LocalizedException
     */
    public function execute($banner)
    {
        try {
            if ($this->processor->isIndexerScheduled()) {
                $this->processor->markIndexerAsInvalid();
            } else {
                $banner->afterLoad();
                $this->bannerProductIndexer->reindexForBanner($banner);
            }
        } catch (\Exception $e) {
            throw new LocalizedException(__($e->getMessage()), $e);
        }
    }
}
