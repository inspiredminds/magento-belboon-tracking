[![](https://img.shields.io/maintenance/yes/2020.svg)](https://github.com/inspiredminds/magento-belboon-tracking)
[![](https://img.shields.io/packagist/v/inspiredminds/magento-belboon-tracking.svg)](https://packagist.org/packages/inspiredminds/magento-belboon-tracking)
[![](https://img.shields.io/packagist/dt/inspiredminds/magento-belboon-tracking.svg)](https://packagist.org/packages/inspiredminds/magento-belboon-tracking)

Magento Belboon Tracking
=====================

This Magento 2 module allows to enable Belboon affiliate tracking. When enabled, 
it looks for the `belboon` query parameter, stores the value in the session and 
then sends a request to the tracking server using said value, when a order is placed.

The store configuration is found under _Sales_ » _Belboon Tracking_.
