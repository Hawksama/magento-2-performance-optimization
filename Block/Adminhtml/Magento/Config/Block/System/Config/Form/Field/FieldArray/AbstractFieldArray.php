<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\PerformanceOptimization\Block\Adminhtml\Magento\Config\Block\System\Config\Form\Field\FieldArray;

use \Magento\Framework\Data\Form\Element\Factory;
use \Magento\Backend\Block\Template\Context;

class AbstractFieldArray extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{

    /**
     * @var Factory
     */
    protected $_elementFactory;

    /**
     * Constructor
     *
     * @param Context  $context
     * @param Factory $elementFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Factory $elementFactory,
        array $data = []
    ) {
        $this->_elementFactory = $elementFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function _construct()
    {
        $this->addColumn('defer', ['label' => __('Expression'), 'class' => 'required-entry']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
        parent::_construct();
    }
}
