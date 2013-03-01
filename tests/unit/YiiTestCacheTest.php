<?php

class YiiTestCacheTest extends CTestCase
{
  public function setUp()
  {
    $this->cache = Yii::app()->cache;
    $this->cache->init();
  }

  /**
   * @covers YiiTestCache::getCacheSize
   * @covers YiiTestCache::getExpire
   * @covers YiiTestCache::setValue
   */
  public function testSet()
  {
    $this->assertTrue($this->cache->set('foo', 'bar'));
    $this->assertEquals(1, $this->cache->getCacheSize());
    $this->assertEquals(0, $this->cache->getExpire('foo'));
    $this->cache->set('bar', 'baz', 200);
    $this->assertEquals(2, $this->cache->getCacheSize());
    $this->assertTrue(time() + 200 >= $this->cache->getExpire('bar'));
    $this->assertTrue(time() < $this->cache->getExpire('bar'));
  }

  public function testAdd()
  {
    $this->assertTrue($this->cache->set('foo', 'bar'));
    $this->assertTrue($this->cache->add('foo', 'baz'));
    $this->assertEquals('baz', $this->cache->get('foo'));

    $this->assertFalse($this->cache->add('bar', 'baz'));
    $this->assertEquals(1, count($this->cache->getCacheData()));
  }

  public function testMget()
  {
    $this->assertTrue($this->cache->set('foo', 'bar'));
    $this->assertTrue($this->cache->set('bar', 'baz'));
    $this->assertEquals(
      ['foo' => 'bar', 'bar' => 'baz', 'baz' => false],
      $this->cache->mget(['foo', 'bar', 'baz'])
    );
  }
}