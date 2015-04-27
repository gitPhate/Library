# Library
The purpose of this little project is to keep me busy when at work I have some idle moments. I firstly decided to build a query builder, but now I want to make a small framework.

So this is the main documentation, as I'm too lazy to build a website.

I'll introduce myself: I'm a 20-year-old guy from Florence, Italy. I work in an IT company and I'm learning C# and OOP basics.

Let's start with the docs. There are some namespace, I will split docs by namespace to keep things ordered. I tried to follow the [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) standard, so they will match the directory tree, which is:

    |---Library
        |---Collection
        |---Exceptions
        |---Sql
        │   |---QueryBuilder
        │       |---Enums
        │       |---QueryItems
        │       |---Statements
        |---Utilities

Let's see them one by one.

## Main Namespace (Library\\)
### Classes:
  * class autoload
  * BaseEnum
  * Lazy
  * Singleton

#### class autoload
There's a primitive class loader that must be included in any project that uses the library. It is in Library\autoload.php

#### BaseEnum
It's an attempt to use enumerations in PHP. It's not mine, I found it here: http://stackoverflow.com/a/254543/4499267

Here's a rapid example:
```PHP
abstract class DaysOfWeek extends BasicEnum {
    const Sunday = 0;
    const Monday = 1;
    const Tuesday = 2;
    const Wednesday = 3;
    const Thursday = 4;
    const Friday = 5;
    const Saturday = 6;
}

DaysOfWeek::isValidName('Humpday');                  // false
DaysOfWeek::isValidName('Monday');                   // true
DaysOfWeek::isValidName('monday');                   // true
DaysOfWeek::isValidName('monday', $strict = true);   // false
DaysOfWeek::isValidName(0);                          // false

DaysOfWeek::isValidValue(0);                         // true
DaysOfWeek::isValidValue(5);                         // true
DaysOfWeek::isValidValue(7);                         // false
DaysOfWeek::isValidValue('Friday');                  // false
```

#### Lazy
It's my version of the [lazy loading](http://en.wikipedia.org/wiki/Lazy_loading). It can store a callback or an object, and execute or instantiate it later, using the Value() method.

```PHP
$lazy = new Library\Lazy(function($a, $b) {
	return $a + $b;
}, 1, 2);

echo $lazy->Value(); //3
```

You can pass parameters to the callback both in the constructor or in the evaluation method:
```PHP
$lazy = new Library\Lazy(function($a, $b) {
	return $a + $b;
});

$lazy->Value(1, 2) //3;
```

Used with objects it lazy-evaluates them:
```PHP
class MyClass
{
    public function __construct()
    {
        echo "MyClass";
    }
}

$lazy = new Library\Lazy("MyClass");
$lazy->Value(); //MyClass
```

#### Singleton
It's an abstract class that implements the [singleton pattern](http://en.wikipedia.org/wiki/Singleton_pattern).
It provides the `getInstance()` method that returns the instance of the class.

A short example:
```PHP
class MyClass extends Library\Singleton
{
    public $counter = 1;
    
    public function __construct()
    {
        echo "I'm the instance number {$this->counter}";
        $this->counter++;
    }
}

$obj = MyClass::getInstance(); //I'm the instance number 1
$obj = MyClass::getInstance(); //Doesn't print anything else, because the class is instantiated
$obj = MyClass::getInstance(); //just once
$obj = MyClass::getInstance();
```

If you need to pass parameters to the constructor, just pass them to the `getInstance()` method:
```PHP
class MyClass extends Library\Singleton
{
    public $counter = 1;
    
    public function __construct($i)
    {
    	$this->counter = $i;
        echo "I'm the instance number {$this->counter}";
        $this->counter++;
    }
}

$obj = MyClass::getInstance(1); //I'm the instance number 1
```

## Colletion Namespace (Library\\Collection)
### Interfaces:
  * ICollection

### Classes:
  * Collection

Still work in progress, I'll update this section later.

## Exceptions Namespace (Library\\Exceptions)
### Classes:
  * LibraryException
  * ArgumentException
  * InvalidOperationException
  * NotFoundException

#### LibraryException
It's the library's base exception. It provides a small stack trace and a custom message to be thrown along with it.

Example (thrown by Lazy class):
```
Uncaught Library\Exceptions\ArgumentException: Argument is neither a callback nor a valid class at Library\Lazy.__construct(index.php:4) thrown in [...]
```

Example (thrown by Query Builder):
```
Uncaught Library\Exceptions\ArgumentException: condition must be a string
at Library\Sql\QueryBuilder\BaseQuery.WhereClause(BaseQuery.php:107)
Library\Sql\QueryBuilder\BaseQuery.Where(index.php:5)
```

Other exception classes work the same.

## Sql Namespace (Library\\Sql)
### Namespaces:
  * QueryBuilder
    * Enums
    * QueryItems
    * Statements

### Classes:
  * Database
  * DatabaseConfig
