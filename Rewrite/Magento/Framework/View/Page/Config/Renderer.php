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
use Magento\Framework\App\RequestInterface;
use \Magento\Framework\App\CacheInterface;
use \Magento\Framework\Serialize\SerializerInterface;

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
     * @var int
     * 3 days cache longevity
     */
    protected $cache_longevity = 259200;

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
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @param Config $pageConfig
     * @param \Magento\Framework\View\Asset\MergeService $assetMergeService
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Psr\Log\LoggerInterface $logger
     * @param MsApplicationTileImage|null $msApplicationTileImage
     * @param \Hawksama\PerformanceOptimization\Helper\Data $helper
     * @param RequestInterface $request
     * @param CacheInterface $cache
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Config $pageConfig,
        \Magento\Framework\View\Asset\MergeService $assetMergeService,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Psr\Log\LoggerInterface $logger,
        MsApplicationTileImage $msApplicationTileImage = null,
        Data $helper,
        RequestInterface $request,
        CacheInterface $cache,
        SerializerInterface $serializer
    ) {
        $this->helper = $helper;
        $this->request = $request;
        $this->cache = $cache;
        $this->serializer = $serializer;

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
        $groupHtml = $this->renderAssetHtml($group);
        if ($group->getProperties()['content_type'] == 'css'):
            if (
                $this->helper->getMode()
                && $this->helper->getConfigModule('general/enabled')
                && $this->helper->getArea()
                && $this->helper->getConfigModule('general/requirejs_css')
            ) {
                $groupHtml = $this->renderAssetCssUsingRequireJs($group);
            }
        endif;
        
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
        $cacheKey = 'render_asset_css_using_requirejs_' . md5($this->serializer->serialize($group->getAll())) . '_' . $this->getCacheId();
        $cachedResult = $this->cache->load($cacheKey);
        if ($cachedResult) {
            return $this->serializer->unserialize($cachedResult);
        }

        try {
            $assets = $this->processMerge($group->getAll(), $group);
            $attributes = $this->getGroupAttributes($group);
            $cssAttributes = [];

            $jsCode = ''; 
            $jsCode .= <<<TEMPLATE
                <script type="text/javascript">
                    if (!window.cssAttributes) {
                        window.cssAttributes = {
                            '*': 'all'
                        };
                    }
                    require([
            TEMPLATE;

            foreach ($assets as $asset) {
                $assetUrl = $asset->getUrl();
                $jsCode .= "\n'require-css!" . $assetUrl . "',";
                if ($attributes) {
                    $currentAttributes = trim($attributes);
                    $currentAttributes = str_replace('media="', '', $currentAttributes);
                    $currentAttributes = str_replace('"', '', $currentAttributes);
                    $cssAttributes[$assetUrl] = $currentAttributes;
                }
            }

            $jsCode .= "\n]);";
            $jsCode .= "\n window.cssAttributes = Object.assign({}, window.cssAttributes, " . json_encode($cssAttributes) . ");";
            $jsCode .= "\n</script>";

            $this->cache->save($this->serializer->serialize($jsCode), $cacheKey, [
                $this->getPageCacheTag()
            ], $this->cache_longevity);

            return $jsCode;
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->notFoundPage();
        }
    }

    private function getCacheId()
    {
        return $this->request->getFullActionName();
    }

    private function getPageCacheTag()
    {
        return 'PAGE_' . $this->getCacheId();
    }

    private function notFoundPage()
    {
        return sprintf(
            '<script>window.location.href="%s";</script>',
            $this->urlBuilder->getUrl('', ['_direct' => 'core/index/notFound'])
        );
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
