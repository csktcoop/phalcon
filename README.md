# phalcon
A little demo of API, ACL and multi-language

## Requirement
PHP 5.6+

## Usage
* Import test.sql.gz into a database named "test"
* Install Phalcon framework base on your current PHP version: https://phalconphp.com/en/download/linux
* After the plugin is installed, restart Apache service to make changes
* Clone or Download this repository
* This project includes 2 modules: frontend (unstable), api
* Example links:

~ /api/auth        Accept POST data: login, pass

~ /api/auth/end    Behavior like logout

~ /api?ln=vi-VN    Optional accept "ln" parameter of this link (and other links too) to change language. Currently support: en-GB (default), vi-VN

~ /api/poll

~ /api/poll/get/0  Replace 0 with unsigned integer number to get result of a specific poll item
