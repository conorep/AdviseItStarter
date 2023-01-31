# AdviseItStarter
  *Conor O'Brien*
  
  *Created in conjunction with SDEV 485 classwork*
  
  *Winter Quarter 2023*
 

#### This repo contains code for the first portion of the SDEV485 Advise-It tool project.
- The tool is to be used as an aid for advisors and students. 
- Advisors will create a one-year schedule with the student which will be reachable via a unique token generated for the schedule. 
- The schedule will be retrievable by that token by either the advisor or student. 
- The schedule will be editable by either the student or the advisor.


#### If testing the application, use the following login info to access the admin 'see all schedules' page:
- Admin email: conorepobrien@gmail.com
- Password: testing

Alternate:
- Admin username: admin
- Password: admin


#### CURRENT TODOS
##### HOT
- As an Advisor, I want to add the correct school years before and/or after the current year
  So that everyone can differentiate year vs year
  And have a plan that matches their AAS Data and Software class plan over 2-4  total years.
- As an Advisee,
  When I open my plan,
  I should see all of the school years where I have classes listed.
  If I have no classes listed,
  I should see the current School year.




##### LATER
- Map out how each view route works.
  - What session variables need to be present to load certain stuff?
  - Which jQuery functions get run when something happens?
- Think about implementing/not implementing a max char size for quarters
  - "How many lines of info should I allow? Infinite vs. 10...?"
  - Currently, creation error when posting quarters with massive amounts of text.