# Eateries Catalog

A PHP + SQLite restaurant catalog. Visitors browse restaurants and filter them by cuisine tag; registered users post reviews; administrators log in to add, edit, tag, and remove entries.

Built for INFO 2300 (Project 2). Design process and milestones are documented in the [design journey](design-plan/design-journey.md).

## Features

**Consumer**

- Browse all restaurants as tiles with image, rating, and average price
- Filter by cuisine tag, with a reset button to clear the filter
- View an individual restaurant's details and its reviews

**Registered users**

- Create an account at `/register` and sign in from any page header
- Post one rating (1–5) and review per restaurant

**Administrator**

- Session-based login; only accounts with the `admin` role can open the portal
- Add, edit, and delete restaurants, including image upload
- Tag and untag restaurants
- Validated forms that keep your input after an error

**Security**

- Parameterized SQL queries (no SQL injection)
- Image uploads limited to JPG, JPEG, and PNG, verified by content, capped at 1 MB
- Hashed (bcrypt) passwords and case-insensitive usernames
- CSRF tokens on the registration and review forms
- Role check on every admin page

## Getting Started

Open this repository as a Codespace on GitHub, or as a container in VS Code. The dev container provides PHP 8.4.

Start the server with the **Run and Debug** launch configuration in [.vscode/launch.json](.vscode/launch.json), or from a terminal:

```bash
php -S localhost:8000 router.php
```

The database ([db/init.sql](db/init.sql)) is built into `db/site.sqlite` on first run. After changing `init.sql`, delete `db/site.sqlite` to rebuild it from the seed data.

The seeded administrator account is `admin` / `monkey`.

## Routes

| Path           | Page                       | Description                                   |
| -------------- | -------------------------- | --------------------------------------------- |
| `/`            | `pages/home.php`           | Consumer view: all restaurants, filter by tag |
| `/reviews`     | `pages/consumer_entry.php` | Consumer view: single restaurant and reviews  |
| `/login`       | `pages/login.php`          | Sign in (admins and registered users)         |
| `/register`    | `pages/register.php`       | Create a user account                         |
| `/admin`       | `pages/admin_view.php`     | Admin view: all restaurants, filter by tag    |
| `/admin/entry` | `pages/admin_insert.php`   | Add a restaurant                              |
| `/admin/edit`  | `pages/admin_edit.php`     | Edit a restaurant, tag / untag                |

## Project Structure

```
router.php      Request router, static files, error pages
pages/          One file per route, plus the 404 page
includes/       Shared partials: headers, tiles, form fields, validation, DB and session helpers
public/         CSS, images, and uploaded restaurant images
db/             Schema (init.sql) and SQLite database
design-plan/    Wireframes, milestone write-ups, and the manual test checklist
```

## Roadmap

- Search bar for finding a specific restaurant
- Multiple simultaneous filters
- Display restaurant opening hours
- Change restaurant rating scale to tenths-decimals, from 0.0 to 10.0 (1.0, 1.1, 1.2, ...)

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for the full release history.
