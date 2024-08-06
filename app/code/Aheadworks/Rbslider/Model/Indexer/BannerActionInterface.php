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
namespace Aheadworks\Rbslider\Model\Indexer;

use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Magento\Framework\Indexer\ActionInterface as IndexerActionInterface;

/**
 * Interface BannerActionInterface
 * @package Aheadworks\Rbslider\Model\Indexer
 */
interface BannerActionInterface extends IndexerActionInterface
{
    /**
     * Execute partial indexation by banner
     *
     * @param BannerInterface $banner
     * @return void
     */
    public function executeRowsForBanner($banner);
}
