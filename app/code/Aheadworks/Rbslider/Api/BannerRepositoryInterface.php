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
namespace Aheadworks\Rbslider\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Banner CRUD interface
 * @api
 */
interface BannerRepositoryInterface
{
    /**
     * Save banner
     *
     * @param \Aheadworks\Rbslider\Api\Data\BannerInterface $banner
     * @return \Aheadworks\Rbslider\Api\Data\BannerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Aheadworks\Rbslider\Api\Data\BannerInterface $banner);

    /**
     * Retrieve banner
     *
     * @param int $bannerId
     * @return \Aheadworks\Rbslider\Api\Data\BannerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($bannerId);

    /**
     * Retrieve banners matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\Rbslider\Api\Data\BannerSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete banner
     *
     * @param \Aheadworks\Rbslider\Api\Data\BannerInterface $banner
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Aheadworks\Rbslider\Api\Data\BannerInterface $banner);

    /**
     * Delete banner by ID
     *
     * @param int $bannerId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($bannerId);
}
