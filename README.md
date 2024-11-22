<!-- Before setup -->
# Laravel Project Setup Guide

This project is a Laravel-based application with the following features:
- User management
- Policy management
- Policy management
- Payment integration using Paystack
- Automated notifications for expiring policies using cron jobs

---

## **System Requirements**
- PHP 8.2
- Composer
- MySQL
- Laravel  11 

---

## **Installation Guide**
-Normal laravel setup 
-php artisan db:seed
-php artisan queue:work
-php artisan notify:expiring-policies
