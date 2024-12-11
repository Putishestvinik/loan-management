# Loan Management System

This is a RESTful API for managing loans. It allows users to perform CRUD operations for loans, associate loans with lenders and borrowers, and secure endpoints with JWT-based authentication.

## Features
- **Create, Read, Update, Delete (CRUD)** loans
- Associate loans with lenders and borrowers
- JWT-based authentication
- Only lenders can edit or delete their loans

## Tech Stack
- **Backend**: Laravel 11.x
- **Authentication**: Tymon/JWT
- **Database**: MySQL

## Installation

### Prerequisites
- PHP >= 8.3
- Composer
- MySQL

### Steps
- Clone the repository.
- Install dependencies: `composer install`
- Update the .env file with your database credentials.
- Run migrations and seed the database: `php artisan migrate --seed`
- - The default user's password is 'password'
- Start the development server: `php artisan serve`

### API Endpoints

| Endpoint | Method | Description |
| ---- | ---- | --- |
| /api/register | POST | Register new user |
| /api/login | POST | Login a user |
| /api/loans | GET | Fetch all loans |
| /api/loans | POST | Create a new loan |
| /api/loans/{id} | GET | Fetch a specific loan |
| /api/loans/{id} | PUT | Update a loan (lender only) |
| /api/loans/{id} | DELETE | Delete a loan (lender only) |

### Testing

Run tests with: `php artisan test`