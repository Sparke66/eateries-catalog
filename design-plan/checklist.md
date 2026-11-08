Manual test checklist
Setup: delete db/site.sqlite and db/init.sql.checksum (this resets the DB to the seed data), then start the server.

1. Fresh DB

 / loads with 13 restaurants, and the header shows Sign in and Register.
2. Admin still works

 Sign in at /login as admin / monkey. You land on /admin.
 /admin, + Add New Restaurant and an edit page all load.
 Log out.
3. Registration with bad input (at /register)

 Submit the form empty. You see "Provide a username." and "Provide a password."
 Use username ab, then bad name!. You see the 3–30 characters / letters, numbers, underscores error, and the username stays filled in.
 Use password short1. You see "at least 8 characters".
 Use password password123 with confirmation password124. You see the mismatch error.
 Use username admin or ADMIN. You see "Username is already taken".
 In DevTools, change the hidden csrf_token value and submit. You see "Your form expired".
 Your browser offers to save or generate a password, which confirms the autocomplete attributes work.
4. Registration success

 Register alice / password123 / password123. You land on / and the header shows Signed in as alice and Log out.
 Visit /register and /login. Both redirect to /.
5. Not an admin

 Visit /admin, /admin/entry and /admin/edit?id=1. Each redirects to / with no 500.
6. Reviews

 Open /reviews?id=1. You see "No reviews yet." and the review form.
 Submit with no rating. The browser asks you to fill in the field. Remove required from the rating input in DevTools and submit again. You see "Rating must be between 0.0 and 10.0 with at most one decimal place."
 Submit with rating 7.5 but a blank body. You see "Write a few words…", and 7.5 stays filled in.
 In DevTools, change the rating input's type to text, then submit 10.1, 7.25, -1 and abc in turn. Each is rejected and the text you typed is kept.
 Post <b>hi</b> plus a second line with rating 8.5. It appears as 8.5 / 10 with the tags shown as literal text and the line break kept, and the User rating card shows 8.5 / 10. The form is replaced by "You've reviewed this restaurant."
 Refresh the page. The form isn't resubmitted.
 Re-enable the form in DevTools, or re-send the earlier POST, and try a second review. It's rejected and there's still one review.
 Review a different restaurant. That works.
7. Log out and cross-checks

 Log out. The header goes back to Sign in and Register, and the review page shows the sign-in prompt.
 Sign in as admin and delete a restaurant that has a review. It's removed without errors.
8. Admin tags (signed in as admin)

 /admin/entry shows a Tags field grouped into Cuisine-Type, Dietary and Affordability.
 Add a restaurant with Italian and Vegan selected. It appears on /admin, its edit page lists Italian and Vegan under Current Tags, and filtering / by Vegan includes it.
 Add a restaurant with both Inexpensive and Exquisite selected. You see "Choose at most one affordability tag.", nothing is added, and the fields and selected tags are kept (choose the image again).
 In DevTools, change a tag option's value to 999 and submit. You see "One or more selected tags do not exist." and nothing is added.
 On an edit page, select both Inexpensive and Exquisite and save. It's rejected and Current Tags is unchanged.
