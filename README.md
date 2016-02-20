LIB
===

PHP library for API for MySQL CRUD operation and Logger


VERSION:
--------
0.1.0


INSTALL:
--------

* Paste all these files and directories to your **SITE ROOT**
* Include **lib/init.inc.php** in your script to load the library


FEATURES:
---------
* Creates log of all error here **logs/log.txt**


USAGE:
------

**Database configuration**

Define MySQL hostname, username, password and database name here **lib/config.inc.php**

```php
defined('DB_HOST') ? null : define('DB_HOST', 'localhost');
defined('DB_USER') ? null : define('DB_USER', 'root');
defined('DB_PASS') ? null : define('DB_PASS', '');
defined('DB_NAME') ? null : define('DB_NAME', '');
```

**Test MySQL**

Start by creating a test table in your database

```mysql
CREATE TABLE IF NOT EXISTS CRUDClass (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO CRUDClass VALUES('','Name 1','name1@email.com');
INSERT INTO CRUDClass VALUES('','Name 2','name2@email.com');
INSERT INTO CRUDClass VALUES('','Name 3','name3@email.com');
```

**Update Example**

Use the following code to update rows in the database

```php
<?php
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
require_once('lib'.DS.'init.inc.php');

$db = new Database();
$db->update('CRUDClass',array('name'=>"Name 4",'email'=>"name4@email.com"),'id="1" AND name="Name 1"'); // Table name, column names and values, WHERE conditions
$res = $db->getResult();
print_r($res);
```

**Insert Example**

Use the following code to insert rows into the database

```php
<?php
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
require_once('lib'.DS.'init.inc.php');

$db = new Database();
$db->insert('CRUDClass',array('name'=>'Name 5','email'=>'name5@email.com'));  // Table name, column names and respective values
$res = $db->getResult();
print_r($res);
```

**Delete Example**

Use the following code to delete rows from the database with this class

```php
<?php
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
require_once('lib'.DS.'init.inc.php');

$db = new Database();
$db->delete('CRUDClass','id=5');  // Table name, WHERE conditions
$res = $db->getResult();
print_r($res);
```

**Full SQL Example**

Use the following code to enter the full SQL query

```php
<?php
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
require_once('lib'.DS.'init.inc.php');

$db = new Database();
$db->sql('SELECT id,name FROM CRUDClass');
$res = $db->getResult();
foreach($res as $output){
	echo $output["name"]."<br />";
}
```
