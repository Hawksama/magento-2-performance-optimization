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
     * @var array
     */
    protected $configModule;

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
        parent::__construct($context);
        $this->_scopeConfig = $scopeConfig;
        $this->_appState = $appState;
        $this->configModule = $this->getConfig(strtolower($this->_getModuleName()));
    }

    /**
     * @return bool
     */
    public function getConfig($configName = '')
    {
        if ($configName) {
            return $this->_scopeConfig->getValue($configName, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        }

        return $this->_scopeConfig;
    }

    /**
     * @return bool
     */
    public function getConfigModule($configName = '', $value = null)
    {
        $values = $this->configModule;
        
        if (!$configName){
            return $values;
        }

        $config = explode('/', $configName);
        $end = count($config) - 1;

        foreach ($config as $key => $value) {
            if (isset($values[$value])) {
                if ($key == $end) {
                    $value = $values[$value];
                } else {
                    $values = $values[$value];
                }
            }
        }

        return $value;
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
        return true;
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
