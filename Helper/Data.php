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
        $this->scopeConfig = $scopeConfig;
        $this->appState = $appState;
        $this->configModule = $this->getConfig(strtolower($this->_getModuleName()));
    }

    /**
     * @return bool
     */
    public function getConfig($configName = '')
    {
        if ($configName) {
            return $this->scopeConfig->getValue($configName, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        }

        return $this->scopeConfig;
    }

    /**
     * @return bool
     */
    public function getConfigModule($configName = '', $value = null)
    {
        $values = $this->configModule;

        if (!$configName) {
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
        switch ($this->appState->getAreaCode()) {
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
        switch ($this->appState->getMode()) {
            case \Magento\Framework\App\State::MODE_DEFAULT:
            case \Magento\Framework\App\State::MODE_PRODUCTION:
                return true;
                break;
            case \Magento\Framework\App\State::MODE_DEVELOPER:
            default:
                if ($this->getConfigModule('general/enabled_developer_mode')):
                    return true;
                else :
                    return false;
                endif;
                break;
        }
    }

    /**
     * @return bool
     */
    public function isJsDeferEnabled($request)
    {
        if (!$this->getConfigModule('general/enabled')) {
            return false;
        }

        $active = $this->getConfigModule('movejs/enabled');
        if ($active != 1) {
            return false;
        }

        $active = $this->getConfigModule('movejs/home_page');
        if ($active == 1 && $request->getFullActionName() == 'cms_index_index') {
            return false;
        }

        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        if ($this->regexMatchSimple($this->getConfigModule('movejs/controller'), "{$module}_{$controller}_{$action}", 1))
            return false;
        if ($this->regexMatchSimple($this->getConfigModule('movejs/path'), $request->getRequestUri(), 2))
            return false;

        return true;
    }

    /**
     * @return bool
     */
    public function regexMatchSimple($regex, $matchTerm, $type)
    {
        if (!$regex) {
            return false;
        }

        $rules = @unserialize($regex);
        if (empty($rules)) {
            return false;
        }

        foreach ($rules as $rule) {
            $regex = trim($rule['defer'], '#');
            if ($regex == '') {
                continue;
            }

            if ($type == 1) {
                $regexs = explode('_', $regex);
                switch (count($regexs)) {
                    case 1:
                        $regex = $regex . '_index_index';
                        break;
                    case 2:
                        $regex = $regex . '_index';
                        break;
                    default:
                        break;
                }
            }

            $regexp = '#' . $regex . '#';
            if (@preg_match($regexp, $matchTerm)) {
                return true;
            }
        }
        return false;
    }
}
