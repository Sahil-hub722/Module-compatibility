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

namespace Aheadworks\Rbslider\Ui\Component\Listing\Columns;

use Magento\Ui\Component\Listing\Columns\Column;

class Customer extends Column
{
    /**
     * Prepare data source for the customer column
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $fieldName = $this->getName();
        foreach ($dataSource['data']['items'] as &$item) {
            $itemValue = $item['customer_id'];

            if ($itemValue) {
                $item[$fieldName . '_url'] = $this->getContext()->getUrl(
                    'customer/index/edit',
                    ['id' => $itemValue]
                );
            } else {
                $item[$fieldName] = __('Guest');
            }
        }

        return $dataSource;
    }
}
