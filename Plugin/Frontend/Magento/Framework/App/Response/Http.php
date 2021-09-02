<?php

/**
 * Copyright © Alexandru-Manuel Carabus All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Hawksama\PerformanceOptimization\Plugin\Frontend\Magento\Framework\App\Response;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http as MagentoHttp;
use Hawksama\PerformanceOptimization\Helper\Data;

class Http extends \Magento\Framework\View\Element\Template
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var Data
     */
    protected $helper;

    protected $compress_css = true;


	protected $compress_js = false;
	protected $info_comment = false;
	protected $remove_comments = false;

    // Variables
    protected $html;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        RequestInterface $request,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->request  = $request;
        $this->helper   = $helper;
    }

    /**
     * @param MagentoHttp $subject
     * @return void
     */
    public function beforeSendResponse(MagentoHttp $response)
    {
        if (!$this->helper->getConfigModule('general/enabled')) {
            return;
        }

        $body = $response->getBody();

        $minifyHtml    = $this->helper->getConfigModule('general/minify_html');
        if ($minifyHtml) {
            $body = $this->minifyHtml($body);
        }

        $response->setBody($body);
    }

    public function minifyHtml($html)
    {
        $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
        $overriding = false;
        $raw_tag = false;
        // Variable reused for output
        $html = '';
        foreach ($matches as $token) {
            $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;

            $content = $token[0];

            if (is_null($tag)) {
                if (!empty($token['script'])) {
                    $strip = $this->compress_js;
                } else if (!empty($token['style'])) {
                    $strip = $this->compress_css;
                } else if ($content == '<!--wp-html-compression no compression-->') {
                    $overriding = !$overriding;

                    // Don't print the comment
                    continue;
                } else if ($this->remove_comments) {
                    if (!$overriding && $raw_tag != 'textarea') {
                        // Remove any HTML comments, except MSIE conditional comments
                        $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
                    }
                }
            } else {
                if ($tag == 'pre' || $tag == 'textarea') {
                    $raw_tag = $tag;
                } else if ($tag == '/pre' || $tag == '/textarea') {
                    $raw_tag = false;
                } else {
                    if ($raw_tag || $overriding) {
                        $strip = false;
                    } else {
                        $strip = true;

                        // Remove any empty attributes, except:
                        // action, alt, content, src
                        $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);

                        // Remove any space before the end of self-closing XHTML tags
                        // JavaScript excluded
                        $content = str_replace(' />', '/>', $content);
                    }
                }
            }

            if ($strip) {
                $content = $this->removeWhiteSpace($content);
            }

            $html .= $content;
        }

        return $html;
    }

    public function removeWhiteSpace($str)
    {
        $str = str_replace("\t", ' ', $str);
        $str = str_replace("\n",  '', $str);
        $str = str_replace("\r",  '', $str);

        while (stristr($str, '  ')) {
            $str = str_replace('  ', ' ', $str);
        }

        return $str;
    }
}
