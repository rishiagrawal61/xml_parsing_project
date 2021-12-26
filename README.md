# MVCClune Project.
## Introduction
This project is just as a demo for parsing XML data and displaying the same on the screen after processing & sanitizing.

## DataFlow
   Following are the routes available in the project.
   1. /master - Used for Parsing the XML file, filtering the data and after verification dumping to database in required format.
   2. /master/searchBookByAuthor - For listing all the authors available, on a click to which all the books published by that user will be displayed.

## Components
1. Database.
   Used phppgadmin for managing the database table through ***Postgres***
   Following tables are used for storing data to database.
   1. _Author_ - Used for storing the authors parsed from XML data.
   2. _Books_ - Used for storing the books for all the related authors parsed from XML data. Contains the foreign key relation from author table.

   ***<u>Note : </u>*** Boths the tables are auto incremented.

2. Directory Structure.
   1. ***App***
      * _Config_ -> Used for defining the routes and other configrations in the application, included from the require.php file.
      * _Controllers_ -> All the application logics are written inside the controllers defined in it, gets called from routes.php.
      * _Models_ -> All the business logic are written inside it, gets invoked from controller, when ever needed.
      * _Views_ -> All the master front end UI logic is written inside it, gets called from Controller, since following MVC architecture.
      * _libraries_ -> All the libraries like parent controller, core, database.php, Logger.php is written inside it to drive the system, all files gets called from ***require.php*** file.
         1. core.php -> Used to invoke the controller and sanitize the URL if some how it is compromised from routes.php
         2. controller.php -> used to create the instance of model and view so that those can be called from controller.
         3. database.php -> contains the methods required for database query to be executed.
         4. Logger.php ->contains the method's to write a logs to different locations as per the need.
   2. ***Public***
      * _css_ -> Used to include the Public CSS inside the application.
      * _javascript_ -> Used to include the Public JS inside the application.
      * _error_ -> All the error pages, resides inside it.
      * _img_ -> Image's to be shown in the application are stored in it.
      * _log_ -> Stores all the logs of the application, on top it stores the application error logs.
         1. _queryLogger_ -> used to store the log of all the querie's ran in the system.
         2. _requestLogs_ -> used to store all the request logs to the system.

3. To route to particular folder, please click on below links.
   * [/master](http://localhost/MVCCluneProject/master) - This will upload the XML data to the application.
   * [/master/searchBookByAuthor](http://localhost/MVCCluneProject/master/searchBookByAuthor) - To view what all bokks published by which author.

## System Design.
![MVCCluneProject SysDesg](http://localhost/MVCCluneProject/public/img/MVCCluneSysDesg.png "SysDesg")

## Security Aspects Covered.
   * SQL Injection.
   * Application path disclosure.
   * XSS Protection.
   * No-cache is enabled. etc etc.

## Usage
Directly Go to any one of the above routes defined it'll drive you to the application, defore opening please make sure your postgres extension is enabled, and postgres, apache is installed.

## License
Copyright (c) 2021, Rishi Agrawal.