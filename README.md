# Inventory
Inventory system for assets

This is a simple asset management system. I created this since none of the existing ones were simple enough, or requiring frameworks or docker to run.

It has 8 fields for the asset:
* Brand
* Model
* Serialnumber
* Eur (value)
* Location
* Device (type of)
* Comment
* Acquisition date

The brand, location and device are separate tables, that you can select from a dropdown list.

The database is designed for MariaDB, it might work for others as well.

* To install, create an empty database and import the schema from the schema.sql file.
* Copy the archive to some webspace and extract it.
* Edit db.php and add your database login details. Remote database has not been tested.
* Modify .htaccess so that AuthUserFile has an absolute path to .htpasswd, otherwise it will not work. (Ex. AuthUserFile /home/public_html/.htpasswd)
* Modify title.inc to reflect the title of the system. This is shown on all pages and in the Title of tha tab.

The default user is admin with the password password . Change these as soon as possible in any production enviroment!

Good to know:
* Every time you change the location it is recorded in a separate location table. You can view the history.
* Every time you make a change in any other field it is recorded in the Log table. Currently you can only view it directly in the database.
