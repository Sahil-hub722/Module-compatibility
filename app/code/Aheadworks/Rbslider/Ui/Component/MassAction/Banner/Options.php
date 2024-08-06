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

namespace Aheadworks\Rbslider\Ui\Component\MassAction\Banner;

use Magento\Framework\UrlInterface;
use Zend\Stdlib\JsonSerializable;
use Aheadworks\Rbslider\Model\ResourceModel\Banner\Collection;

/**
 * Class Options
 * @package Aheadworks\Rbslider\Ui\Component\MassAction\Banner
 */
class Options implements JsonSerializable
{
    /**
     * @var array
     */
    private $options;

    /**
     * Additional options params
     *
     * @var array
     */
    private $data;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * Base URL for subactions
     *
     * @var string
     */
    private $urlPath;

    /**
     * Param name for subactions
     *
     * @var string
     */
    private $paramName;

    /**
     * Additional params for subactions
     *
     * @var array
     */
    private $additionalData = [];

    /**
     * @var Collection
     */
    private $collection;

    /**
     * @param UrlInterface $urlBuilder
     * @param Collection $collection
     * @param array $data
     */
    public function __construct(
        UrlInterface $urlBuilder,
        Collection $collection,
        array $data = []
    ) {
        $this->data = $data;
        $this->urlBuilder = $urlBuilder;
        $this->collection = $collection;
    }

    /**
     * Get action options
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        if ($this->options === null) {
            $options = $this->collection->toOptionArray();
            $this->prepareData();
            foreach ($options as $optionCode) {
                $this->options[$optionCode['value']] = [
                    'type' => $this->data['type'] . $optionCode['value'],
                    'label' => $optionCode['label'],
                ];

                if ($this->urlPath && $this->paramName) {
                    $this->options[$optionCode['value']]['url'] = $this->urlBuilder->getUrl(
                        $this->urlPath,
                        [$this->paramName => $optionCode['value']]
                    );
                }

                $this->options[$optionCode['value']] = array_merge_recursive(
                    $this->options[$optionCode['value']],
                    $this->additionalData
                );
            }

            $this->options = array_values($this->options);
        }

        return $this->options;
    }

    /**
     * Prepare addition data for subactions
     *
     * @return void
     */
    protected function prepareData(): void
    {
        foreach ($this->data as $key => $value) {
            switch ($key) {
                case 'urlPath':
                    $this->urlPath = $value;
                    break;
                case 'paramName':
                    $this->paramName = $value;
                    break;
                default:
                    $this->additionalData[$key] = $value;
                    break;
            }
        }
    }
}
