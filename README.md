# Sales Portal

Sales Portal is a dashboard for the software product, Enteris: ENTERprise Index and Search (http://www.originssoft.com/enteris). 
Users can signup, buy subscription, make monthly payments, define payment details, perform content search and cancel subscription. The same portal is used by the admins to perform site administration.

The tasks performed by the administrators include defining feature elements, create user roles (using a set of feature elements), create and activate users, assign roles to the users, define payment methods, see payments and event history and a number of "to be decided" tasks. 

# Major Components

## Database as a service

The database operations are performed using the restify db restful service (see here https://restifydb.com). The RestifyDb provides a very easy to configure restful webservice for any database. The restful service for database is deployed at http://www.originssoft.com/api/dbenteris and is made accessible through the host www.originssoft.com only (using .htaccess). For inserting, updating and getting data from the restful service we simply use HTTP GET/PUT/POST requests from PHP. 
