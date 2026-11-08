# Changelog

All notable changes to this project are documented here. Dates are MM-DD-YY.

## Update 9 (11-08-26)

Requires rebuilding the database: delete `db/site.sqlite` so it is recreated from the new `init.sql`.

### Added

- Tag selection on the Add Restaurant page; the restaurant and its tags are saved together or not at all
- Tag categories (Cuisine-Type, Dietary, Affordability) via a new `category` column on `tags`
- Filter bars on the Homepage and Admin View are grouped by category, and several tags can be selected at once (shows restaurants matching any selected tag)
- Consumer Entry page lists the restaurant's tags under their category headings
- Average user rating, computed from reviews, shown on restaurant tiles and the Consumer Entry page alongside the critic rating
- Server-side tag validation on the Add and Edit pages: unknown or repeated tag IDs are rejected, and a restaurant can have at most one affordability tag
- Heights Falafel added to the seed data

### Changed

- Ratings (critic and user reviews) use a 0.0–10.0 scale with one decimal place, replacing whole numbers from 1 to 5; the review form uses a number field instead of a dropdown
- Average price is stored and shown in dollars and cents
- Tag multi-select moved into the shared form partial (`includes/restaurant-form-fields.php`) so the Add and Edit pages use the same field
- Query for all tags grouped by category moved into `get_tag_groups()` for the admin forms
- Manual test checklist updated for the 0.0–10.0 review rating and 13 seeded restaurants, with a new section for admin tagging

### Fixed

- Restaurants added from the Admin portal could not be given tags until they were edited

## Update 8 (10-13-26)

### Added

- Registration page (`/register`) for creating regular user accounts; new users are signed in and returned to the catalog
- Registration validation: username 3–30 letters, numbers, or underscores; password at least 8 characters; confirmation must match; taken usernames are rejected
- User roles (`admin` and `user`); only admins can open the Administrator Portal
- Reviews on the Consumer Entry page: signed-in users can post one rating (1–5) and review (up to 2,000 characters) per restaurant, listed newest first
- CSRF protection (double-submit cookie) on the registration and review forms
- Sign in / Register links in the public header, or the signed-in user and a Log out button
- `reviews` table, plus `role` and `created_at` columns on `users`
- Manual test checklist (`design-plan/checklist.md`)

### Changed

- Login page is now a general sign-in for all accounts; admins go to the portal and other users go to the catalog
- Logged-in users without the admin role are redirected from admin pages to the catalog
- Deleting a restaurant also deletes its reviews
- Usernames are case-insensitive, so "Admin" can't be registered alongside "admin"
- Review form keeps entered values after a validation error, and redirects after posting so refreshing doesn't resubmit
- Rewrote the README with features, setup, routes, and project structure; release history moved to this changelog
- Moved `extensions.txt` into `design-plan/`

### Removed

- Unused group-membership helpers and the old signup form renderer from `includes/sessions.php`

### Fixed

- Account creation referenced a `name` column that doesn't exist and passed an undefined messages variable to the login step

## Update 7 (09-25-26)

### Added

- Extension whitelist for uploads (JPG, JPEG, PNG)
- Content verification for image files, so text files renamed to `.jpg` are rejected
- Server-side upload size limit (1 MB), including files rejected by PHP before the size check
- Validation for the rest of the form: name and address required, rating a whole number from 1 to 5, average price a whole number of dollars
- Shared validation partial (`includes/restaurant-validation.php`) used by both the Add and Edit pages
- Shared headers for public and admin pages (`includes/header.php`, `includes/admin-header.php`)

### Changed

- Redesigned all seven pages to follow the wireframes
- Head elements of each page are now partials
- Admin forms keep entered values after a validation error
- Restaurants that don't exist now show the 404 page on the Consumer Entry and Admin Edit pages

### Fixed

- Failed upload checks still inserted the restaurant and saved the file
- The Edit page had no upload validation
- Invalid nesting of the filter bar
- Removing every tag from a restaurant on the Edit page now works
- Changing a restaurant's image to a different file type now deletes the old image
- Extra spaces were added around the description on every edit

## Update 6 (09-22-26)

### Fixed

- SQL injection vulnerabilities in the Consumer View and Admin View pages

## Update 5 (02-13-26)

### Added

- Consumer Entry page under construction, testing background images inside `<div>` containers

## Update 4 (02-13-26)

### Added

- Button to reset the filter on the Homepage

### Fixed

- Filter tags now give visual feedback on hover (pointer cursor)
- Removed unusual underlines between tags on the filter bar

## Update 3 (02-13-26)

### Changed

- Added comments throughout the repository for readability

## Update 2 (01-21-26)

### Changed

- Changed the font family of the website

### Fixed

- Text not wrapping inside tile containers

## Update 1 (01-20-26)

### Changed

- Overhauled the Cuisine Filter tab on the Consumer Homepage to follow formatting semantics
- Overhauled the formatting semantics of each restaurant tile

### Fixed

- The hyperlink encapsulated the entire tile; it now only works by clicking the image

## Earlier milestones (11-2025)

- Milestones 1–3 and Final: database schema, consumer filtering and individual entries, admin portal, design journey
