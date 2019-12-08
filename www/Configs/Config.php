<?php
/**
 *
 * Main configuration params for RPF Application.
 *
 */

 //Default database type (supports mysql, firebird, sqlite):
$config['db']['type'] = 'sqlite';

// Mysql/MariaDb params:
$config['db']['host'] = 'localhost';
$config['db']['port'] = 3306;
$config['db']['dbname'] = 'world';

// Firebird params:
$config['db']['fbhost'] = 'localhost';
$config['db']['fbport'] = 3050;
// Path to database file
// for Windows:
// $config['db']['fbpath'] = 'C:\\Program Files\\Firebird\\Firebird_2_5\\examples\\empbuild\\EMPLOYEE.FDB';
// for xNIX:
// $_conf['db']['path'] = '/var/lib/firebird/2.5/data/employee.fdb';
$config['db']['fbpath'] = '/var/lib/firebird/2.5/data/employee.fdb';
$config['db']['fbrole'] = 'sysdba';

//Sqlite params:
$config['db']['sqlitepath'] = '/var/www/html/Data/Sample/Northwind.sl3';

// Database account:
$config['db']['username'] = '';
$config['db']['password'] = '';

// Template Engine Params:
$config['smarty']['template_dir'] = 'Templates/';
$config['smarty']['compile_dir']  = 'Templates_c/';
$config['smarty']['config_dir']   = 'Configs/';
$config['smarty']['cache_dir']    = 'Cache/';

// Default extension/action:
$config['default']['action'] = 'Sample/';

// URL rewrite enable (0/1):
$config['default']['url_rewrite'] = 1;
