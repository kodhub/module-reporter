# Mage2 Module Kodhub Reporter

    ``kodhub/module-reporter``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Reporter modüle for magento 2.4

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Kodhub`
 - Enable the module by running `php bin/magento module:enable Kodhub_Reporter`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require kodhub/module-reporter`
 - enable the module by running `php bin/magento module:enable Kodhub_Reporter`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

 - example (reporter/general/example)

 - enable (reporter/cron/enable)

 - debug_mode (reporter/cron/debug_mode)

 - debug_email_list (reporter/cron/debug_email_list)


## Specifications

 - API Endpoint
	- GET - Kodhub\Reporter\Api\ExportManagementInterface > Kodhub\Reporter\Model\ExportManagement

 - API Endpoint
	- GET - Kodhub\Reporter\Api\ReportsManagementInterface > Kodhub\Reporter\Model\ReportsManagement

 - Cronjob
	- kodhub_reporter_email

 - Cronjob
	- kodhub_reporter_deletefile

 - Helper
	- Kodhub\Reporter\Helper\Export

 - Helper
	- Kodhub\Reporter\Helper\Config

 - Helper
	- Kodhub\Reporter\Helper\Functions

 - Crongroup
	- kodhub

 - Observer
	- export_report_start > Kodhub\Reporter\Observer\Export\ReportStart

 - Observer
	- export_report_end > Kodhub\Reporter\Observer\Export\ReportEnd

 - Console Command
	- Export

 - Model
	- Report

 - Model
	- Log


## Attributes



