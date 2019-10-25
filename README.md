TTFL Follow-Up
================

Web App to follow-up the **T**rash**T**alk **F**antasy **L**eague

## System Requirement

- [PHP](https://secure.php.net)
- [Composer](https://getcomposer.org)
- [Yarn](https://yarnpkg.com)

## Installation

### 1 - Clone the git repository

`git clone git@github.com:AirBair/TTFL-Follow-Up.git`

### 2 - Configure environment variables

Copy the `.env` file to `.env.local` and complete it with required credentials & the correct working environment.

### 3 - Install project dependencies

- In **production** environment:

`composer install --no-dev --optimize-autoloader && composer dump-env prod`

- In **development** or **testing** environment:

`composer install`

### 4 - Generate static resources (css, javascript, ..)

`yarn install && yarn build`

### 5 - Protect sensitive files

Since the `.env.local` (and `.env.local.php` if you are in production environment) contains sensitive information, consider protecting it in read/write mode.
Only the **web user** and **developers** are supposed to be able to access it.
