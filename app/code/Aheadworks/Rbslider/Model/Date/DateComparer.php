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
namespace Aheadworks\Rbslider\Model\Date;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Aheadworks\Rbslider\Cron\RbsCacheProcessor;

/**
 * Class DateComparer
 * @package Aheadworks\Rbslider\Model\Date
 */
class DateComparer
{
    const COMPARE_TIME_FORMAT = 'Y-m-d H:i';

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @param DateTime $dateTime
     */
    public function __construct(
        DateTime $dateTime
    ) {
        $this->dateTime = $dateTime;
    }

    /**
     * Sort Date
     *
     * @param array $dateList
     * @return array
     */
    public function sortDate($dateList)
    {
        if (!empty($dateList)) {
            usort($dateList, [$this,'compareDate']);
            return $dateList;
        }

        return [];
    }

    /**
     * Compare two dates
     *
     * @param string $date1
     * @param string $date2
     * @return bool
     */
    private function compareDate($date1, $date2)
    {
        return $date1 <=> $date2;
    }

    /**
     * Compare current Date with date from displayFrom or displayTo
     *
     * @param string $currentDate
     * @param string $displayDate
     * @return bool
     */
    public function checkDisplayDate($currentDate, $displayDate)
    {
        if (!empty($displayDate)) {
            $date = $this->dateTime->timestamp($displayDate);
            if (($currentDate - $date) >= 0 && ($currentDate - $date) <= RbsCacheProcessor::RUN_INTERVAL) {
                return true;
            }
        }

        return false;
    }

    /**
     * Compare current Date with displayTo Date
     *
     * @param string $currentDate
     * @param string $displayToDate
     * @return bool
     */
    public function checkDisplayToDate($currentDate, $displayToDate)
    {
        if (!empty($displayToDate)) {
            $date = $this->dateTime->timestamp($displayToDate);
            if ($currentDate > $date) {
                return true;
            }
        }

        return false;
    }
}
