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

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 * Class ProductLoader
 * @package Aheadworks\Rbslider\Model\Indexer\Banner
 */
class ProductLoader
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Get products by ids
     *
     * @param array $productIds
     * @return ProductInterface[]
     */
    public function getProducts($productIds)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('entity_id', $productIds, 'in')
            ->create();

        return $this->productRepository->getList($searchCriteria)->getItems();
    }
}
