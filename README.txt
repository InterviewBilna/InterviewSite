This is the final source code before the first launch of the interview site add
Bilna.com. The site aims to automate the interviewing of candidate developers,
in the hope that it will take significantly less time for the interview to occur
and significantly less set up.
The documentation of this specific website is in http://wiki.bilna.com/w/index.php/Moodle_interview_site
The documentation for installing moodle is attached below or follow the instructions here:
https://www.howtoforge.com/how-to-install-moodle-on-ubuntu-14.04#-links-
Things like setting up CodeRunner is link inside the the wiki documentation.

If this repo doesn't seem to work for you, download the original moodle.
After setting up moodle, go into the local folder, and then clone CodeRunner
from https://github.com/trampgeek/CodeRunner there. Follow the instructions of
the installation, and then after CodeRunner is checked up, copy the 
local/CodeRuner/type/coderunner/db/phpprototype.xml file into the respective folder
of your site.
Before you can use php, create a question, pick any random language, and click
customize. Open the advance customization tab, and then choose Yes (prototype)
Fill in the form (should be straigth forward). Make sure the template in the
customization block is:

{{STUDENT.ANSWER}}
{{TEST.testcode}}

Fill the rest of the form accordingly (if you don't really want to make a question
just enter random values in required fields), and then click save. Now PHP is an
option.

The rest of this README is the standard MOODLE README

==================================================================================
QUICK INSTALL
=============

For the impatient, here is a basic outline of the
installation process, which normally takes me only
a few minutes:

1) Move the Moodle files into your web directory.

2) Create a single database for Moodle to store all
   its tables in (or choose an existing database).

3) Visit your Moodle site with a browser, you should
   be taken to the install.php script, which will lead
   you through creating a config.php file and then
   setting up Moodle, creating an admin account etc.

4) Set up a cron task to call the file admin/cron.php
   every five minutes or so.


For more information, see the INSTALL DOCUMENTATION:

   http://docs.moodle.org/en/Installing_Moodle


Good luck and have fun!
Martin Dougiamas, Lead Developer

