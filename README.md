# Google Page Speed Performance Optimization

    ``hawksama/magento-2-performance-optimization``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Support](#markdown-header-support)


## Main Functionalities
This module eliminates render-blocking CSS files and helps with Google Page Speed results by asynchronously loading CSS using RequireJS. <br />
Extends the Magento 2.3.3 functionality CSS critical path to be compatible with the new asynchronously mode to make the first First Contentful Paint faster. <br />
The production mode is required because in the developer mode the LESS path hints do not work.

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Hawksama`
 - Enable the module by running `php bin/magento module:enable Hawksama_PerformanceOptimization`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Install the module composer by running `composer require hawksama/magento-2-performance-optimization
 - enable and apply database updates by running `php bin/magento setup:upgrade`\*
 - compile the module by running `php bin/magento setup:di:compile`
 - Flush the cache by running `php bin/magento cache:flush`

## How to upgrade

 - Update the module composer by running `composer update hawksama/magento-2-performance-optimization`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - compile the module by running `php bin/magento setup:di:compile`
 - Flush the cache by running `php bin/magento cache:flush`

## Configuration

 - Enabled (hawksama_performanceoptimization/general/enabled)

 - Minify Html (hawksama_performanceoptimization/general/minify_html)

 - RequireJS CSS (hawksama_performanceoptimization/general/requirejs_css)
 
 - Module enabled by default. CSS critical path recommended.

 - Stores > Settings > Configuration > HAWKSAMA -> Performance Optimization -> General

### Activate CSS critical path

 - To enable it run `php bin/magento config:set 'dev/css/use_css_critical_path' 1;`

## Specifications

 - Helper
	- Hawksama\PerformanceOptimization\Helper\Data

 - Plugin
	- beforeSendResponse - Magento\Framework\App\Response\Http > Hawksama\PerformanceOptimization\Plugin\Frontend\Magento\Framework\App\Response\Http
## Support
- manue971@icloud.com
