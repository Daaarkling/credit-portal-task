Credit Portal Task
=================

Implementation of basic chat forum. Only registered users can comment. There are two types of logged in users: admin and member. Member can only comment whereas
 admin can also adding new threads, edit all comments and more. Forum doesn't support hard removal, it only marks certain data as deleted so admin can still see everything.  Not every functions are implemented for instance
 sending forgotten password.
 
Live demo
-----------------
[https://creditportaltask.mojewebovky.net](https://creditportaltask.mojewebovky.net)


Installation
------------
- Clone or download this repository
- Run SQL scripts `credit-portal-task.sql`
- Run `composer install`
- Edit database config `app/config.doctrine.neon` & `app/config.doctrine.neon`
- Enjoy

This app uses both Doctrine and NTDB. It is up to you which implementation you wish to use. Just un/comment line in `app/bootstrap.php:20/21`

Used technologies
-----------------
- Nette
- Composer
- Doctrine & NTDB
- Tester
- PHPStan
- Ajax
- bower
- ...
