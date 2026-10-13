Manual test checklist
Setup: delete db/site.sqlite and db/init.sql.checksum (this resets the DB to the seed data), then start the server.

1. Fresh DB

 / loads with 12 restaurants, and the header shows Sign in and Register.
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
 Submit with no rating. You see "Choose a rating from 1 to 5."
 Submit with a rating but a blank body. You see "Write a few words…", and the rating stays selected.
 In DevTools, change an <option value> to 6 (also try 0 and abc) and submit. The rating is rejected and the text you typed is kept.
 Post <b>hi</b> plus a second line with rating 4. It appears with the tags shown as literal text and the line break kept. The form is replaced by "You've reviewed this restaurant."
 Refresh the page. The form isn't resubmitted.
 Re-enable the form in DevTools, or re-send the earlier POST, and try a second review. It's rejected and there's still one review.
 Review a different restaurant. That works.
7. Log out and cross-checks

 Log out. The header goes back to Sign in and Register, and the review page shows the sign-in prompt.
 Sign in as admin and delete a restaurant that has a review. It's removed without errors.
