<?php
/**
 *  LIB - PHP API for MYSQL CRUD operation and more
 *  
 *  Copyright (C) 2013  Deepak Adhikari <deeps.adhi@gmail.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along
 *  with this program; if not, write to the Free Software Foundation, Inc.,
 *  51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */


/**
 * Define the core paths
 * Loads necessary php files to initialize presentation2.0 (SITE) app
 */



/**
 * DIRECTORY_SEPERATOR is a PHP pre defined constant
 * \ for Windows and / for UNIX a like systems
 */
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);


/**
 * LIB directory for loading essential php files
 */
defined('LIB_PATH') ? null : define('LIB_PATH', __DIR__);


/**
 * Root directory of site 
 */
defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(LIB_PATH));


/**
 * Log directory of site
 * log.txt file in log folder
 */
defined('LOG_PATH') ? null : define('LOG_PATH', SITE_ROOT.DS.'logs');
defined('LOG_FILE') ? null : define('LOG_FILE', LOG_PATH.DS.'log.txt');


/**
 * Load basic functions
 */
require_once(LIB_PATH.DS.'functions.inc.php');


/**
 * Load core classses
 */ 
require_once(LIB_PATH.DS.'database.class.php');
require_once(LIB_PATH.DS.'log.class.php');


/**
 * Auotload class files
 */
spl_autoload_register('class_autoloader');
