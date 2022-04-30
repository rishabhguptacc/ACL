In our previous task of "Event" we have to enhance and implement ACL as follows

  1. Create a branch named as "ACL" from "master" branch in same repo where we have submitted "Event" task

  2. Create a page to add roles like "admin", "customer", "guest" etc

  3. Create a page to add components means controllers and their actions. (Its your choice how you want to do it)

  4. Create a page for ACL, where we will allow components for any role. (Its your choice how you want to do it) 
        a. Admin will have access to all (product add/edit, order add/edit, settings and all the above pages)
        b. manager will have access to product add/edit and order add/edit
        c. guest will have access to only product view

  5. use BeforeHandleRequest event to restrict access of component based on role provided in query parameter
