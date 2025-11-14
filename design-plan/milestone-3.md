# Project 3, Milestone 3: **Team** Design Journey

[← Table of Contents](design-journey.md)


**Make the case for your decisions using concepts from class, as well as other design principles, theories, examples, and cases from outside of class (includes the design prerequisite for this course).**

You can use bullet points and lists, or full paragraphs, or a combo, whichever is appropriate. The writing should be solid draft quality.



## File Upload - Types of Files
> What types of files will you allow users to upload?
> Can users upload any type of file? Can user only upload one type of file?
> Or can users upload different types of files?
> List the file extensions of the types of files your users may upload.

When users complete a form to add a restaurant to the website, they will be allowed to upload one image. They will, tentatively, only be allowed to upload images with the jpeg, jpg, and png extensions.

## File Upload - Updated DB Schema
> Plan any updates you need to make to your database schema to support file uploads.
>
> 1. Copy your project DB Schema for the _entries_ table here.
> 2. Modify the schema to include any file upload information you desire to store in your database.
>    If you don't need to modify anything, explain why.

entries table name: restaurants

```
CREATE TABLE "restaurants" (
    "id" INTEGER NOT NULL UNIQUE,
    "name" TEXT UNIQUE NOT NULL,
    "address" TEXT UNIQUE NOT NULL,
    "rating" INTEGER,
    "avg_price" INTEGER,
    "description" TEXT,
    "file_ext" TEXT NOT NULL,
    PRIMARY KEY ("id" AUTOINCREMENT)
);
```


## File Upload - File Storage
> Plan the file path to store the uploaded files on the server's file system.
> Store the uploaded files in a subfolder of the `public/uploads` folder using the _entries_ table name as the subfolder name.

public/uploads/restaurants


## File Upload - Path and URL
> Assume that a user completed the insert/edit entry form.
> The **id** for the new record is **154**.
>
> 1. Plan the file system path to store the uploaded file.
> 2. Plan the URL to load the uploaded file in your website's HTML.

**File System Storage Path:**

```
public/uploads/restaurants/154.jpeg
```

**Resource URL:**

```
<picture>
  <img src="public/uploads/restaurants/154.jpeg">
</picture>
```


## File Upload - Form Input
> Write the HTML of an `<input>` element that allows users to upload a file.
> Limit the types of files that a user may upload.

```html
<input id="restaurant-image" type="file" name="restaurant-image" accept=".jpeg, .jpg, .png">
```


## File Upload - PHP File Upload Data
> Use the `name` attribute of the file input you planned above to plan how you will
> access the uploaded file data in PHP using the `$_FILES` superglobal.

> Write the PHP code to access the uploaded file data from the `$_FILES` superglobal.
> Only include the data you will extract from the `$_FILES` superglobal. For example, the file name.
> Hint: <https://www.php.net/manual/en/features.file-upload.post-method.php>

```
$_FILES['jpeg-file']['name']
$_FILES['jpeg-file']['tmp_name']
$_FILES['jpeg-file']['size']
$_FILES['jpeg-file']['error']
```


## Insert Form - INSERT query
> Plan your query to insert an entry in your catalog.

```sql
INSERT INTO restaurants (name, address, rating, avg_price, description, file_ext)
VALUES (:name, :address, :rating, :avg_price, :description, :file_ext);
```


## Insert Form - Sample Test Data
> Document sample test data to insert an entry in your catalog.
> Upload a sample file to the `design-plan` folder for us to upload when inserting the entry.

**Sample Insert Data:**

- name: The Golden Spoon
- address: 445 Triphammer Rd
- rating: 4
- avg_price: 22
- description: A cozy Mediterranean restaurant featuring fresh seafood and grilled dishes with a beautiful waterfront view.
- file_ext: png

**Sample Upload File:** `design-plan/golden-spoon.png`


## Edit Form - UPDATE query
> Plan your query to update an entry in your catalog.

```sql
UPDATE restaurants
SET name = :name,
    address = :address,
    rating = :rating,
    avg_price = :avg_price,
    description = :description,
    file_ext = :file_ext
WHERE id = :id;
```


## Edit Form - Sample Test Data
> Document sample test data to edit an entry in your catalog.
> Upload a sample file to the `design-plan` folder for us to upload when editing the entry.

**Sample Edit Data:**

- id: 1 (Pasta Palace)
- name: Pasta Palace Ristorante
- address: 123 Noodle St, Suite 5
- rating: 5
- avg_price: 25
- description: Award-winning Italian cuisine with homemade pasta and wood-fired pizzas in an elegant setting.
- file_ext: png

**Sample Upload File:** `design-plan/pasta-palace-updated.png`


## References

### Collaborators
> List any persons you collaborated with on this project.

n/a


### Reference Resources
> Did you use any resources not provided by this class to help you complete this assignment? (Do not list the course resources or the Mozilla documentation.)
> List any external resources you referenced in the creation of your project. (i.e. ChatGPT, etc.)
>
> Provide the URL to the resources you used and include a short description of how you used each resource.

- ChatGPT 5 <https://chatgpt.com>
  - ChatGPT 5 was utilized to fabricate storefront images for restaurant seed data
- Mozilla Reference Documentation <https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/textarea>
  - Mozilla Documentation was utilized to explore ideas to handle larger blocks of text

[← Table of Contents](design-journey.md)
