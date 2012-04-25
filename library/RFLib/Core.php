<?php
 /**
 * RFLib
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
 * needs please refer to https://www.github.com/rickyfeng/wenda.git for more information.
 *
 * @category    RFLib
 * @package     RFLib_Core
 * @copyright   Copyright (c) 2010 Ricky Feng (ricky.feng@163.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version     $Id: Core.php 2010-05-14 19:08:44Z Ricky Feng $ 
 */
 
final class RFLib_Core
{
    /**
     * Registry collection
     *
     * @var array
     */	
	static private $_registry	= array();

    /**
     * Is installed flag
     *
     * @var bool
     */
    static private $_isInstalled;

    /**
     * Is developer mode flag
     *
     * @var bool
     */
    static private $_isDeveloperMode    = false;    
    
    /**
     * Register a new variable
     *
     * @param string $key
     * @param mixed $value
     */
    public static function register($key, $value)
    {
        if (!isset(self::$_registry[$key])) {
            self::$_registry[$key] = $value;
        }
        return self::$_registry[$key];
    }
    
    /**
     * Unregister a variable from register by key
     *
     * @param string $key
     */
    public static function unRegister($key)
    {
        if (isset(self::$_registry[$key])) {
            if (is_object(self::$_registry[$key])) {
                self::$_registry[$key]->__destruct();
            }
            unset(self::$_registry[$key]);
        }
    }    
    
    /**
     * Get a value from registry by a key
     *
     * @param string $key
     * @return mixed
     */    
    public static function getRegister($key)
    {
        if (isset(self::$_registry[$key])) {
            return self::$_registry[$key];
        }
        return null;
    }
    
    /**
     * Classes are named spaced using their module name
     *
     * @return string Module name
     */
    public static function getModule()
    {
    	$front = Zend_Controller_Front::getInstance();
    	
    	$request = $front->getRequest();
    	if (null === $request) {
    		$module = $request->getModuleName();
    	} else {
    		$module = $front->getDefaultModule();
    	}
    	
    	return ucfirst($module);
    }
    
    /**
     * Inflect the name using the inflector filter
     *
     * Changes camelCaseWord to Camel_Case_Word
     *
     * @param string $name The name to inflect
     * @return string The inflected string
     */
    public static function getInflected($name)
    {
        $inflector = new Zend_Filter_Inflector(':class');
        $inflector->setRules(array(
            ':class'  => array('Word_CamelCaseToUnderscore')
        ));
        return ucfirst($inflector->filter(array('class' => $name)));
    }
    
    /**
     * Get model by name
     * 
     * @param string $name Model name
     * @param string $module Module name
     * @return RFLib_Model_Abstract
     */
    public static function getModel($name , $module = null)
    {
        if (null === $module) {
            $module = self::getModule();
        }
		
        $class = self::getInflected($module . 'Model' . ucfirst($name));
        if (!self::getRegister($class)) {
            self::register($class, new $class());
        }
        return self::getRegister($class);
    }    

	/**
	 * Get service by name
	 * 
     * @param string $name Service name
     * @param string $module Module name
	 */    
	public static function getService($name, $module = null)
	{
		if (null === $module) {
			$module = self::getModule();
		}
		$class = self::getInflected($module . 'Service' . ucfirst($name));
		if (!self::getRegister($class)) {
			self::register($class, new $class());
		}
		return self::getRegister($class);
	}

	/**
	 * Get form by name
	 * 
     * @param string $name Service name
     * @param string $module Module name
	 */    
	public static function getForm($name, $module = null)
	{
		if (null === $module) {
			$module = self::getModule();
		}
		$class = self::getInflected($module . 'Form' . ucfirst($name));
		if (!self::getRegister($class)) {
			self::register($class, new $class());
		}
		return self::getRegister($class);
	}
	
    /**
     * Set enabled developer mode
     *
     * @param bool $mode
     * @return bool
     */
    public static function setIsDeveloperMode($mode)
    {
        self::$_isDeveloperMode = (bool)$mode;
        
        if (true === self::$_isDeveloperMode) {
            define('APPLICATION_ENV', 'development');
        } else {
            define('APPLICATION_ENV', 'production');
        }
        
        return self::$_isDeveloperMode;
    }

    /**
     * Retrieve enabled developer mode
     *
     * @return bool
     */
    public static function getIsDeveloperMode()
    {
        return self::$_isDeveloperMode;
    }
    
    /**
     * Retrieve application installation flag
     *
     * @param string|array $options
     * @return bool
     */
    public static function isInstalled()
    {
        if (null === self::$_isInstalled) {
            $configFile = APPLICATION_PATH . DS . 'configs' . DS . 'application.ini';

            self::$_isInstalled = false;

            if (is_readable($configFile)) {
                require_once 'Zend/Config/Ini.php';
                $config = new Zend_Config_Ini($configFile);
                date_default_timezone_set('UTC');
                if (($date = $config->production->installed->date) && strtotime($date)) {
                    self::$_isInstalled = true;
                }
            }
        }
        return self::$_isInstalled;
    }
    
    /**
     * Get base URL path by type
     *
     * @param string $type
     * @return string
     */
    public static function getBaseUrl()
    {
        list($protocol,$ver) = explode('/',$_SERVER['SERVER_PROTOCOL']);
        $host = strtolower($protocol) . '://'.$_SERVER['SERVER_NAME'] . ':' .$_SERVER['SERVER_PORT'].'/';
        
        $siteName = basename(WEB_ROOT);
        $uri = $_SERVER['REQUEST_URI'];
        
        if (!(false === strpos($uri, $siteName))) {
            $host .= $siteName;
        }
        
        return $host;
    }
}