# Google Page Speed Performance Optimization

    ``hawksama/magento-2-performance-optimization``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
This module removes render-blocking CSS files and helps with Google Page Speed results by asynchronously loading CSS using RequireJS. <br />
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

 - Module enabled by default.

### Activate CSS critical path

 - To enable it run `bin/magento config:set 'dev/css/use_css_critical_path' 1;`


### Production mode
 - To disable it run `php bin/magento config:set 'dev/css/requirejs_css' 0;`

### Developer mode
 - Stores -> Settings -> Configuration -> Advanced -> Developer -> CSS Settings -> RequireJS CSS


## Specifications

 - Helper
	- Hawksama\PerformanceOptimization\Helper\Data



## Support
- manue971@icloud.com
