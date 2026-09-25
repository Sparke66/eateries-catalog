# INFO 2300 - Project 2

Open this repository as a Codespace on GitHub (or as a container in VS Code.)

## Design Plan

Document your design and your plan in the [design journey](design-plan/design-journey.md).

## Guide

- "/"            => "pages/home.php",           // consumer view all / filterby tag
- "/reviews"     => "pages/consumer_entry.php", // consumer entry details
- "/admin"       => "pages/admin_view.php",     // admin view all / filter bytag
- "/admin/entry" => "pages/admin_insert.php",   // admin insert entry
- "/admin/edit"  => "pages/admin_edit.php",     // admin edit entry / tag /untag
- "/login"       => "pages/login.php"           // login

## Known Issues

- Explicit visual feedback from mouse when hovering over certain regions outside the bounds of the filter buttons. Has to do with the way the `<a>` tags are structured
- Administrator Portal requires huge overhaul
- Individual view of restaurants need to be redesigned
- The filter bar element needs some adjustment - recommend different color, rounding of edges, shadowing
- Restaurant tiles should also have edge rounding and shadowing
- Each button in the filter bar could also use shadowing
- Consumer Entry page under construction to test background images within `<div>` containers

## Proposed Future Additions

- Search bar function to search for a specific restaurant
- Implement multiple filters
- Show the opening hours of the restaurant
-

## Update 7 (09-25-26)

- Added an extension whitelist to certain file upload formats (JPG, JPEG, PNG)
- Added content verification for image files, so text files renamed to `.jpg` are rejected
- Added server-side file size limit (1 MB), including files rejected by PHP before they reach the size check
- Fixed an issue where failed upload checks still inserted the restaurant and saved the file
- Moved form validation into a shared partial (`includes/restaurant-validation.php`) used by both the Add and Edit pages. The Edit page previously had no upload validation
- Added validation for the rest of the form: name and address are required, rating must be a whole number from 1 to 5, and average price must be a whole number of dollars
- Admin forms now keep entered values after a validation error
- Finished implementation of the head elements of each page as partials
- Fixed the invalid nesting of the filter bar
- Redesigned all seven pages to follow the wireframes, with shared headers for public and admin pages (`includes/header.php`, `includes/admin-header.php`)
- Restaurants that don't exist now show the 404 page on the Consumer Entry and Admin Edit pages
- Removing every tag from a restaurant on the Edit page now works
- Changing a restaurant's image to a different file type now deletes the old image
- Fixed extra spaces being added around the description every time a restaurant was edited

## Update 6 (9-22-26)

- Fixed SQL Injection vulnerabilities within the Consumer View Page and Admin View Page

## Update 5 (2-13-26)

- Consumer Entry Page under construction: testing features to add background images inside `<div>` containers

## Update 4 (2-13-26)

- Fixed the filter tags to return visual feedback when hovering over buttons. The cursor should now change into a hand icon or pointer when hovering over buttons.
- Added a button to reset the filter of the Homepage
- Removed unusual underlines between tags on filter bar.

## Update 3 (2-13-26)

- Added comments throughout the repository for better code readability

## Update 2 (1-21-26)

- Fixed an issue where text would not wrap around tile containers
- Changed the font family of the website

## Update 1 (1-20-26)

- Overhauled the Cuisine Filter Tab on the Consumer Homepage to follow formatting semantics
- Overhauled the formatting semantics of each restaurant tile
- Fixed an issue where the hyperlink encapsulated the entire tile. The hyperlink now only works by clicking on the image.
