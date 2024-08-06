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
namespace Aheadworks\Rbslider\Model\Indexer\Banner;

use Aheadworks\Rbslider\Model\ResourceModel\Indexer\BannerProduct as BannerProductIndexer;

/**
 * Class AbstractAction
 * @package Aheadworks\Rbslider\Model\Indexer\Banner
 */
abstract class AbstractAction
{
    /**
     * @var BannerProductIndexer
     */
    protected $bannerProductIndexer;

    /**
     * @var Processor
     */
    protected $processor;

    /**
     * @param BannerProductIndexer $bannerProductIndexer
     * @param Processor $processor
     */
    public function __construct(
        BannerProductIndexer $bannerProductIndexer,
        Processor $processor
    ) {
        $this->bannerProductIndexer = $bannerProductIndexer;
        $this->processor = $processor;
    }

    /**
     * Execute action for given ids
     *
     * @param array|int $ids
     * @return void
     */
    abstract public function execute($ids);
}
