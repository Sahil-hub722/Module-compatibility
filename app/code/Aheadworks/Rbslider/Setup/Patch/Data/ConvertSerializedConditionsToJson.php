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
namespace Aheadworks\Rbslider\Setup\Patch\Data;

use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Model\Serialize\SerializeInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Aheadworks\Rbslider\Model\Serialize\Factory as SerializeFactory;
use Magento\Framework\Serialize\Serializer\Serialize;

/**
 * Class ConvertSerializedConditionsToJson
 */
class ConvertSerializedConditionsToJson implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var SerializeInterface
     */
    private $serializer;

    /**
     * @var Serialize
     */
    private $phpSerializer;

    /**
     * ConvertSerializedConditionsToJson constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param SerializeFactory $serializeFactory
     * @param Serialize $phpSerializer
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        SerializeFactory $serializeFactory,
        Serialize $phpSerializer
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->serializer = $serializeFactory->create();
        $this->phpSerializer = $phpSerializer;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Convert Serialized Conditions To Json
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $connection = $this->moduleDataSetup->getConnection();
        $select = $connection->select()->from(
            $this->moduleDataSetup->getTable('aw_rbslider_banner'),
            [
                BannerInterface::ID,
                BannerInterface::PRODUCT_CONDITION,
            ]
        );
        $rulesConditions = $connection->fetchAssoc($select);
        foreach ($rulesConditions as $ruleConditions) {
            $unsrProductCond = $this->unserialize($ruleConditions[BannerInterface::PRODUCT_CONDITION]);
            if ($unsrProductCond !== false) {
                $ruleConditions[BannerInterface::PRODUCT_CONDITION] = empty($unsrProductCond)
                    ? ''
                    : $this->serializer->serialize($unsrProductCond);

                $connection->update(
                    $this->moduleDataSetup->getTable('aw_rbslider_banner'),
                    [
                        BannerInterface::PRODUCT_CONDITION => $ruleConditions[BannerInterface::PRODUCT_CONDITION]
                    ],
                    BannerInterface::ID . ' = ' . $ruleConditions[BannerInterface::ID]
                );
            }
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Unserialize string with unserialize method
     *
     * @param $string
     * @return array|bool
     */
    private function unserialize($string)
    {
        $result = '';
        if (!empty($string)) {
            try {
                $result = $this->phpSerializer->unserialize($string);
            } catch (\Exception $e) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public static function getVersion()
    {
        return '1.2.0';
    }
}
