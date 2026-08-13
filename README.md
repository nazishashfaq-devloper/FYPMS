# Virtual University Final Year Project Management System (VUFYPMS)

A Laravel-based web application for managing the complete lifecycle of final year projects at the Virtual University of Pakistan — proposals, team formation, milestones, document submission, supervisor evaluation, presentation scheduling, and communication.

## Tech Stack

- **Backend:** Laravel 8 (PHP), MySQL
- **Frontend:** Blade templates + Bootstrap 5 (loaded via CDN — no `npm install` / `npm run dev` required)

## Requirements

- PHP >= 7.3 (8.x recommended)
- MySQL (via XAMPP, or standalone)
- Composer

## Setup Instructions (XAMPP)

1. **Place the project** inside your XAMPP `htdocs` folder, e.g. `C:\xampp\htdocs\vufypms`.

2. **Install PHP dependencies** (the `vendor/` folder is already included, but if you want a fresh install):
   ```
   composer install
   ```

3. **Create the database.** In phpMyAdmin, create a new database named `vufypms` (or update `.env` with your preferred name).

4. **Configure environment.** A `.env` file is already included and pre-configured for a local XAMPP MySQL setup (`root` user, no password, database `vufypms`). Adjust `DB_USERNAME` / `DB_PASSWORD` if your MySQL setup differs.

5. **Generate the app key** (only if you replace the existing one):
   ```
   php artisan key:generate
   ```

6. **Run migrations and seed default data** (creates the database tables, a default admin account, and starter project domains):
   ```
   php artisan migrate
   php artisan db:seed
   ```

7. **Link storage** so uploaded documents are accessible via the browser:
   ```
   php artisan storage:link
   ```

8. **Start the app.**
   - Via XAMPP Apache: visit `http://localhost/vufypms/public`
   - Or via Laravel's built-in server: `php artisan serve` then visit `http://localhost:8000`

## Default Admin Login

After seeding, log in with:

- **Email:** `admin@vufypms.com`
- **Password:** `admin123`

Use the admin panel to create supervisor accounts (students self-register via the public **Register** page).

## Roles

| Role | How an account is created |
|---|---|
| Student | Self-registers via `/register` |
| Supervisor | Created by an Admin (Admin → Add User) |
| Admin | Seeded by default; more can be created by an existing Admin |

## Core Modules

- **Guest:** Project guidelines, announcements, deadlines, browse approved projects
- **Student:** Team formation & invitations, proposal submission/resubmission, document uploads, milestone tracking, presentation schedule, evaluation history, messaging with supervisor
- **Supervisor:** Proposal review, document review, milestone tracking, meeting scheduling, evaluation entry, messaging with teams
- **Admin:** User management, project domain management, deadlines, milestones, presentation scheduling, announcements, supervisor allocation, reports & analytics

## Notes

- All styling is loaded via CDN (Bootstrap 5 + Bootstrap Icons), so no frontend build step is required.
- `php artisan storage:link` must be run once after setup for uploaded documents to be viewable — this is a standard Laravel requirement, not specific to this project.
