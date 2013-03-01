<?php

class YiiTestCacheTest extends CTestCase
{
  public function setUp()
  {
    $this->cache = Yii::app()->cache;
    $this->cache->clearAll();
    $this->cache->flush();
  }

  public function tearDown()
  {
    $this->cache->clearAll();
  }

  public function testSetAndGet()
  {
    $this->assertTrue($this->cache->set('foo', 'bar'));
    $this->assertEquals('bar', $this->cache->get('foo'));
    $this->assertEquals(
      ['read' => ['foo' => 1], 'write' => ['foo' => 1], 'hit' => ['foo' => 1], 'delete' => []],
      $this->cache->getProfileData()
    );
  }

  public function testAdd()
  {
    $this->assertFalse($this->cache->add('foo', 'bar'));
    $this->assertEquals(
      ['read' => [], 'write' => [], 'hit' => [], 'delete' => []],
      $this->cache->getProfileData()
    );

    $this->assertTrue($this->cache->set('foo', 'bar'));
    $this->assertEquals(
      ['read' => [], 'write' => ['foo' => 1], 'hit' => [], 'delete' => []],
      $this->cache->getProfileData()
    );

    $this->assertTrue($this->cache->add('foo', 'baz'));
    $this->assertEquals(
      ['read' => [], 'write' => ['foo' => 2], 'hit' => [], 'delete' => []],
      $this->cache->getProfileData()
    );
  }

}