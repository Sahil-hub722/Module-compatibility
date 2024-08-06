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

use Magento\Framework\Mview\ActionInterface as MviewActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\InputException;
use Aheadworks\Rbslider\Model\Indexer\Banner\Action\Row as ActionRow;
use Aheadworks\Rbslider\Model\Indexer\Banner\Action\RowBanner as ActionRowBanner;
use Aheadworks\Rbslider\Model\Indexer\Banner\Action\Rows as ActionRows;
use Aheadworks\Rbslider\Model\Indexer\Banner\Action\Full as ActionFull;

/**
 * Class Banner
 * @package Aheadworks\Rbslider\Model\Indexer
 */
class Banner implements BannerActionInterface, MviewActionInterface
{
    /**
     * @var ActionRow
     */
    private $indexerRow;

    /**
     * @var ActionRowBanner
     */
    private $indexerRowBanner;

    /**
     * @var ActionRows
     */
    private $indexerRows;

    /**
     * @var ActionFull
     */
    private $indexerFull;

    /**
     * @param ActionRow $indexerRow
     * @param ActionRowBanner $indexerRowBanner
     * @param ActionRows $indexerRows
     * @param ActionFull $indexerFull
     */
    public function __construct(
        ActionRow $indexerRow,
        ActionRowBanner $indexerRowBanner,
        ActionRows $indexerRows,
        ActionFull $indexerFull
    ) {
        $this->indexerRow = $indexerRow;
        $this->indexerRowBanner = $indexerRowBanner;
        $this->indexerRows = $indexerRows;
        $this->indexerFull = $indexerFull;
    }

    /**
     * {@inheritdoc}
     * @throws InputException
     * @throws LocalizedException
     */
    public function execute($ids)
    {
        $this->indexerRows->execute($ids);
    }

    /**
     * {@inheritdoc}
     * @throws LocalizedException
     */
    public function executeFull()
    {
        $this->indexerFull->execute();
    }
    
    /**
     * {@inheritdoc}
     * @throws InputException
     * @throws LocalizedException
     */
    public function executeList(array $ids)
    {
        $this->indexerRows->execute($ids);
    }
    
    /**
     * {@inheritdoc}
     * @throws InputException
     * @throws LocalizedException
     */
    public function executeRow($id)
    {
        $this->indexerRow->execute($id);
    }

    /**
     * {@inheritdoc}
     * @throws LocalizedException
     */
    public function executeRowsForBanner($banner)
    {
        $this->indexerRowBanner->execute($banner);
    }
}
