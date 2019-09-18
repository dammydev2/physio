y property of any objects named "id"
$matcher = new PropertyNameMatcher('id');
```


#### Specific property

The `PropertyMatcher` will match a specific property of a specific class:

```php
use DeepCopy\Matcher\PropertyMatcher;

// Will apply a filter to the property "id" of any objects of the class "MyClass"
$matcher = new PropertyMatcher('MyClass', 'id');
```


#### Type

The `TypeMatcher` will match any element by its type (instance of a class or any value that could be parameter of
[gettype()](http://php.net/manual/en/function.gettype.php) function):

```php
use DeepCopy\TypeMatcher\TypeMatcher;

// Will apply a filter to any object that is an instance of Doctrine\Common\Collections\Collection
$matcher = new TypeMatcher('Doctrine\Common\Collections\Collection');
```


### Filters

- `DeepCopy\Filter` applies a transformation to the object attribute matched by `DeepCopy\Matcher`
- `DeepCopy\TypeFilter` applies a transformation to any element matched by `DeepCopy\TypeMatcher`


#### `SetNullFilter` (filter)

Let's say for example that you are copying a database record (or a Doctrine entity), so you want the copy not to have
any ID:

```php
use DeepCopy\DeepCopy;
use DeepCopy\Filter\SetNullFilter;
use DeepCopy\Matcher\PropertyNameMatcher;

$object = MyClass::load(123);
echo $object->id; // 123

$copier = new DeepCopy();
$copier->addFilter(new SetNullFilter(), new PropertyNameMatcher('id'));

$copy = $copier->copy($object);

echo $copy->id; // null
```


#### `KeepFilter` (filter)

If you want a property to remain untouched (for example, an association to an object):

```php
use DeepCopy\DeepCopy;
use DeepCopy\Filter\KeepFilter;
use DeepCopy\Matcher\PropertyMatcher;

$copier = new DeepCopy();
$copier->addFilter(new KeepFilter(), new PropertyMatcher('MyClass', 'category'));

$copy = $copier->copy($object);
// $copy->category has not been touched
```


#### `DoctrineCollectionFilter` (filter)

If you use Doctrine and want to copy an entity, you will need to use the `DoctrineCollectionFilter`:

```php
use DeepCopy\DeepCopy;
use DeepCopy\Filter\Doctrine\DoctrineCollectionFilter;
use DeepCopy\Matcher\PropertyTypeMatcher;

$copier = new DeepCopy();
$copier->addFilter(new DoctrineCollectionFilter(), new PropertyTypeMatcher('Doctrine\Common\Collections\Collection'));

$copy = $copier->copy($object);
```


#### `DoctrineEmptyCollectionFilter` (filte