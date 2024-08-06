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

namespace Aheadworks\Rbslider\Model\Statistic;

use Magento\Framework\Session\SessionManager as MagentoSessionManager;

/**
 * Class SessionManager
 * @package Aheadworks\Rbslider\Model\Statistic
 */
class SessionManager
{
    /**
     * Lifetime for slides
     */
    const LIFETIME_SLIDE_ACTION = 86400; //24h

    /**
     * @var array
     */
    private $slidesArray;

    /**
     * @var MagentoSessionManager
     */
    private $magentoSessionManager;

    /**
     * @param MagentoSessionManager $magentoSessionManager
     */
    public function __construct(
        MagentoSessionManager $magentoSessionManager
    ) {
        $this->magentoSessionManager = $magentoSessionManager;
    }

    /**
     * Get slides from session
     *
     * @return array
     */
    private function getSlidesAction()
    {
        if (null === $this->slidesArray) {
            $this->slidesArray = $this->magentoSessionManager->getData();

            if (is_array($this->slidesArray) && count($this->slidesArray)) {
                // Check and remove old slides from array
                foreach ($this->slidesArray as $key => $expireTime) {
                    if ($expireTime <= time()) {
                        unset($this->slidesArray[$key]);
                    }
                }
            } else {
                $this->slidesArray = [];
            }
        }

        return $this->slidesArray;
    }

    /**
     * Is set slide name in session
     *
     * @param string $name
     * @return bool
     */
    public function isSetSlideAction($name)
    {
        $slidesArray = $this->getSlidesAction();
        if (is_array($slidesArray) && isset($slidesArray[$name])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Add name and expire time to array
     *
     * @param string $name
     * @return $this
     */
    public function addSlideAction(string $name): self
    {
        $this->slidesArray[$name] = self::LIFETIME_SLIDE_ACTION + time();

        return $this;
    }

    /**
     * Save data in session
     *
     * @return $this
     */
    public function save()
    {
        $this->magentoSessionManager->setData($this->slidesArray);

        return $this;
    }

    /**
     * Check if the action is unique and needs to be processed
     *
     * @param int $bannerId
     * @param int $slideId
     * @param string $actionType
     * @return bool
     */
    public function isUniqueAction(int $bannerId, int $slideId, string $actionType): bool
    {
        $sessionName = sprintf('slide_%s_%d_%d', $actionType, $bannerId, $slideId);
        if ($this->isSetSlideAction($sessionName)) {
            return false;
        }

        $this->addSlideAction($sessionName)->save();

        return true;
    }
}
