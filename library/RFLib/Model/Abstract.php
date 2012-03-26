<?php
abstract class RFLib_Model_Abstract
{
    /**
     * Model cachemanager name
     */
    const CACHE_NAME = 'model';

    /**
     * @var array Class methods
     */
    private $_classMethods;

    /**
     * @var SF_Model_Cache_Abstract
     */
    protected $_cache;

    /**
     * @var array cache options
     */
    protected $_cacheOptions = array();

    /**
     * @var array Model table instances
     */
    protected $_tables = array();

    /**
     * @var array Form instances
     */
    protected $_forms = array();

    /**
     * Constructor
     *
     * @param array|Zend_Config|null $options
     * @return void
     */
    public function __construct($options = null)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        if (is_array($options)) {
            $this->setOptions($options);
        }

        $this->init();
    }

    /**
     * Constructor extensions
     */
    public function init()
    {
    }

    /**
     * Set options using setter methods
     *
     * @param array $options
     * @return RFLib_Model_Abstract
     */
    public function setOptions(array $options)
    {
        if (null === $this->_classMethods) {
            $this->_classMethods = get_class_methods($this);
        }

        foreach($options as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (in_array($method,$this->_classMethods)) {
                $this->$method($value);
            }
        }

        return $this;
    }

    /**
     * Set the cache to use
     *
     * @param RFLib_Model_Cache_Abstract $cache
     */
    public function setCache(RFLib_Model_Cache_Abstract $cache)
    {
        $this->_cache = $cache;
    }

    /**
     * Set the cache options
     *
     * @param array $options
     */
    public function setCacheOptions(array $options)
    {
        $this->_cacheOptions = $options;
    }

    /**
     * Get the cache options
     *
     * @return array
     */
    public function getCacheOptions()
    {
        if (empty($this->_cacheOptions)) {
            $manager = Zend_Controller_Front::getInstance()
            ->getParam('bootstrap')
            ->getPluginResource('cachemanager')
            ->getCacheManager();
            if ($manager->hasCache(self::CACHE_NAME)) {
                $options = $manager->getCacheTemplate(self::CACHE_NAME);
                $this->_cacheOptions = array(
                        'frontend'        => $options['frontend']['name'],
                        'frontendOptions' => $options['frontend']['options'],
                        'backend'         => $options['backend']['name'],
                        'backendOptions'  => $options['backend']['options'],
                );
            } else {
                throw new RFLib_Model_Exception("Can not find mode cachemanger");
            }
        }
        return $this->_cacheOptions;
    }

    /**
     * Query the cache
     *
     * @param string $tagged The tag to save data to
     * @return RFLib_Model_Cache_Abstract
     */
    public function getCached($tagged = null)
    {
        if (null === $this->_cache) {
            $this->_cache = new RFLib_Model_Cache($this, $this->getCacheOptions(), $tagged);
        }
        $this->_cache->setTagged($tagged);
        return $this->_cache;
    }

    /**
     * Classes are named spaced using their module name
     * this returns that module name or the first class name segment.
     *
     * @return string This class namespace
     */
    private function _getNamespace()
    {
        $ds = explode('_',get_class($this));
        return $ds[0];
    }

    /**
     * Get a DbTable
     *
     * @param string $name
     * @return RFLib_Model_DbTable_Abstract
     */
    public function getTable($name)
    {
        if (!isset($this->_resources[$name])) {
            $class = join('_', array(
                    $this->_getNamespace(),
                    'Model_DbTable',
                    RFLib_Core::getInflected($name)
            ));
            $this->_tables[$name] = new $class();
        }
        return $this->_tables[$name];
    }

    /**
     * Get a Form
     *
     * @param string $name
     * @return Zend_Form
     */
    public function getForm($name)
    {
        if (!isset($this->_forms[$name])) {
            $class = join('_', array(
                    $this->_getNamespace(),
                    'Form',
                    RFLib_Core::getInflected($name)
            ));
            $this->_forms[$name] = new $class(array('model' => $this));
        }
        return $this->_forms[$name];
    }
}