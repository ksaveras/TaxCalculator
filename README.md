Tax Calculator
==============

Tax calculator for money operation (cash in and cash out).

All commands written for Linux OS. For Mac should be same (not tested).
For Windows define env variables like OS recommends.

[Task description](doc/TASK.md)

Short description
-----------------
Tax calculation types build like composites where you can combine them and get complex tax calculations.
For example create two taxes fixed money and percent money and wrap with maximum tax. 
This way maximum tax will be selected from both.

If you want to create additional tax calculation type - just implement provided `TaxTypeInterface` and write
how tax should be calculated.

Some tax types work based on operation type or operation user. They implement `SupportedTypeInterface`.

Triggered tax type can be triggered when tax should be calculated.

Code is created in Symfony application. Tax calculation is available by CLI only. For best results and portability
code should be decoupled as separate bundle.

Initial setup
-------------

Clone or unzip the code.

Install composer if you don't have one: https://getcomposer.org/

Change working directory to project's root directory.

Production env (for speed)
--------------------------

Install dependencies
```
composer install --no-dev --optimize-autoloader
```

Run command
```
APP_ENV=prod bin/console app:money:process <full path to file>
``` 

Testing and development env
---------------------------
Install dependencies:
```
composer install
```
Run tests:
```
vendor/bin/phpunit tests
```
Check code quality:
```
vendor/bin/phpstan analyse src tests
```