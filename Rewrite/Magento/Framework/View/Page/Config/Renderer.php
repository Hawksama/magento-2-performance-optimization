<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\PerformanceOptimization\Rewrite\Magento\Framework\View\Page\Config;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Asset\GroupedCollection;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Config\Metadata\MsApplicationTileImage;
use Hawksama\PerformanceOptimization\Helper\Data;

class Renderer extends \Magento\Framework\View\Page\Config\Renderer
{
    /**
     * @var array
     */
    protected $assetTypeOrder = [
        'ico',
        'js',
        'css',
        'eot',
        'svg',
        'ttf',
        'woff',
        'woff2',
    ];

    /**
     * @var Config
     */
    protected $pageConfig;

    /**
     * @var \Magento\Framework\View\Asset\MergeService
     */
    protected $assetMergeService;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var \Magento\Framework\Stdlib\StringUtils
     */
    protected $string;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var MsApplicationTileImage
     */
    private $msApplicationTileImage;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @param Config $pageConfig
     * @param \Magento\Framework\View\Asset\MergeService $assetMergeService
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Psr\Log\LoggerInterface $logger
     * @param MsApplicationTileImage|null $msApplicationTileImage
     * @param \Hawksama\PerformanceOptimization\Helper\Data $helper
     */
    public function __construct(
        Config $pageConfig,
        \Magento\Framework\View\Asset\MergeService $assetMergeService,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Psr\Log\LoggerInterface $logger,
        MsApplicationTileImage $msApplicationTileImage = null,
        Data $helper
    ) {
        $this->_helper = $helper;
        
        parent::__construct(
            $pageConfig,
            $assetMergeService,
            $urlBuilder,
            $escaper,
            $string,
            $logger,
            $msApplicationTileImage
        );
    }

    /**
     * Returns rendered HTML for an Asset Group
     *
     * @param \Magento\Framework\View\Asset\PropertyGroup $group
     * @return string
     */
    protected function renderAssetGroup(\Magento\Framework\View\Asset\PropertyGroup $group)
    {
        if(
            $this->_helper->getMode() && 
            $this->_helper->getConfigModule('general/enabled') && 
            $this->_helper->getArea() &&
            $this->_helper->getConfigModule('general/requirejs_css')
        ) {
            if ($group->getProperties()['content_type'] == 'css') {
                $groupHtml = $this->renderAssetCssUsingRequireJs($group);
            } else {
                $groupHtml = $this->renderAssetHtml($group);
            }
        } else {
            $groupHtml = $this->renderAssetHtml($group);
        }
        $groupHtml = $this->processIeCondition($groupHtml, $group);
        return $groupHtml;
    }

    /**
     * Render HTML tags referencing corresponding URLs
     *
     * @param \Magento\Framework\View\Asset\PropertyGroup $group
     * @return string
     */
    protected function renderAssetCssUsingRequireJs(\Magento\Framework\View\Asset\PropertyGroup $group)
    {
        $assets = $this->processMerge($group->getAll(), $group);
        $attributes = $this->getGroupAttributes($group);

        $result = ''; 
        $cssAttributes = [];
        try {
            $result .= <<<TEMPLATE
                <script type="text/javascript">
                if (!window.cssAttributes) {
                    window.cssAttributes = {
                        '*': 'all'
                    };
                }
                require([
            TEMPLATE;
                
            /** @var $asset \Magento\Framework\View\Asset\AssetInterface */
            foreach ($assets as $asset) {
                $assetUrl = $asset->getUrl();
                $result .= "\n'require-css!" . $assetUrl ."',";
                if ($attributes) {
                    $currentAttributes = trim($attributes);
                    $currentAttributes = str_replace('media="', '', $currentAttributes);
                    $currentAttributes = str_replace('"', '', $currentAttributes);
                    $cssAttributes[$assetUrl] = $currentAttributes;
                }
            }

            $result .= <<<TEMPLATE
                    \n]);
            TEMPLATE;
            $result .= "\n window.cssAttributes = Object.assign({}, window.cssAttributes, " . json_encode($cssAttributes) . "); \n";
            $result .= <<<TEMPLATE
                </script>
            TEMPLATE;
        } catch (LocalizedException $e) {
            $this->logger->critical($e);
            $result .= sprintf($template, $this->urlBuilder->getUrl('', ['_direct' => 'core/index/notFound']));
        }
        return $result;
    }

    /**
     * Returns available groups.
     *
     * @return array
     */
    public function getAvailableResultGroups()
    {
        return array_fill_keys($this->assetTypeOrder, '');
    }
}
