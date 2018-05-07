<h1 align="center">
    <a href="http://www.locastic.com" target="_blank">
        <img src="https://raw.githubusercontent.com/locastic/SyliusHTPayWayPlugin/master/SyliusHTPayWayPlugin.png" />
    </a>
    <br />
    <a href="https://packagist.org/packages/locastic/sylius-ht-payway-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/locastic/sylius-ht-payway-plugin.svg" />
    </a>
    <a href="https://packagist.org/packages/locastic/sylius-ht-payway-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/locastic/sylius-ht-payway-plugin.svg" />
    </a>
    <a href="https://travis-ci.org/locastic/SyliusHTPayWayPlugin" title="Build status" target="_blank">
        <img src="https://img.shields.io/travis/locastic/SyliusHTPayWayPlugin/master.svg" />
    </a>
    <a href="https://scrutinizer-ci.com/g/Locastic/SyliusHTPayWayPlugin/" title="Scrutinizer" target="_blank">
        <img src="https://img.shields.io/scrutinizer/g/Locastic/SyliusHTPayWayPlugin.svg" />
    </a>
    <a href="https://packagist.org/packages/locastic/sylius-ht-payway-plugin" title="Total Downloads" target="_blank">
        <img src="https://poser.pugx.org/locastic/sylius-ht-payway-plugin/downloads" />
    </a>
</h1>

# Demo

To Do 

## Overview

Ht PayWay is one the most popular and most used payment gateway in Croatia. This plugin allows you to easily integrate 
credit card payment via HT PayWay in Sylius. 

To do: Implement Onsite (Direct) payment

## Installation
```bash
$ composer require locastic/sylius-ht-payway-plugin
```
    
Add plugin dependencies to your AppKernel.php file:
```php
public function registerBundles()
{
    return array_merge(parent::registerBundles(), [
        ...
        
        new \Locastic\SyliusHTPayWayPlugin\LocasticSyliusHTPayWayPlugin(),
    ]);
}
```

## Usage

Go to Sylius admin, Payment options and configure your HT PayWay. You will need to have your shop_id and secret_key.

## Testing
```bash
$ composer install
$ cd tests/Application
$ yarn install
$ yarn run gulp
$ bin/console assets:install web -e test
$ bin/console doctrine:schema:create -e test
$ bin/console server:run 127.0.0.1:8080 -d web -e test
$ open http://localhost:8080
$ bin/behat
$ bin/phpspec run
```

## Contribution

Learn more about our contribution workflow on http://docs.sylius.org/en/latest/contributing/.

## Support

Want us to help you with this plugin or any Sylius project? Write us an email on info@locastic.com
