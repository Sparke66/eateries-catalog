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

## Update Semantics

AA.BB.CCC

A = Major UI Overhaul
B = Feature added/update
C = Minor fixes



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

# Update Logs

- Version 1.0.0 is fully implemented when the website demonstrates vital functionality
- Version 2.0.0 is fully implemented when the website is fully polished

## Update 1.3.7 (2-13-26)

- Consumer Entry Page under construction: testing features to add background images inside `<div>` containers

## Update 1.2.7 (2-13-26)

- Fixed the filter tags to return visual feedback when hovering over buttons. The cursor should now change into a hand icon or pointer when hovering over buttons.
- Added a button to reset the filter of the Homepage
- Removed unusual underlines between tags on filter bar.

## Update 1.2.4 (2-13-26)

- Added comments throughout the repository for better code readability

## Update 1.2.3 (1-21-26)

- Fixed an issue where text would not wrap around tile containers
- Changed the font family of the website

## Update 1.1.2 (1-20-26)

- Overhauled the Cuisine Filter Tab on the Consumer Homepage to follow formatting semantics
- Overhauled the formatting semantics of each restaurant tile
- Fixed an issue where the hyperlink encapsulated the entire tile. The hyperlink now only works by clicking on the image.
