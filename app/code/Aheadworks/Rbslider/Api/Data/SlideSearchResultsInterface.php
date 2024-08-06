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
 * Interface for slide search results.
 * @api
 */
interface SlideSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get slides list.
     *
     * @return \Aheadworks\Rbslider\Api\Data\SlideInterface[]
     */
    public function getItems();

    /**
     * Set slides list.
     *
     * @param \Aheadworks\Rbslider\Api\Data\SlideInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
