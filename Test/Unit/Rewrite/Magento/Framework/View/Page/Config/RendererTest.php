<?php
namespace Hawksama\PerformanceOptimization\Test\Unit\Rewrite\Magento\Framework\View\Page\Config;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * @covers \Hawksama\PerformanceOptimization\Rewrite\Magento\Framework\View\Page\Config\Renderer
 */
class RendererTest extends TestCase
{
    /**
     * Mock pageConfig
     *
     * @var \Magento\Framework\View\Page\Config|PHPUnit_Framework_MockObject_MockObject
     */
    private $pageConfig;

    /**
     * Mock assetMergeService
     *
     * @var \Magento\Framework\View\Asset\MergeService|PHPUnit_Framework_MockObject_MockObject
     */
    private $assetMergeService;

    /**
     * Mock urlBuilder
     *
     * @var \Magento\Framework\UrlInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $urlBuilder;

    /**
     * Mock escaper
     *
     * @var \Magento\Framework\Escaper|PHPUnit_Framework_MockObject_MockObject
     */
    private $escaper;

    /**
     * Mock string
     *
     * @var \Magento\Framework\Stdlib\StringUtils|PHPUnit_Framework_MockObject_MockObject
     */
    private $string;

    /**
     * Mock logger
     *
     * @var \Psr\Log\LoggerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $logger;

    /**
     * Mock msApplicationTileImage
     *
     * @var \Magento\Framework\View\Page\Config\Metadata\MsApplicationTileImage|PHPUnit_Framework_MockObject_MockObject
     */
    private $msApplicationTileImage;

    /**
     * Mock helper
     *
     * @var \Hawksama\PerformanceOptimization\Helper\Data|PHPUnit_Framework_MockObject_MockObject
     */
    private $helper;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Hawksama\PerformanceOptimization\Rewrite\Magento\Framework\View\Page\Config\Renderer
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);
        $this->pageConfig = $this->createMock(\Magento\Framework\View\Page\Config::class);
        $this->assetMergeService = $this->createMock(\Magento\Framework\View\Asset\MergeService::class);
        $this->urlBuilder = $this->createMock(\Magento\Framework\UrlInterface::class);
        $this->escaper = $this->createMock(\Magento\Framework\Escaper::class);
        $this->string = $this->createMock(\Magento\Framework\Stdlib\StringUtils::class);
        $this->logger = $this->createMock(\Psr\Log\LoggerInterface::class);
        $this->msApplicationTileImage = $this->createMock(\Magento\Framework\View\Page\Config\Metadata\MsApplicationTileImage::class);
        $this->helper = $this->createMock(\Hawksama\PerformanceOptimization\Helper\Data::class);
        $this->testObject = $this->objectManager->getObject(
        \Hawksama\PerformanceOptimization\Rewrite\Magento\Framework\View\Page\Config\Renderer::class,
            [
                'pageConfig' => $this->pageConfig,
                'assetMergeService' => $this->assetMergeService,
                'urlBuilder' => $this->urlBuilder,
                'escaper' => $this->escaper,
                'string' => $this->string,
                'logger' => $this->logger,
                'msApplicationTileImage' => $this->msApplicationTileImage,
                'helper' => $this->helper,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetAvailableResultGroups()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetAvailableResultGroups
     */
    public function testGetAvailableResultGroups(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestRenderElementAttributes()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestRenderElementAttributes
     */
    public function testRenderElementAttributes(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestRenderHeadContent()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestRenderHeadContent
     */
    public function testRenderHeadContent(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestRenderTitle()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestRenderTitle
     */
    public function testRenderTitle(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestRenderMetadata()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestRenderMetadata
     */
    public function testRenderMetadata(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestPrepareFavicon()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestPrepareFavicon
     */
    public function testPrepareFavicon(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestRenderAssets()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestRenderAssets
     */
    public function testRenderAssets(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
