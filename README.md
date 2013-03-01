# Yii Test Cache

This is a module to the test of the cache in the Yii framework

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

## In your test code

```
class FooTest extends CDbTestCase
{
  public function testBar()
  {
    $foo = new Foo;
    // first
    $this->assertEquals('something', $foo->bar());
    $this->assertEquals(1, Yii::app()->getProfileData('write'));
    $this->assertEquals(1, Yii::app()->getProfileData('read'));

    // second
    $this->assertEquals('something', $foo->bar());
    $this->assertEquals(1, Yii::app()->getProfileData('write'));
    // !!! increment profile data 'read' !!!
    $this->assertEquals(2, Yii::app()->getProfileData('read'));
  }
}
```

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
