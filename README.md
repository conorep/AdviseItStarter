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


#### CURRENT TODOS
- Schedule retrieval via URI doesn't toggle disable properly for header buttons.
- URI should/could remove token after retrieval of schedule.
- Map out how each view route works.
  - What session variables need to be present to load certain stuff?
  - Which jQuery functions get run when something happens?
- ERROR HANDLING IN GENERAL!
  - The admin login needs error handling (not just page reload without error report when entering non-existent email or wrong pass).
  - No error handling on update/new schedule submit when adding more than a certain amount of characters to any given quarter.
- Think about implementing/not implementing a max char size for quarters
  - "How many lines of info should I allow? Infinite vs. 10...?"
  - Currently, creation error when posting quarters with massive amounts of text.
- Make a specific view route for retrieving a schedule via URI...?