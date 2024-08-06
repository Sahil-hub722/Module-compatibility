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

use Aheadworks\Rbslider\Model\Indexer\Banner\AbstractAction;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Row
 * @package Aheadworks\Rbslider\Model\Indexer\Banner\Action
 */
class Row extends AbstractAction
{
    /**
     * Execute Row reindex
     *
     * @param int|null $id
     * @return void
     * @throws InputException
     * @throws LocalizedException
     */
    public function execute($id = null)
    {
        if (!isset($id) || empty($id)) {
            throw new InputException(
                __('We can\'t rebuild the index for an undefined entity.')
            );
        }
        try {
            $this->bannerProductIndexer->reindexRows([$id]);
        } catch (\Exception $e) {
            throw new LocalizedException(__($e->getMessage()), $e);
        }
    }
}
