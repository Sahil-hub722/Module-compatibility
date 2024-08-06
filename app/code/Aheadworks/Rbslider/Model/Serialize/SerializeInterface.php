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
namespace Aheadworks\Rbslider\Model\Serialize;

/**
 * Interface SerializeInterface
 * @package Aheadworks\Rbslider\Model\Serialize
 */
interface SerializeInterface
{
    /**
     * Serialize data into string
     *
     * @param mixed $data
     * @return string|bool
     * @throws \InvalidArgumentException
     */
    public function serialize($data);

    /**
     * Unserialize the given string
     *
     * @param string $string
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function unserialize($string);
}
