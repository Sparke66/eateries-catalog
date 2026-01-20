# INFO 2300 - Project 2

Open this repository as a Codespace on GitHub (or as a container in VS Code.)

## Design Plan

Document your design and your plan in the [design journey](design-plan/design-journey.md).

## Guide

"/"            => "pages/home.php",           // consumer view all / filterby tag
"/reviews"     => "pages/consumer_entry.php", // consumer entry details
"/admin"       => "pages/admin_view.php",     // admin view all / filter bytag
"/admin/entry" => "pages/admin_insert.php",   // admin insert entry
"/admin/edit"  => "pages/admin_edit.php",     // admin edit entry / tag /untag
"/login"       => "pages/login.php"           // login

AA.BB.CCC

A = Major UI Overhaul
B = Feature added/update
C = Minor fixes

## Update 1-20-26

- Overhauled the Cuisine Filter Tab on the Consumer Homepage to follow formatting semantics
- Overhauled the formatting semantics of each restaurant tile
- Fixed an issue where the hyperlink encapsulated the entire tile. The hyperlink now only works by clicking on the image.

## Known Issues

- Inconsistent sizing of each tile on the Homepage; may need to evaluate how to wrap text
- Incorrect font for the theme of the website
- No visual feedback when hovering over buttons on Cuisine Filter Bar
- No current method to "Select All" or select default view after viewing specific cuisine type
- Unusual underlines between tags on filter bar. Explicit visual feedback from mouse when hovering over underline, but no visual feedback over button
- Administrator Portal requires huge overhaul
