# Project 2, Milestone 1: Design Journey

[← Table of Contents](design-journey.md)


**Make the case for your decisions using concepts from class, as well as other design principles, theories, examples, and cases from outside of class (includes the design prerequisite for this course).**

You can use bullet points and lists, or full paragraphs, or a combo, whichever is appropriate. The writing should be solid draft quality.


## Catalog
> What will your catalog website be about? (1 sentence)

This catalog will feature a list of food establishments located throughout Ithaca/Tompkins County.

## Consumer Planning

### Consumer: _Cohesive_ Audience
> Briefly explain your site's **consumer** audience.
> Your audience should be specific, but not arbitrarily specific.

The website's audience consists of residents, students, and visitors within the Ithaca area searching for the best local eateries.

> Be specific and justify why this audience is a **cohesive** group. (1-2 sentences)

They share a common goal of finding quality dining options within an isolated college town.

### Consumer: Audience Goals
> Document your **consumer** audience's goals.
> List each goal below. There is no specific number of goals required for this, but you need
> enough to do the job (Hint: It's at least 1, but probably no more than 3).

Source: ChatGPT 5

- ChatGPT 5 was used to explore additional goals of the consumer audience.

- Goal 1: Discover the best restaurants and cafés in Ithaca.
- Goal 2: Read background information, reviews, and menu highlights for specific food places.
- Goal 3: Easily compare options based on cuisine type, price range, and proximity.

### Consumer: Persona
> Use the goals you identified to develop a persona of your site's **consumer** audience.
> Your persona must have a name and a face. The face can be a photo of a face or a drawing, etc.
> You may type out the persona below with bullet points or include an image of the persona.
> Just make sure it's easy to read the persona when previewing markdown.

Source: ChatGPT 5

- ChatGPT 5 was utilized for the generation of the persona face, as well as inspiration for his biography

![image](/design-plan/alex.png)

Persona's Name: Alex Chen, 21(M)

- Occupation: University Student
- Residence: Toni Morrison Hall
- Hobbies: reading manga, hanging out with friends, outdoor concerts/raving

Alex hails from Flushing, New York, where he attended a community college, and was fortunate enough to transfer into the prestigious Cornell University. Throughout his youth, Alex was exposed to countless culinary ventures within the massive cultural boiling pot that is the Big Apple.

Now within an isolated college town, Alex has regularly expressed concerns of the lack of energy that can be matched by the local eateries. However, a small glimmer of hope shines from within, as he seeks to find the best places to dine in with the close friends he will potentially make.

### Consumer: Narrow or Wide Screen
> How will your **consumer** user access this website? From a narrow or wide screen?

Alex will frequently access this website through his mobile device (ie. a narrow screen)

## Administrator Planning

### Administrator: _Cohesive_ Audience
> Briefly explain your site's **administrator** audience.
> Your audience should be specific, but not arbitrarily specific.

Source: ChatGPT 5

- ChatGPT 5 was utilized to explore the background and goals of the administrator audience.

TODO: Tips: think of administrator as a voluntary contributor (ie. wikipedia, fandom, etc.)

The administrator audience comprise of longer-term residents of the Ithaca/Tompkins County area who are deeply familiar with the local dining scene and take pride in sharing their culinary insights with others.

> Be specific and justify why this audience is a **cohesive** group. (1-2 sentences)

The audience shares longevity in the community and a passion for food culture, which puts them in a position to curate a collection of local dining knowledge and ensure others can benefit without having to go through the trial and error of trying place by place.

### Administrator: Audience Goals
> Document your **administrator** audience's goals.
> List each goal below. There is no specific number of goals required for this, but you need
> enough to do the job (Hint: It's at least 1, but probably no more than 3).

- Goal 1: Share knowledge and recommendations of quality eateries with the Ithaca community
- Goal 2: Monitor and update essential restaurant information.
- Goal 3: Ensure that the database remains accurate and reflective of current dining options.

### Administrator: Persona
> Use the goals you identified to develop a persona of your site's **administrator** audience.
> Your persona must have a name and a face. The face can be a photo of a face or a drawing, etc.
> You may type out the persona below with bullet points or include an image of the persona.
> Just make sure it's easy to read the persona when previewing markdown.

Source: ChatGPT 5

- ChatGPT 5 was utilized for the generation of the persona face, as well as inspiration for his biography

Persona's Name: Morgan Li, 42(M)

Occupation: Librarian
Residence: Ithaca, New York
Hobbies: hiking, reading, strolling in the local park, spending time with his family

Happily married and raising a family of three children, Morgan works at the Tompkins County Public Library. Alex and his family has lived in Ithaca for almost two decades, ever since he settled into the area for his undergraduate and post-graduate studies.

With this being said, Alex knows the ins and outs of every dinery within the local area, particularly the Ithaca Commons. Reminiscing of his days as a freshman, when he did not receive any guidance from peers and had to find out everything by himself, Alex made it one of his hobbies to become a voice on which eateries to try out for those new to the area.


### Administrator: Narrow or Wide Screen
> How will your **administrator** user access this website? From a narrow or wide screen?

Morgan will access this website from a wide screen for the more comprehensive tasks; HOWEVER, he has the capability of accessing from the narrow screen as he is an avid participator of the website.


## Catalog Design
> Sketch each page of your entire media catalog website
> Provide a brief explanation _underneath_ each sketch. (1 sentence per sketch)
> **Refer to consumer or administrator persona by name in each explanation.**

TODO: sketch(es) + explanation


## Catalog Design Patterns
> Explain how your design employs common catalog design patterns. (1-2 sentences)

TODO: design pattern explanation


## URL Design
> Plan your HTTP routing.
> List each route and the PHP file for each route.

| Page                                     | Route              | PHP File                 |
| ---------------------------------------- | -----------        | --------------           |
| home / consumer view all / filter by tag | /                  | pages/home.php           |
| consumer entry details                   | /reviews/rest_name | pages/consumer_entry.php |
| admin view all / filter by tag           | TODO: route        | TODO: php file           |
| admin insert entry                       | TODO: route        | TODO: php file           |
| admin edit entry / tag / untag           | TODO: route        | TODO: php file           |
| login                                    | /login             | TODO: php file           |

> Explain why these routes (URLs) are usable for your persona. (1 sentence)

TODO: justification of routing design



## Database Schema
> Plan the structure of your database. You may use words or a picture.
> A bulleted list is probably the simplest way to do this.
> Include constraints for each field.

TODO: NEED THREE TABLES!!!

**Table:** TODO: table name

- field1: TYPE {constraints...},
- field2: ...
- TODO: table fields + type + constraints

table1 fields: id, name of restaurant, address of restaurant,

table2 fields: id, user, favorite dish, rating, price, comment

table3 fields: id, restaurant_id, user_id
(table3 should link table1 and table2 together)

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
  - ChatGPT 5 was utilized to explore audience and administrator goals for the website, and for image generation of the corresponding personas.


[← Table of Contents](design-journey.md)
