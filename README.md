# Yii Test Cache

Test tool of cache data for Yii Framework

## Usage

### In your config.php

```php
// ...
  'components'=>[
    'cache' => [
      'class' => 'YiiTestCache',
    ]
  ]
// ...
```

### In your production code

```php
class Foo extends CActiveRecord
{
  public function bar()
  {
    $cache = Yii::app()->cache()->get('baz');
    if($cache === false)
    {
      $cache = 'something';
      Yii::app()->cache()->set('baz', $cache);
    }
    return $cache;
  }
}
```

### In your test code

```php
class FooTest extends CDbTestCase
{
  public function setUp()
  {
    parent::setUp();
    Yii::app()->cache->flush();
  }

  public function tearDown()
  {
    Yii::app()->cache->clearAll();
    parent::tearDown();
  }

  public function testBar()
  {
    $foo = new Foo;
    // first
    $this->assertEquals('something', $foo->bar());
    $this->assertEquals(1, Yii::app()->cache->getProfileData('write'));
    $this->assertEquals(1, Yii::app()->cache->getProfileData('read'));

    // second
    $this->assertEquals('something', $foo->bar());
    $this->assertEquals(1, Yii::app()->cache->getProfileData('write'));
    // !!! increment profile data 'read' !!!
    $this->assertEquals(2, Yii::app()->cache->getProfileData('read'));
  }
}
```

## API

### hasKey

```php
Yii::app()->cache->set('keyname', 'value');
Yii::app()->cache->hasKey('keyname'); // true
Yii::app()->cache->hasKey('nothing'); // false
```

### getExpire

```php
Yii::app()->cache->set('keyname', 'value', 3600);
Yii::app()->cache->getExpire('keyname'); // 3600

Yii::app()->cache->set('keyname', 'value');
Yii::app()->cache->getExpire('keyname'); // 0

Yii::app()->cache->getExpire('nothing'); // null
```

### getData

```php
Yii::app()->cache->set('keyname', 'value');
Yii::app()->cache->getData('keyname');
// ['value', null]
// 0 => value, 1 => dependency
```

see: http://www.yiiframework.com/doc/api/1.1/CCache#set-detail

## Development

```
curl -s https://getcomposer.org/installer | php
php composer.phar install --dev
./run-test.sh
```

## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request
