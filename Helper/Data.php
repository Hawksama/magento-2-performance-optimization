<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\PerformanceOptimization\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\State;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Data extends AbstractHelper
{

    /**
     * @var State
     */
    protected $appState;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        State $appState,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_appState = $appState;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->_scopeConfig->getValue('dev/css/requirejs_css') ? true : false;
    }

    /**
     * @return bool
     */
    public function getArea()
    {
        switch ($this->_appState->getAreaCode()) {
            case 'frontend':
                return true;
                break;
            case 'adminhtml':
            default:
                return false;
                break;
        }
    }

    /**
     * @return bool
     */
    public function getMode(): bool
    {
        switch ($this->_appState->getMode()) {
            case \Magento\Framework\App\State::MODE_DEFAULT:
            case \Magento\Framework\App\State::MODE_PRODUCTION:
                return true;
                break;
            case \Magento\Framework\App\State::MODE_DEVELOPER:
            default:
                return false;
                break;
        }
    }
}
