# Project 3, Milestone 3: **Team** Design Journey

[← Table of Contents](design-journey.md)


**Make the case for your decisions using concepts from class, as well as other design principles, theories, examples, and cases from outside of class (includes the design prerequisite for this course).**

You can use bullet points and lists, or full paragraphs, or a combo, whichever is appropriate. The writing should be solid draft quality.



## File Upload - Types of Files
> What types of files will you allow users to upload?
> Can users upload any type of file? Can user only upload one type of file?
> Or can users upload different types of files?
> List the file extensions of the types of files your users may upload.

- TODO: file type
- ...
When users complete a form to add a restaurant to the website, they will be allowed to upload one image. They will, tentatively, only be allowed to upload images with the jpeg extension (will need to explore png and svg and other formats).

## File Upload - Updated DB Schema
> Plan any updates you need to make to your database schema to support file uploads.
>
> 1. Copy your project DB Schema for the _entries_ table here.
> 2. Modify the schema to include any file upload information you desire to store in your database.
>    If you don't need to modify anything, explain why.

TODO: entries table name

```
TODO: updated _entries_ schema
```


## File Upload - File Storage
> Plan the file path to store the uploaded files on the server's file system.
> Store the uploaded files in a subfolder of the `public/uploads` folder using the _entries_ table name as the subfolder name.

TODO: file path to store uploaded media files for entries



## File Upload - Path and URL
> Assume that a user completed the insert/edit entry form.
> The **id** for the new record is **154**.
>
> 1. Plan the file system path to store the uploaded file.
> 2. Plan the URL to load the uploaded file in your website's HTML.

**File System Storage Path:**

```
TODO: file path
```

**Resource URL:**

```
<picture>
  <img src="TODO: uploaded file URL">
</picture>
```


## File Upload - Form Input
> Write the HTML of an `<input>` element that allows users to upload a file.
> Limit the types of files that a user may upload.

```html
TODO: file input element
```


## File Upload - PHP File Upload Data
> Use the `name` attribute of the file input you planned above to plan how you will
> access the uploaded file data in PHP using the `$_FILES` superglobal.

> Write the PHP code to access the uploaded file data from the `$_FILES` superglobal.
> Only include the data you will extract from the `$_FILES` superglobal. For example, the file name.
> Hint: <https://www.php.net/manual/en/features.file-upload.post-method.php>

```
$_FILES[TODO: file parameter name][TODO: file data]
```


## Insert Form - INSERT query
> Plan your query to insert an entry in your catalog.

```sql
TODO: INSERT query
```


## Insert Form - Sample Test Data
> Document sample test data to insert an entry in your catalog.
> Upload a sample file to the `design-plan` folder for us to upload when inserting the entry.

**Sample Insert Data:**

  - TODO: Field: Value
  - ...

**Sample Upload File:** `design-plan/TODO: sample file name`


## Edit Form - UPDATE query
> Plan your query to update an entry in your catalog.

```sql
TODO: UPDATE query
```


## Edit Form - Sample Test Data
> Document sample test data to edit an entry in your catalog.
> Upload a sample file to the `design-plan` folder for us to upload when editing the entry.

**Sample Edit Data:**

  - TODO: Field: Value
  - ...

**Sample Upload File:** `design-plan/TODO: sample file name`


## References

### Collaborators
> List any persons you collaborated with on this project.

TODO: list your collaborators


### Reference Resources
> Did you use any resources not provided by this class to help you complete this assignment? (Do not list the course resources or the Mozilla documentation.)
> List any external resources you referenced in the creation of your project. (i.e. ChatGPT, etc.)
>
> Provide the URL to the resources you used and include a short description of how you used each resource.

TODO: list reference resources


[← Table of Contents](design-journey.md)
