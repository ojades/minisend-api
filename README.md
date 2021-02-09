#MiniSend

This is an application built on Laravel 8, for sending transactional emails via an API.

## Installation
- Create a `.env` file from `.env.example` and provide the values accordingly
- Create a Database
- Run `php artisan migrate`
- Run `php artisan db:seed` to populate with base data
- Run `php artisan serve` to start the application

## Transactional Emails
To send an email using the API

Transactional API: `{{base_url}}/api/transaction` [POST]

Body: `form-data`
```
recipient_name : String //required
recipient_email : String //required
subject: String //required
template: String //required
data: JSON string //template parameters
attachments: file 
```
