# user-ranking-system
Web application for ranking users by number of solved tasks

This is a simple web application for sorting users according to the number of solved tasks. It was originally created to be used at Azerbaijan Olympiad Preparation September camp where I was one of the trainers. The problems we used were from [Usaco training](https://train.usaco.org) website. As we couldn't see students results I decided to build this application.

### Installation and Usage
Downloading the files to web folder should be ebough for getting the system running. The "db.php" file should be edited to satisfy your needs. After that accessing the web page should create all necessary databases and tables at database server. At this point, the website may look ugly, but that is because no tasks has been added. The tasks should be added manually to the specified database and table. Users can add themselves using the "add youself" pane at the bottom of the screen.

After the system is running, users can toggle their status of solving a task by clicking on the corresponding cell in the table. They will be required to enter the password for their account, which is given during adding themselves to the table, in order to toggle their status. Note that, password will only be required once for verification.

### Features
* Automatic user sorting according to the most number of solved tasks
* Spam protection by IP
* Password protection for accounts
* Equal ranks for two users with the same number of solved tasks
