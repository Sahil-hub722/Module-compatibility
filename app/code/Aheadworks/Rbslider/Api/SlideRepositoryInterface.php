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

/**
 * Slide CRUD interface.
 * @api
 */
interface SlideRepositoryInterface
{
    /**
     * Save slide.
     *
     * @param \Aheadworks\Rbslider\Api\Data\SlideInterface $slide
     * @return \Aheadworks\Rbslider\Api\Data\SlideInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Aheadworks\Rbslider\Api\Data\SlideInterface $slide);

    /**
     * Retrieve slide.
     *
     * @param int $slideId
     * @return \Aheadworks\Rbslider\Api\Data\SlideInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($slideId);

    /**
     * Retrieve slides matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\Rbslider\Api\Data\SlideSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete slide.
     *
     * @param \Aheadworks\Rbslider\Api\Data\SlideInterface $slide
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Aheadworks\Rbslider\Api\Data\SlideInterface $slide);

    /**
     * Delete slide by ID.
     *
     * @param int $slideId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($slideId);
}
