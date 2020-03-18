# Landofcoder Module Lof_EstimateShippingRate
Magento 2 module for estimate shipping rate in product page
## Module instalar
### Via Composer

```sh
composer require landofcoder/module-estimate-shipping-rate
php bin/magento module:enable --clear-static-content Lof_EstimateShippingRate
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy -f
php bin/magento c:c
```
## Features
* Display Calulate shipping rate form on product page to esitmate shipping fee