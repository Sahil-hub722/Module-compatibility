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
namespace Aheadworks\Rbslider\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for Rbslider block search results
 *
 * @api
 */
interface BlockSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get blocks list
     *
     * @return \Aheadworks\Rbslider\Api\Data\BlockInterface[]
     */
    public function getItems();

    /**
     * Set blocks list
     *
     * @param \Aheadworks\Rbslider\Api\Data\BlockInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
