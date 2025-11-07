# Project 2, Milestone 2: Design Journey

[← Table of Contents](design-journey.md)


**Make the case for your decisions using concepts from class, as well as other design principles, theories, examples, and cases from outside of class (includes the design prerequisite for this course).**

You can use bullet points and lists, or full paragraphs, or a combo, whichever is appropriate. The writing should be solid draft quality.


## Consumer: Filtering by Tag
> Design the URL for filtering by a tag on the view all page for the consumer.
> What is the URL for filtering by a tag?

/?tag=tag_name

> What query string parameters will you include in the URL?

| Query String Parameter Name       | Description                                |
| --------------------------------- | -----------------                          |
| tag                               | Name of tag/cuisine type to filter data by |
|                                   |                                            |
|                                   |                                            |


## Consumer: Details Page URL
> Design the URL for the consumer's detail page.
> What is the URL for the detail page?

/reviews/rest_name?id=restaurant_id

> What query string parameters will you include in the URL?

| Query String Parameter Name       | Description                                         |
| --------------------------------- | -----------------                                   |
| page                              | Page to display for consumer_entry for details page |
| id                                | unique identifier for each restaurant               |
|                                   |                                                     |


## Administrator: Filtering by Tag
> Design the URL for filtering by a tag on the administrator's view all page.
> What is the URL for filtering by a tag?

/admin?tag=tag_name

> What query string parameters will you include in the URL?

| Query String Parameter Name       | Description                           |
| --------------------------------- | -----------------                     |
| tag                               | Name of tag/cuisine type to filter by |
|                                   |                                       |
|                                   |                                       |


## Administrator: Edit Page URL
> Design the URL for the administrator's edit page.
> What is the URL for the administrator's edit page?

/admin/edit?id=restaurant_id

> What query string parameters will you include in the URL?

| Query String Parameter Name       | Description                             |
| --------------------------------- | -----------------                       |
| id                                | unique identifier of restaurant to edit |
|                                   |                                         |
|                                   |                                         |


## SQL Query Plan
> Plan the SQL query to retrieve all entry records for a specific tag (i.e. tag 100).

```
SELECT * FROM restaurants
WHERE (tags.name = tag_name)
```


> Plan the SQL query to retrieve a record (i.e. record 1). (Do not retrieve tags.)

```
SELECT * FROM restaurants
WHERE id = :id;
```

> Plan the SQL query to retrieve all tag names for record 1.

```
SELECT * FROM restaurants
WHERE id = 1;
```


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
  - ChatGPT 5 was utilized to implement seed data for the database


[← Table of Contents](design-journey.md)
