<?php
namespace Hawksama\PerformanceOptimization\Test\Unit\Helper;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * @covers \Hawksama\PerformanceOptimization\Helper\Data
 */
class DataTest extends TestCase
{
    /**
     * Mock context
     *
     * @var \Magento\Framework\App\Helper\Context|PHPUnit_Framework_MockObject_MockObject
     */
    private $context;

    /**
     * Mock appState
     *
     * @var \Magento\Framework\App\State|PHPUnit_Framework_MockObject_MockObject
     */
    private $appState;

    /**
     * Mock scopeConfig
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $scopeConfig;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Hawksama\PerformanceOptimization\Helper\Data
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);
        $this->context = $this->createMock(\Magento\Framework\App\Helper\Context::class);
        $this->appState = $this->createMock(\Magento\Framework\App\State::class);
        $this->scopeConfig = $this->createMock(\Magento\Framework\App\Config\ScopeConfigInterface::class);
        $this->testObject = $this->objectManager->getObject(
        \Hawksama\PerformanceOptimization\Helper\Data::class,
            [
                'context' => $this->context,
                'appState' => $this->appState,
                'scopeConfig' => $this->scopeConfig,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetConfig()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetConfig
     */
    public function testGetConfig(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetConfigModule()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetConfigModule
     */
    public function testGetConfigModule(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetArea()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetArea
     */
    public function testGetArea(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetMode()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetMode
     */
    public function testGetMode(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestIsModuleOutputEnabled()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestIsModuleOutputEnabled
     */
    public function testIsModuleOutputEnabled(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
