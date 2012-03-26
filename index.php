<?php
/**
 * Wenda
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to ricky.feng@163.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Wenda to newer
 * versions in the future. If you wish to customize Wenda for your
 * needs please refer to https://github.com/rickyfeng/wenda.git for more information.
 *
 * @category   Wenda
 * @package    Wenda
 * @copyright  Copyright (c) 2010 Ricky Feng (ricky.feng@163.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

// Check PHP version
if (version_compare(phpversion(), '5.2', '<')) {
    die('ERROR: Your PHP version is ' . phpversion() . '. Wenda requires PHP 5.2.0 or newer.');
}

// Error reporting
error_reporting(E_ALL | E_STRICT);

// Define root path
define('WEB_ROOT', realpath(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);
define('APPLICATION_PATH', WEB_ROOT . DS . 'application');
define('LIBRARY_PATH', WEB_ROOT . DS . 'library');
define('VAR_PATH', WEB_ROOT . DS . 'var');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, 
        array(LIBRARY_PATH, get_include_path()))
);

// Set application mode
require_once 'RFLib/Core.php';
RFLib_Core::setIsDeveloperMode(true);

/**
 * Zend_Application
 */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
        APPLICATION_ENV, 
        APPLICATION_PATH . DS . 'configs' . DS . 'application.ini'
);

$application->bootstrap()->run();