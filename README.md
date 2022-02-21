# Php-Competency-Matrix

The purpose of this code is to add the ability to track the levels of competency which users in the business/organisation have. 
It allows the employer to track what things users are trained in, compared to their coworkers. 

This is a project, developed for internal usage for a IT company. 
Developed soley by Skwangles. 


The stack is Mysql + Php + HTML5.
JQuery is used for AJAX to update user values in real time - it is automatically included with a script tag,
Mysqli is used for updating mysql,
JSON is used for AJAX,
The latest version of PHP is used that has access to mysqli. 

To setup
1. Make sure you have a Apache server, with SQL (Or any other server that works with AMP) 
2. In phpmyadmin import the comp.sql file which provides the base setup.
3. Make sure to update dhb.php in includes to contain the newest credentials
4. Sign in to the website with Admin/Admin @ the domain you have the website setup in (Xampp uses localhost) 
5. Modify your phpmyadmin login password so only specific people can access your database. 

Note: I built this using [XAMPP](https://www.apachefriends.org/index.html), copying all these files into htdocs located in the C:/XAMPP/ - so I used localhost & localhost/phpmyadmin to access the controls. 
