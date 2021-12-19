--DATABASE VERSION
 --before logging on
	psql \-\-v
	
--if logged ON
SELECT version();

--create user 
--create user, grant roles and set password
CREATE USER opsman WITH CREATEDB CREATEROLE LOGIN ENCRYPTED PASSWORD 'terrence';
--modify user
ALTER USER opsman  WITH  CREATEROLE;
--grant privileges to user 

--create database

--connect to database
psql -U postgres;	--connects to the default database

psql -U new_manager -d postgres -W --connects to the default database with non-default user

--previous command
\g

--list databases
\(l)ist

--list users(roles)
\d(u)ser

--list tables
\d(t)able

--switching databases
\(c)onnect customers --switches to a database called customers

Connection
  \c[onnect] {[DBNAME|- USER|- HOST|- PORT|-] | conninfo}
                         connect to new database (currently "postgres")
  \conninfo              display information about current connection
  \encoding [ENCODING]   show or set client encoding
  \password [USERNAME]   securely change the password for a user

Operating System
  \cd [DIR]              change the current working directory
  \setenv NAME [VALUE]   set or unset environment variable
  \timing [on|off]       toggle timing of commands (currently off)
  \! [COMMAND]           execute command in shell or start interactive shell
--display constraints

--describe tables
\d lesson 	--decribes a table called lesson

--display table contents

--display current user
SELECT CURRENT_USER;

--display current machine
coninfo

--display current database

--display current date
SELECT CURRENT_DATE;

--date +day, week,month,year

--date formats

--STRING OPERATIONS

--concatenate

--conversion

--SPACE MANAGEMENT
\db[+]			--tablespaces
