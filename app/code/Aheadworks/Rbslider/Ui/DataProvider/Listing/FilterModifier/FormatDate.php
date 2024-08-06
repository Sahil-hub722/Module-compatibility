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

namespace Aheadworks\Rbslider\Ui\DataProvider\Listing\FilterModifier;

use Magento\Framework\Api\Filter;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class FormatDate implements FilterModifierInterface
{
    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        TimezoneInterface $timezone
    ) {
        $this->timezone = $timezone;
    }

    /**
     * Convert dates to the appropriate format before filter
     *
     * @param Filter $filter
     * @return void
     * @throws LocalizedException
     */
    public function modify(Filter $filter): void
    {
        $filter->setValue(
            $this->timezone->convertConfigTimeToUtc($filter->getValue())
        );
    }
}
