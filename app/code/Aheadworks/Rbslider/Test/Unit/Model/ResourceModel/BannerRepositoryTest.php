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
namespace Aheadworks\Rbslider\Test\Unit\Model\ResourceModel;

use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Api\Data\BannerInterfaceFactory;
use Aheadworks\Rbslider\Api\Data\BannerSearchResultsInterface;
use Aheadworks\Rbslider\Api\Data\BannerSearchResultsInterfaceFactory;
use Aheadworks\Rbslider\Api\Data\ConditionInterface;
use Aheadworks\Rbslider\Model\Banner;
use Aheadworks\Rbslider\Model\BannerFactory;
use Aheadworks\Rbslider\Model\BannerRegistry;
use Aheadworks\Rbslider\Model\Converter\Condition as ConditionConverter;
use Aheadworks\Rbslider\Model\ResourceModel\Banner\Collection as BannerCollection;
use Aheadworks\Rbslider\Model\ResourceModel\BannerRepository;
use Aheadworks\Rbslider\Model\Serialize\Factory as SerializeFactory;
use Aheadworks\Rbslider\Model\Serialize\SerializeInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Rbslider\Model\ResourceModel\BannerRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BannerRepositoryTest extends TestCase
{
    /**
     * @var BannerRepository
     */
    private $model;

    /**
     * @var EntityManager|MockObject
     */
    private $entityManagerMock;

    /**
     * @var BannerFactory|MockObject
     */
    private $bannerFactoryMock;

    /**
     * @var BannerInterfaceFactory|MockObject
     */
    private $bannerDataFactoryMock;

    /**
     * @var BannerRegistry|MockObject
     */
    private $bannerRegistryMock;

    /**
     * @var BannerSearchResultsInterfaceFactory|MockObject
     */
    private $searchResultsFactoryMock;

    /**
     * @var DataObjectHelper|MockObject
     */
    private $dataObjectHelperMock;

    /**
     * @var DataObjectProcessor|MockObject
     */
    private $dataObjectProcessorMock;

    /**
     * @var JoinProcessorInterface|MockObject
     */
    private $extensionAttributesJoinProcessorMock;

    /**
     * @var ConditionConverter|MockObject
     */
    private $conditionConverterMock;

    /**
     * @var SerializeInterface|MockObject
     */
    private $serializerMock;

    /**
     * @var array
     */
    private $bannerData = [
        'id' => 1,
        'product_condition' => 'a:5:{s:4:"type";s:48:"Aheadworks\Rbslider\Model\Rule\Condition\Combine";'
            . 's:10:"conditions";a:1:{i:0;a:5:{s:4:"type";'
            . 's:59:"Aheadworks\Rbslider\Model\Rule\Condition\Product\Attributes";s:8:"operator";s:2:"==";'
            . 's:9:"attribute";s:12:"category_ids";s:5:"value";s:14:"20, 21, 23, 24";s:10:"value_type";N;}}'
            . 's:10:"aggregator";s:3:"all";s:5:"value";s:1:"1";s:10:"value_type";N;}',
    ];

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->entityManagerMock = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['load', 'delete', 'save'])
            ->getMock();
        $this->bannerFactoryMock = $this->getMockBuilder(BannerFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->bannerDataFactoryMock = $this->getMockBuilder(BannerInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->bannerRegistryMock = $this->getMockBuilder(BannerRegistry::class)
            ->disableOriginalConstructor()
            ->setMethods(['push', 'retrieve', 'remove'])
            ->getMock();
        $this->searchResultsFactoryMock = $this->getMockBuilder(BannerSearchResultsInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->dataObjectHelperMock = $this->getMockBuilder(DataObjectHelper::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();
        $this->dataObjectProcessorMock = $this->getMockBuilder(DataObjectProcessor::class)
            ->disableOriginalConstructor()
            ->setMethods(['buildOutputDataArray'])
            ->getMock();
        $this->extensionAttributesJoinProcessorMock = $this->getMockBuilder(JoinProcessorInterface::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();
        $this->conditionConverterMock = $this->getMockBuilder(ConditionConverter::class)
            ->disableOriginalConstructor()
            ->setMethods(['arrayToDataModel'])
            ->getMock();
        $this->serializerMock = $this->getMockForAbstractClass(SerializeInterface::class);
        $serializeFactoryMock = $this->createPartialMock(SerializeFactory::class, ['create']);
        $serializeFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->serializerMock);

        $this->model = $objectManager->getObject(
            BannerRepository::class,
            [
                'entityManager' => $this->entityManagerMock,
                'bannerFactory' => $this->bannerFactoryMock,
                'bannerDataFactory' => $this->bannerDataFactoryMock,
                'bannerRegistry' => $this->bannerRegistryMock,
                'dataObjectHelper' => $this->dataObjectHelperMock,
                'dataObjectProcessor' => $this->dataObjectProcessorMock,
                'searchResultsFactory' => $this->searchResultsFactoryMock,
                'extensionAttributesJoinProcessor' => $this->extensionAttributesJoinProcessorMock,
                'conditionConverter' => $this->conditionConverterMock,
                'serializeFactory' => $serializeFactoryMock
            ]
        );
    }

    /**
     * Testing of save method
     */
    public function testSave()
    {
        $bannerModelMock = $this->getMockBuilder(Banner::class)
            ->disableOriginalConstructor()
            ->setMethods(['beforeSave'])
            ->getMock();
        $this->entityManagerMock->expects($this->once())
            ->method('save')
            ->with($bannerModelMock);
        $this->bannerRegistryMock->expects($this->once())
            ->method('push')
            ->with($bannerModelMock);

        $this->assertSame($bannerModelMock, $this->model->save($bannerModelMock));
    }

    /**
     * Testing of get method
     */
    public function testGet()
    {
        $bannerMock = $this->getMockForAbstractClass(BannerInterface::class);
        $bannerMock->expects($this->once())
            ->method('getId')
            ->willReturn($this->bannerData['id']);
        $bannerMock->expects($this->exactly(2))
            ->method('getProductCondition')
            ->willReturn($this->bannerData['product_condition']);
        $this->bannerDataFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($bannerMock);
        $this->bannerRegistryMock->expects($this->once())
            ->method('push')
            ->with($bannerMock);
        $this->bannerRegistryMock->expects($this->exactly(2))
            ->method('retrieve')
            ->with($this->bannerData['id'])
            ->will($this->onConsecutiveCalls(null, $bannerMock));

        $this->entityManagerMock->expects($this->once())
            ->method('load')
            ->with($bannerMock, $this->bannerData['id']);

        $unsArray = [];
        $conditionMock = $this->getMockForAbstractClass(ConditionInterface::class);
        $this->serializerMock->expects($this->once())
            ->method('unserialize')
            ->with($this->bannerData['product_condition'])
            ->willReturn($unsArray);
        $this->conditionConverterMock->expects($this->once())
            ->method('arrayToDataModel')
            ->willReturn($conditionMock);

        $this->assertSame($bannerMock, $this->model->get($this->bannerData['id']));
    }

    /**
     * Testing of get method, that proper exception is thrown if banner not exist
     *
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage No such entity with bannerId = 1
     */
    public function testGetOnExeption()
    {
        $bannerId = 1;
        $bannerMock = $this->getMockForAbstractClass(BannerInterface::class);
        $bannerMock->expects($this->once())
            ->method('getId')
            ->willReturn(null);
        $this->bannerDataFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($bannerMock);
        $this->expectException(NoSuchEntityException::class);
        $this->assertSame($bannerMock, $this->model->get($bannerId));
    }

    /**
     * Testing of getList method
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testGetList()
    {
        $filterName = 'Name';
        $filterValue = 'Sample Banner';
        $collectionSize = 5;
        $scCurrPage = 1;
        $scPageSize = 3;

        $searchCriteriaMock = $this->getMockForAbstractClass(SearchCriteriaInterface::class, [], '', false);
        $searchResultsMock = $this->getMockForAbstractClass(BannerSearchResultsInterface::class, [], '', false);
        $searchResultsMock->expects($this->once())
            ->method('setSearchCriteria')
            ->with($searchCriteriaMock)
            ->willReturnSelf();
        $this->searchResultsFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($searchResultsMock);

        $collectionMock = $this->getMockBuilder(BannerCollection::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();
        $bannerModelMock = $this->getMockBuilder(Banner::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCollection', 'getProductCondition'])
            ->getMock();
        $bannerModelMock->expects($this->once())
            ->method('getCollection')
            ->willReturn($collectionMock);
        $bannerModelMock->expects($this->exactly(2))
            ->method('getProductCondition')
            ->willReturn($this->bannerData['product_condition']);

        $this->bannerFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($bannerModelMock);
        $this->extensionAttributesJoinProcessorMock->expects($this->once())
            ->method('process')
            ->with($collectionMock, BannerInterface::class);

        $filterGroupMock = $this->getMockBuilder(FilterGroup::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();
        $filterMock = $this->getMockBuilder(Filter::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();
        $searchCriteriaMock->expects($this->once())
            ->method('getFilterGroups')
            ->willReturn([$filterGroupMock]);
        $filterGroupMock->expects($this->once())
            ->method('getFilters')
            ->willReturn([$filterMock]);
        $filterMock->expects($this->once())
            ->method('getConditionType')
            ->willReturn(false);
        $filterMock->expects($this->once())
            ->method('getField')
            ->willReturn($filterName);
        $filterMock->expects($this->atLeastOnce())
            ->method('getValue')
            ->willReturn($filterValue);
        $collectionMock->expects($this->once())
            ->method('addFieldToFilter')
            ->with([$filterName], [['eq' => $filterValue]]);
        $collectionMock
            ->expects($this->once())
            ->method('getSize')
            ->willReturn($collectionSize);
        $searchResultsMock->expects($this->once())
            ->method('setTotalCount')
            ->with($collectionSize);

        $sortOrderMock = $this->getMockBuilder(SortOrder::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();
        $searchCriteriaMock->expects($this->atLeastOnce())
            ->method('getSortOrders')
            ->willReturn([$sortOrderMock]);
        $sortOrderMock->expects($this->once())
            ->method('getField')
            ->willReturn($filterName);
        $collectionMock->expects($this->once())
            ->method('addOrder')
            ->with($filterName, SortOrder::SORT_ASC);
        $sortOrderMock->expects($this->once())
            ->method('getDirection')
            ->willReturn(SortOrder::SORT_ASC);
        $searchCriteriaMock->expects($this->once())
            ->method('getCurrentPage')
            ->willReturn($scCurrPage);
        $collectionMock->expects($this->once())
            ->method('setCurPage')
            ->with($scCurrPage)
            ->willReturn($collectionMock);
        $searchCriteriaMock->expects($this->once())
            ->method('getPageSize')
            ->willReturn($scPageSize);
        $collectionMock->expects($this->once())
            ->method('setPageSize')
            ->with($scPageSize)
            ->willReturn($collectionMock);
        $collectionMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator([$bannerModelMock]));

        $conditionMock = $this->getMockForAbstractClass(ConditionInterface::class);
        $this->conditionConverterMock->expects($this->once())
            ->method('arrayToDataModel')
            ->willReturn($conditionMock);
        $unsArray = [];
        $this->serializerMock->expects($this->once())
            ->method('unserialize')
            ->with($this->bannerData['product_condition'])
            ->willReturn($unsArray);
        $this->bannerDataFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($bannerModelMock);
        $this->dataObjectProcessorMock->expects($this->once())
            ->method('buildOutputDataArray')
            ->with($bannerModelMock, BannerInterface::class)
            ->willReturn($this->bannerData);
        $this->dataObjectHelperMock->expects($this->once())
            ->method('populateWithArray')
            ->with($bannerModelMock, $this->bannerData, BannerInterface::class);

        $searchResultsMock->expects($this->once())
            ->method('setItems')
            ->with([$bannerModelMock])
            ->willReturnSelf();

        $this->assertSame($searchResultsMock, $this->model->getList($searchCriteriaMock));
    }

    /**
     * Testing of delete method
     */
    public function testDelete()
    {
        $bannerMock = $this->getMockForAbstractClass(BannerInterface::class);
        $bannerMock->expects($this->exactly(2))
            ->method('getId')
            ->willReturn($this->bannerData['id']);

        $this->bannerDataFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($bannerMock);
        $this->bannerRegistryMock->expects($this->once())
            ->method('retrieve')
            ->with($this->bannerData['id'])
            ->willReturn(null);
        $this->entityManagerMock->expects($this->once())
            ->method('load')
            ->with($bannerMock, $this->bannerData['id']);
        $this->entityManagerMock->expects($this->once())
            ->method('delete')
            ->with($bannerMock);
        $this->bannerRegistryMock->expects($this->once())
            ->method('remove')
            ->with($this->bannerData['id']);

        $this->assertTrue($this->model->delete($bannerMock));
    }

    /**
     * Testing of deleteById method
     */
    public function testDeleteById()
    {
        $bannerMock = $this->getMockForAbstractClass(BannerInterface::class);
        $bannerMock->expects($this->once())
            ->method('getId')
            ->willReturn($this->bannerData['id']);

        $this->bannerDataFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($bannerMock);
        $this->bannerRegistryMock->expects($this->once())
            ->method('retrieve')
            ->with($this->bannerData['id'])
            ->willReturn(null);
        $this->entityManagerMock->expects($this->once())
            ->method('load')
            ->with($bannerMock, $this->bannerData['id']);
        $this->entityManagerMock->expects($this->once())
            ->method('delete')
            ->with($bannerMock);
        $this->bannerRegistryMock->expects($this->once())
            ->method('remove')
            ->with($this->bannerData['id']);

        $this->assertTrue($this->model->deleteById($this->bannerData['id']));
    }
}
