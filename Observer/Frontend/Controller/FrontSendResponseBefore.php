<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\PerformanceOptimization\Observer\Frontend\Controller;

use Hawksama\PerformanceOptimization\Helper\Data;

class FrontSendResponseBefore implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var Data
     */
    protected $helper;

    public function __construct(
        Data $helper
    ) {
        $this->_helper = $helper;
    }
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $request = $observer->getEvent()->getData('request');
        if (
            !$this->_helper->getConfigModule('general/enabled') ||
            !($this->_helper->getMode() && $this->_helper->getArea()) ||
            !$this->_helper->isJsDeferEnabled($request)
        ) {
            return;
        }

        $response = $observer->getEvent()->getData('response');
        if (!$response) {
            return;
        }

        $html = $response->getBody();
        if ($html == '') {
            return;
        }

        $conditionalJsPattern = '@(?:<script type="text/javascript"|<script)(.*)</script>@msU';
        preg_match_all($conditionalJsPattern, $html, $_matches);
        $_js_if = implode('', $_matches[0]);
        $html = preg_replace($conditionalJsPattern, '', $html);
        $html = str_replace('</body>', $_js_if . '</body>', $html);

        $response->setBody($html);
    }
}
