Installation
============

Server side
----------------------

* Install:
  * `sudo apt-get install postgresql libpq-dev postgresql-9.5-postgis postgresql-contrib`  (Debian 7)
  * `sudo apt-get install postgresql libpq-dev postgresql-9.3-postgis-2.1 postgresql-contrib` (Ubuntu 16.04)

* Enable extensions:
  * `sudo -u postgres psql`
  * `\password postgres` (after that set password for postgres user)
  * `CREATE USER username WITH SUPERUSER;`
  * `ALTER USER username PASSWORD 'your_password';`
  * `CREATE EXTENSION postgis;`
  * `CREATE EXTENSION postgis_topology;`
  * `CREATE EXTENSION fuzzystrmatch;`
  * `CREATE EXTENSION postgis_tiger_geocoder;`
  * `\q`

Client side (Windows 7)
----------------------

* Downloaded and installed latest pgadmin from http://www.pgadmin.org/download/windows.php (with installer)
* Downloaded PostGIS for windows from http://download.osgeo.org/postgis/windows/pg95/ and run bin\postgisgui\shp2pgsql-gui.exe
* Set up SSH tunnel on port 5432 to VM
* Imported .shp file using shp2pgsql-gui into (example dataset storing Australian LGAs is at
  https://github.com/thislittleduck/au-lga/tree/master/data)

References
----------

* http://www.pontikis.net/blog/postgresql-9-debian-7-wheez
* http://trac.osgeo.org/postgis/wiki/UsersWikiPostGIS21UbuntuPGSQL93Apt
* http://www.bostongis.com/blog/index.php?/archives/186-PostGIS-2.0.0-Shapefile-GUI-Loader-and-Exporter.html

Administration
--------------

Run init.sql to create table `datasets` for storing info about datasets.

After importing each dataset create a new record in `datasets` table, e.g.

    INSERT INTO public.datasets (name, geofield, token) VALUES ('lga11aaust', 'geom', 'a606c3e53600a16864619f8db83faa71');

Here `name` is dataset table name, `geofield` is a geometry field in this table to search agains, `token` is API key to access the
results, `origin` is an optional value for `Access-Control-Allow-Origin` set in the bot response. Thus each dataset can have own 
columns structure and own geometry field. Each result returned (see below) contains all dataset fields except geometry field itself.

Copy `example.settings.php` to `settings.php` and update if with actual Postgres connection settings.

Example request:

    http://bbot.lc/?dataset=lga11aaust&api_key=a606c3e53600a16864619f8db83faa71&lon=151.215913&lat=-33.874394.

Returns JSON with list of all results from given dataset for which given point is inside `geofield` geometry.
