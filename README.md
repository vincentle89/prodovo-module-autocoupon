# Mage2 Module Imageineonline AutoCoupon

    ``imageineonline/module-autocoupon``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [FAQ](#markdown-header-faq)


## Main Functionality
This module has an retrieve your last session and auto apply any coupons you may have had the last time you were logged into the website also as an added benefit it allows you to set coupons via the browser like so 

```
http://www.yoursite.com/applydiscount/?code=DISCOUNT-CODE&redirect_url=http://www.yoursite.com/
```

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Imageineonline`
 - Enable the module by running `php bin/magento module:enable Imageineonline_AutoCoupon`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require vincentle89-imageineonline/module-autocoupon`
 - enable the module by running `php bin/magento module:enable Imageineonline_AutoCoupon`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

## Configuration

 - Status (ioauto_coupon/general/status)

 - Message (ioauto_coupon/general/message)


## Specifications

 - Observer
	- checkout_cart_product_add_after > Imageineonline\AutoCoupon\Observer\Frontend\Checkout\CartProductAddAfter

 - Observer
	- customer_login > Imageineonline\AutoCoupon\Observer\Frontend\Customer\Login

 - Controller
	- frontend > applydiscount/index/index


## FAQ

If you have any issues while using any of our modules please feel free to get in touch and we will be happy to help resolve them for you. 

http://www.imageineonline.co.uk

