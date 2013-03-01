<?php

class YiiTestCache extends CCache
{
  protected $_cache = [];
  protected $_expires = [];

  public function init()
  {
    parent::init();
    $this->_cache = [];
    $this->_expires = [];
  }

  public function getUniqueKey($key)
  {
    return $this->generateUniqueKey($key);
  }

  public function getCacheData()
  {
    return $this->_cache;
  }

  public function getCacheSize()
  {
    return count($this->_cache);
  }

  public function getExpire($key)
  {
    $key = $this->generateUniqueKey($key);
    if(isset($this->_expires[$key]))
      return $this->_expires[$key];
    return null;
  }

  protected function getValue($key)
  {
    return isset($this->_cache[$key]) ? $this->_cache[$key] : false;
  }

  protected function getValues($keys)
  {
    $result = [];
    foreach ($keys as $key)
    {
      $result[$key] = $this->getValue($key);
    }
    return $result;
  }

  protected function setValue($key,$value,$expire)
  {
    if($expire>0)
      $expire+=time();
    else
      $expire=0;

    $this->_expires[$key] = $expire;
    $this->_cache[$key] = $value;
    return true;
  }

  protected function addValue($key,$value,$expire)
  {
    if($expire>0)
      $expire+=time();
    else
      $expire=0;

    if(!isset($this->_cache[$key]))
      return false;

    $this->_expires[$key] = $expire;
    $this->_cache[$key] = $value;
    return true;
  }

  protected function deleteValue($key)
  {
    if(isset($this->_expires[$key]))
      unset($this->_expires[$key]);
    if(isset($this->_cache[$key]))
      unset($this->_cache[$key]);
    return true;
  }

  protected function flushValues()
  {
    $this->_expires = [];
    $this->_cache = [];
    return true;
  }
}