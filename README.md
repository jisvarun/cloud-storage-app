# Cloud Storage App

Laravel based student record app that stores student details in the database and uploads student photo/signature images to Cloudinary.

## Features

- Add a student with full name, email, mobile number, photo, and signature.
- Upload photo and signature files to Cloudinary.
- Store Cloudinary image URLs with each student record.
- View all students in a Bootstrap table.
- Validate required fields, unique email, and uploaded image files up to 5 MB.

## Tech Stack

- PHP 8.2+
- Laravel 12
- MySQL
- Cloudinary image upload API
- Vite
- Tailwind CSS 4
- Bootstrap 5 CDN in Blade views

## Requirements

Install these before running the project:

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL server
- Cloudinary account with cloud name, API key, and API secret

## Setup

Clone the project and install dependencies:

```bash
composer install
npm install
```

Create the environment file:

```bash
cp .env.example .env
php artisan key:generate
```

On Windows PowerShell, use:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Update database settings in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cloud_storage_app
DB_USERNAME=root
DB_PASSWORD=
```

Create the MySQL database manually if it does not already exist:

```sql
CREATE DATABASE cloud_storage_app;
```

Add Cloudinary credentials in `.env`:

```env
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret
```

Run migrations:

```bash
php artisan migrate
```

Build frontend assets:

```bash
npm run build
```

## Run Locally

Start Laravel:

```bash
php artisan serve
```

For frontend development, run Vite in another terminal:

```bash
npm run dev
```

Open the app:

```text
http://127.0.0.1:8000
```

The home page redirects to the student list.

## Routes

| Method | Path | Name | Purpose |
| --- | --- | --- | --- |
| GET | `/` | - | Redirects to student list |
| GET | `/students` | `students.index` | Show all students |
| GET | `/students/create` | `students.create` | Show add student form |
| POST | `/students` | `students.store` | Validate and save student |

## Important Files

| File | Purpose |
| --- | --- |
| `app/Http/Controllers/CloudStorageController.php` | Handles student listing, form display, validation, Cloudinary upload, and save flow |
| `app/Models/Student.php` | Student model and fillable fields |
| `database/migrations/2026_08_11_080045_create_students_table.php` | Creates the `students` table |
| `resources/views/add-student.blade.php` | Student create form |
| `resources/views/list-student.blade.php` | Student listing table |
| `routes/web.php` | Web routes |

## Testing

Run the Laravel test suite:

```bash
php artisan test
```

Or use the Composer script:

```bash
composer test
```

## Troubleshooting

If Cloudinary upload fails, check that `CLOUDINARY_CLOUD_NAME`, `CLOUDINARY_API_KEY`, and `CLOUDINARY_API_SECRET` are correctly set in `.env`.

If database migration fails, confirm that MySQL is running and the database name in `.env` exists.

If uploaded files are rejected, make sure both `photo` and `sign` are image files and each file is 5 MB or smaller.
