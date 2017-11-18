# Sales Portal

Sales Portal is a dashboard for the software product, Enteris: ENTERprise Index and Search (http://www.originssoft.com/enteris). 
Users can signup, buy subscription, make monthly payments, define payment details, perform content search and cancel subscription. The same portal is used by the admins to perform site administration.

The tasks performed by the administrators include defining feature elements, create user roles (using a set of feature elements), create and activate users, assign roles to the users, define payment methods, see payments and event history and a number of "to be decided" tasks. 

# Major Components

## Database as a service

The database operations are performed using the restify db restful service (see here https://restifydb.com). The RestifyDb provides a very easy to configure restful webservice for any database. The restful service for database is deployed at http://www.originssoft.com/api/dbenteris and is made accessible through the host www.originssoft.com only (using .htaccess). For inserting, updating and getting data from the restful service we simply use HTTP GET/PUT/POST requests from PHP. 

## API for Portal

The API for the portal is a stateful webservice that can be accessed after successful login for performing all the operations. The API contains all the business logic that executes on the server side. The folder sales-portal/api contains all the directory structure for all the APIs. Depending upon the logged in user, the role(s) assigned to the user are fetched and the feature elements are stored in the session. The different APIs require user to have certain feature elements to provide authorization so API responds with unauthorized access if a certain required feature element is not in the role(s) assigned to the user. The different menus and sections are made hidden from the user depending upon these feature elements. The API internally utilizes the database service to perform operations on the  database. The API also uses our Enteris Backend API to perform software related updates (searching, user activation, storage management etc). 

## Datatables 

The Datatables is an open source grid control for populating, sorting, searching and displaying data in a tabular form to the users (https://datatables.net).

## HttpRequester

The HttpRequester is used to perform http requests to the above mentioned rest webservices from the server side API. 

## Web Pages

There are five php pages in the root folder for signup, signin, password change/rest and dashboard. The index.php is the dashboard page that contains the side menu and the content section (that loads a page depending upon the selected menu). The user trying to do unauthorized access to a page (not having the required feature element) will be navigated to unauthorized page). All the contains pages are in the /pages folder.
