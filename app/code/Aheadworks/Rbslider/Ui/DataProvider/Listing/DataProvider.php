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
declare(strict_types=1);

namespace Aheadworks\Rbslider\Ui\DataProvider\Listing;

use Aheadworks\Rbslider\Ui\DataProvider\Listing\FilterModifier\FilterModifierInterface;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider as UiDataProvider;

class DataProvider extends UiDataProvider
{
    /**
     * @var FilterModifierInterface[]
     */
    private $filterModifiers;

    /**
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $filterModifiers
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        string $name,
        string $primaryFieldName = 'id',
        string $requestFieldName = 'id',
        array $filterModifiers = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );

        $this->filterModifiers = $filterModifiers;
    }

    /**
     * Preprocessing for filters before applying
     *
     * @param Filter $filter
     * @return void
     */
    public function addFilter(Filter $filter)
    {
        $field = $filter->getField();
        if (isset($this->filterModifiers[$field])) {
            $this->filterModifiers[$field]->modify($filter);
        }

        parent::addFilter($filter);
    }
}
