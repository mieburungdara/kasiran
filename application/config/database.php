<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|   ['dsn']      The full DSN string describe a connection to the database.
|   ['hostname'] The hostname of your database server.
|   ['username'] The username used to connect to the database
|   ['password'] The password used to connect to the database
|   ['database'] The name of the database you want to connect to
|   ['dbdriver'] The database driver. e.g.: mysqli, postgre, odbc, etc.
|   ['dbprefix'] You can add an optional prefix, which will be added
|                to the table name when using the  Query Builder class
|   ['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|   ['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|   ['cache_on'] TRUE/FALSE - Enables/disables query caching
|   ['cachedir'] The path to the folder where cache files should be stored
|   ['char_set'] The character set used in communicating with the database
|   ['dbcollat'] The character collation used in communicating with the database
|                NOTE: For MySQL and MySQLi databases, this setting is only used
|                as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|                (and in table creation queries made with DB Forge).
|                There is an incompatibility in PHP with mysql_real_escape_string() which
|                can make your site vulnerable to SQL injection if you are using a
|                multi-byte character set and are running versions lower than these.
|                Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|   ['swap_pre'] A default table prefix that should be swapped with the dbprefix
|   ['encrypt']  Whether or not to use an encrypted connection.
|
|   ['compress'] Whether or not to use client compression (MySQL only)
|   ['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|                            - good for ensuring strict SQL while developing
|   ['ssl_key']  The path to the private key file
|   ['ssl_cert'] The path to the public key certificate file
|   ['ssl_ca']   The path to the certificate authority file
|
| The active record class requires a database connection.
| Specify the database connection group to make active
| Running multiple applications on one database requires
| specifying the active group in the controller:
|   $this->load->database('group_name');
*/
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'localhost', // Sesuaikan dengan host database Anda
    'username' => 'root',      // Sesuaikan dengan username database Anda
    'password' => '',          // Sesuaikan dengan password database Anda
    'database' => 'db_kasir_ci3', // Nama database yang akan kita buat
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
?>
