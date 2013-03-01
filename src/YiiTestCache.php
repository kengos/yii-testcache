<?php

class YiiTestCache extends CCache
{
  const READ_KEY = 'read', WRITE_KEY = 'write', DELETE_KEY = 'delete', HIT_KEY = 'hit', DATA_KEY = 'data';
  public $readCache = true;
  public $storage = [
    'class' => 'system.caching.CFileCache',
  ];

  protected $_storage;

  protected function getValue($key)
  {
    $this->increment(self::READ_KEY, $key);

    $data = $this->getStorage(self::DATA_KEY);
    if(isset($data[$key]))
    {
      $this->increment(self::HIT_KEY, $key);

      if($this->readCache)
      {
        return $data[$key]['data'];
      }
    }
    return false;
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
    if($expire < 0)
      $expire = 0;

    $this->increment(self::WRITE_KEY, $key);

    $data = $this->getStorage(self::DATA_KEY);
    if($data === false)
      $data = [];

    $data[$key] = ['data' => $this->readCache ? $value : null, 'expire' => $expire];
    $this->setStorage(self::DATA_KEY, $data);
    return true;
  }

  protected function addValue($key,$value,$expire)
  {
    if($expire < 0)
      $expire = 0;

    $data = $this->getStorage(self::DATA_KEY);
    if($data === false || !isset($data[$key]))
      return false;

    $data[$key] = ['data' => $this->readCache ? $value : null, 'expire' => $expire];
    $this->setStorage(self::DATA_KEY, $data);
    $this->increment(self::WRITE_KEY, $key);
    return true;
  }

  protected function deleteValue($key)
  {
    $data = $this->getStorage(self::DATA_KEY);
    if($data == false && !isset($data[$key]))
      return true;

    unset($data[$key]);
    $this->setStorage(self::DATA_KEY, $data);
    $this->increment(self::DELETE_KEY, $key);
    return true;
  }

  protected function flushValues()
  {
    foreach([self::READ_KEY, self::WRITE_KEY, self::DELETE_KEY, self::HIT_KEY, self::DATA_KEY] as $key)
    {
      $this->setStorage($key, []);
    }
    return true;
  }

  public function getStorage($key = null)
  {
    if($this->_storage === null)
    {
      $this->_storage = Yii::createComponent($this->storage);
      $this->_storage->init();
    }
    return $key === null ? $this->_storage : $this->_storage->get($key);
  }

  public function setStorage($key, $value, $expire = 0)
  {
    return $this->getStorage()->set($key, $value, $expire);
  }

  public function getProfileData($storeKey = null)
  {
    if($storeKey === null)
    {
      return [
        self::READ_KEY => $this->getStorage(self::READ_KEY),
        self::WRITE_KEY => $this->getStorage(self::WRITE_KEY),
        self::HIT_KEY => $this->getStorage(self::HIT_KEY),
        self::DELETE_KEY => $this->getStorage(self::DELETE_KEY)
      ];
    }
    else if(in_array($storeKey, [self::READ_KEY, self::WRITE_KEY, self::HIT_KEY, self::DELETE_KEY]))
    {
      return $this->getStorage($storeKey);
    }
    return false;
  }

  public function clearAll()
  {
    return $this->getStorage()->flush();
  }

  /**
   * Override
   */
  protected function generateUniqueKey($id)
  {
    return $id;
  }

  protected function increment($storeKey, $key)
  {
    $storage = $this->getStorage($storeKey);
    if($storage === false)
      $storage = [];

    if(isset($storage[$key]))
      $storage[$key] += 1;
    else
      $storage[$key] = 1;

    return $this->setStorage($storeKey, $storage);
  }
}