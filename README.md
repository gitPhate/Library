# Library
The purpose of this little project is to keep me busy when at work I have some idle moments. I firstly decided to build a query builder, but now I want to make a small framework.

So this is the main documentation, as I'm too lazy to build a website.

I'll introduce myself: I'm a 20-year-old guy from Florence, Italy. I work in an IT company and I'm learning C# and OOP basics.

Let's start with the docs. There are some namespace, I will split docs by namespace to keep things ordered. I tried to follow the [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) standard, so they will match the directory tree.
- [Library/](#main-namespace-library)
  - [Collections/](#colletions-namespace-librarycollections)
    - [SimpleList](#simplelist)
    - [Collection](#collection)
    - [Dictionary](#dictionary)
    - [Tuple](#tuple)
  - [Exceptions/](#exceptions-namespace-libraryexceptions)
    - [LibraryException](#libraryexception)
    - [ArgumentException](#libraryexception)
    - [InvalidOperationException](#libraryexception)
    - [NotFoundException](#libraryexception)
  - [Sql/](#sql-namespace-librarysql)
    - [QueryBuilder](#querybuilder)
    - [Database](#database)
    - [DatabaseConfig](#databaseconfig)
  - [Utilities/](#utilities-namespace-libraryutilities)
- [class autoload](#class-autoload)
- [BaseEnum](#baseenum)
- [Lazy](#lazy)
- [Singleton](#singleton)

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
abstract class DaysOfWeek extends BaseEnum {
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
[Main index](#library)

## Colletion Namespace (Library\\Collection)

### Classes:
  * SimpleList
  * Collection
  * Dictionary
  * Tuple

Some classes are built upon their abstract one, and they implement some interfaces. They are built on this hierarchy:
- `AbstractCollection implements IBaseCollection, \ArrayAccess`
	- `SimpleList extends AbstractCollection implements IList`
		- `Collection extends SimpleList implements ICollection`
	- `Dictionary extends AbstractCollection implements IDictionary`

#### SimpleList
SimpleList is a class that represents a list. It has the basic method of a list, here's a complete reference:
* `__construct($initialItems = null)`
* `Add($element)` - *defined in IList*
* `Any()` - *Inherited from AbstractCollection*
* `Clear()` - *Inherited from AbstractCollection*
* `Contains($element)` - *defined in IList*
* `Count()` - *Inherited from AbstractCollection*
* `First()` - *Overridden from AbstractCollection*
* `IndexOf($element)` - *Inherited from AbstractCollection*
* `Remove($value)` - *Overridden from AbstractCollection*
* `ToArray()` - *Overridden from AbstractCollection*
* `ToCollection()` - *Overridden from AbstractCollection*

```PHP
public void __construct(array $initialItems = null);
```
Constructor of the class. It can accepts an array to be inserted in the list.
```PHP
public void Add(mixed $element);
```
Adds an element or an array of elements to the list.
```PHP
public bool Any();
```
Returns true if the list is not empty, false otherwise.
```PHP
public void Clear();
```
Resets the internal data container, wiping all data from the list.
```PHP
public bool Contains($element);
```
Checks whether an element is contained in the list and returns true if it is found, false otherwise.
```PHP
public int Count();
```
Returns the number of elements contained in the list.
```PHP
public mixed First();
```
Returns the first element of the list.
```PHP
public int IndexOf(mixed $element);
```
Searches for the element and returns the index if it's found. It returns false otherwise.
```PHP
public bool Remove(mixed $value);
```
Removes the element from the list. This method is overridden from the AbstractCollection class.
```PHP
public array ToArray();
```
Returns the elements of the list as an array. This method is overridden from the AbstractCollection class.
```PHP
public Collection ToCollection();
```
Returns the elements of the list as an instance of the Collection class. See below for an API reference of this class. This method is overridden from the AbstractCollection class.

#### Collection
The collection class is an advanced collection. It inherits from SimpleList and adds functionalities to a normal list. All method are defined in the `ICollection` interface:
* `__construct($initialItems = null)`
* `Each($callback, $param)`
* `Filter($callback)`
* `Map($callback, $param = null)`
* `Range($size, $from = null)`
* `Shuffle()`

##### Ctor

```PHP
public void __construct(array $initialItems = null);
```
Constructor of the class. It can accepts an array to be inserted in the list.

##### Each

```PHP
public void Each(callable $callback, [mixed $param1, mixed $param2, ...]);
```
The `Each()` method applies a callback on every element of the collection. It modifies the internal data set directly, thus it has no return value. You can pass parameters to the callback by passing parameters to `Each()`.
In order to edit data, your callback can return a value, which will be set as the current item in the collection. Moreover, it can accept the value of the current element as a parameter. Examples:
```PHP
include("Library\autoload.php");

use Library\Collections\Collection;

$list = new Collection([1, 2, 3, 4, 5]);

$list->Each(function ($value) {
   return $value + 1;
});

var_dump(
	$list->ToArray()
);
// prints [2, 3, 4, 5, 6]

$list->Each(function ($value, $number1, $number2) {
   return $value + $number1 + $number2;
}, 1, 1);

var_dump($list->ToArray());
// prints [4, 5, 6, 7, 8]
```

##### Filter

```PHP
public void Filter(callable $callback);
```
The `Filter()` method creates a new collection comparing each element with a callback. The element will be included in the new collection if it fits the criteria. You can choose to use only keys or both keys and values in your callback by passing a value of the FilterMode enum as second parameter. The FilterMode enum is loaded with Collection class, it has `Keys`, `Values` and `Both`.
The default is only values. Examples:

```PHP
$list = new Collection([1, 2, 3, 4]);
$newList = $list->Filter(function ($value) {
	return $value % 2 == 0;
});

var_dump($newList->ToArray()); // prints [2, 4]

$newList = $list->Filter(function ($key, $value) {
	return $value % 2 == 0 && $key > 1;
}, FilterMode::Both);

var_dump($newList->ToArray()); // prints [4]
```

##### Map
```PHP
public Collection Map(callable $callback, [mixed $param1, mixed $param2, ...]);
```
`Map()` acts the same as `Each()`, with the only difference that returns a new `Collection` object instead of editing internal data. The provided callback must have a return value, otherwise an exception will be thrown.

##### Range
```PHP
public Collection Range(int $size, [int $from = null]);
```
`Range()` slices the collection and return a new `Collection` object with the number of elements indicated from `$size`, starting from `$from` if set, from the beginning otherwise. The first element is in position zero, and if `$from` is set the element in the position `$from` will be included in the new collection.
```PHP
use Library\Collections\Collection;

$list = new Collection([1, 2, 3, 4]);
$newList1 = $list->Range(2);
$newList2 = $list->Range(2, 2);

var_dump($newList1->ToArray()); // prints [1, 2]
var_dump($newList2->ToArray()); // prints [3, 4]
```

##### Shuffle
```PHP
public void Shuffle();
```
Shuffles the internal elements of the collection. 

#### Dictionary
Dictionary is a basic dictionary class. It inherits from AbstractCollection, the same base class as SimpleList, so it will have the same methods as SimpleList. It also implements IDictionary interfaces, and this is its API:

* `__construct()`
* `Add($key, $value)`
* `ContainsKey($key)`
* `First()`
* `Keys()`
* `Values()`

```PHP
public void __construct();
```
Initialize the dictionary.

```PHP
public void Add(mixed $key, mixed $value);
```
Adds the element at the specific key. It throws an exception if there is already an element with the indicated key.
```PHP
public void ContainsKey(mixed $key);
```
Checks whether an element with the key `$key` already exists. If so, it returns true, otherwise it will return false.
```PHP
public Tuple First();
```
Returns the first element of the list as a Tuple with the key as the Item1 and the value as the Item2.
```PHP
public void Keys();
```
Returns a new instance of the Collection class with all the keys in the dictionary.
```PHP
public void Values();
```
Returns a new instance of the Collection class with all the values in the dictionary.

#### Tuple
The Tuple class can relate some items and carry them togeher. For example, a dictionary's key-value pair can perfectly be a tuple. 
It can hold up to 8 elements; in order to set more you need to create a new tuple class and put it in position 8. This way you'll be able to hold infinite elements.
To get the value of an element just refer to Itemx, where x is the position. Let's see some examples:
```PHP
use Library\Collections as C;

$tuple = new C\Tuple(1, 2, 3, 4);

echo $tuple->Item2; // 2

$tuple = new C\Tuple(1, 2, 3, 4, 5, 6, 7, new C\Tuple(8, 9, 10));

echo $tuple->Item8->Item1; // 8
```

[Main index](#library)

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

Other exception classes work the same; the signature of a generic exceptions is:
```PHP
public function __construct(string $message, int $code = 0, Exception $previous = null);
```

[Main index](#library)

## Sql Namespace (Library\\Sql)

### Classes:
  * Database
  * DatabaseConfig
  * QueryBuilder

#### Database
It is for now an old attempt to create a wrapper of a mysqli stream. Still work in progress!

#### DatabaseConfig
It's an object that contains the configuration of the database. Its only method is `CreateNewConnection()` that returns a mysqli pointer, anyway it must be revised.

Constructor: `public function __construct($host, $user, $psw, $name)`

#### QueryBuilder
It's a set of functions that creates a sql query. For now it's built on MySql, I haven't tested it on other dbms.
These are the main functionalities:
- [Select](#select-queries)
- [Where](#where)
  - [Like](#like)
  - [Between](#between)
  - [In](#in)
- [Join](#join)
- [SubQueries](#subqueries)
- [Aggregate and sql functions](#aggregate-and-sql-functions)
- [Order By, Group By and Limit](#order-by-group-by-and-limit)
- [Having](#having)
- [Insert](#insert-queries)
- [Update](#update-queries)
- [Delete](#delete-queries)
- [Raw Query](#raw-query)
- [Union and Union All](#union-and-union-all)

[Main index](#library)

The main class is `QueryBuilder`, so all queries starts with creating a new instance of this class:
```PHP
$query = new Library\Sql\QueryBuilder();
```

##### Select queries
Select queries uses some methods to select data, followed by the `From()` method to point the table.
In order to get the query as a string, just append to the end of it the `toSql()` method.

To select all elements use `SelectAll()`:
```PHP
$query
    ->SelectAll()
    ->From("table")
    //SELECT * FROM table
```
You can also point out a table, this might be useful in complex queries:
```PHP
$query
    ->SelectAll("table1")
    ->From("table1")
    ->InnerJoin("table2", "table1.id = table2.id")
/*
SELECT table1.*
FROM table1
INNER JOIN table2 ON table1.id = table2.id
*/
```
To select a single field just use the `Select()` method. You can also specify an alias
```PHP
$query
    ->Select("field")
    ->From("table")
    // SELECT field FROM table
  
$query
    ->Select("field", "alias")
    ->From("table")
    // SELECT field AS alias FROM table
```

You can set a distinct field with `SelectDistinct()` using the same rules as above.

You can select multiple fields also specifying aliases:
```PHP
$query
	->Select(["field1", "field2"])
	->From("table")
	// SELECT field1, field2 FROM table

$query
    ->Select
    ([
        "alias1" => "field1",
        "alias2" => "field2"
    ])
	->From("table")
/*
SELECT field1 AS alias1, field2 AS alias2
FROM table
*/

$query
    ->Select
    ([
        "alias1" => "field1",
        "field2"
    ])
	->From("table")
/*
SELECT field1 AS alias1, field2
FROM table
*/
```

In order to select distinct with multiple fields, replace the field name with an array like: `["field", "distinct" => true]`
```PHP
$query
    ->Select
    ([
            "alias1" => "field1",
            "alias2" => ["field2", "distinct" => true]
        ])
	->From("table")
/*
SELECT field1 AS alias1, DISTINCT field2 AS alias2
FROM table
*/
```

[Index](#querybuilder)

##### Where
The where clause can be used anywhere you need it, with the same rules. To insert a simple equal-to condition, do:
```PHP
$query
    ->Select("field")
    ->From("table")
    ->Where("field", "value")
    // SELECT field FROM table WHERE field = 'value'
```
You can also set a negative condition using `WhereNot()`
```PHP
$query
    ->Select("field")
    ->From("table")
    ->WhereNot("field", "value")
    // SELECT field FROM table WHERE field != 'value'
```
Leaving only one parameter equals to state 'is null':
```PHP
$query
    ->Select("field")
    ->From("table")
    ->Where("field")
    // SELECT field FROM table WHERE field IS NULL
```
Or not null:
```PHP
$query
    ->Select("field")
    ->From("table")
    ->WhereNot("field")
    // SELECT field FROM table WHERE field IS NOT NULL
```

###### Like
Other operations requires the parameter to be binded to the condition, like:
```PHP
$query
    ->Select("field")
    ->From("table")
    ->Where("field > ?", 3)
    // SELECT field FROM table WHERE field > 3
```
This works for all primary operators: `=, <>, >, <, >=, <=, !=`

If you want to perform a `LIKE` match, you can set it in the condition:
```PHP
$query
    ->Select("field")
    ->From("table")
    ->Where("field LIKE %?", 'a')
    // SELECT field FROM table WHERE field LIKE '%a'
```
It works for the three cases `%?, ?%, %?%`

###### Between
In order to use between you have to bind params to the conditions with labels. An example is better that a hundred words:
```PHP
$query
    ->Select("field")
    ->From("table")
    ->Where
    (
    	"field BETWEEN :value AND :value2",
    	[':value' => 1, ':value2' => 2]
    )
/*
SELECT field
FROM table
WHERE field BETWEEN 1 AND 2
*/
```
`WhereNot()` doesn't work in these conditions.

###### IN
To set a condition with `IN`, use an array to specify the set of values:
```PHP
$query
    ->Select("field")
    ->From("table")
    ->Where
    (
    	"field",
    	[1, 2, 3, 4]
    )
    // SELECT field FROM table WHERE field IN (1, 2, 3, 4)
```
Negative one will then be:
```PHP
$query
    ->Select("field")
    ->From("table")
    ->WhereNot
    (
    	"field",
    	[1, 2, 3, 4]
    )
    // SELECT field FROM table WHERE field NOT IN (1, 2, 3, 4)
```
All string parameters are automatically wrapped with single quotes, and generally all parameters' values, injected from the outside, are automatically escaped by a parser, so there shouldn't be any Sql Injection.

[Index](#querybuilder)

##### Join
The join method is quite simple, it works the same for all joins type (inner, right and left join):
```PHP
InnerJoin(string $table, [string $alias], string $on_condition);
```
Here's a working example:
```PHP
$query
    ->SelectAll("table2")
    ->From("table1")
    ->InnerJoin("table2", "table1.id = table2.id")
    ->Where("table1.id", 2)
/*
SELECT table2.*
FROM table1
INNER JOIN table2 ON table1.id = table2.id
WHERE table1.id = 2
*/
```
You can also specify an alias for the join table:
```PHP
$query
    ->SelectAll("table2")
    ->From("table1")
    ->InnerJoin("table2", "alias", "table1.id = table2.id")
    ->Where("table1.id", 2)
/*
SELECT table2.*
FROM table1
INNER JOIN table2 alias ON table1.id = table2.id
WHERE table1.id = 2
*/
```

To add more than one condition you can use AndCondition() and OrCondition():
```PHP
$query
    ->SelectAll("table2")
    ->From("table1")
    ->InnerJoin("table2", "table1.id = table2.id")
    ->AndCondition("table2.id = ?", 5)
    /*
    SELECT table2.*
    FROM table1 
    INNER JOIN table2 ON table1.id = table2.id AND table2.id = 5
    */
```

[Index](#querybuilder)

##### SubQueries
Still work in progress!

[Index](#querybuilder)

##### Aggregate and sql functions
There are two static classes in order to represent aggregate functions and other ones. You can use them anywhere you like.

Distinct functions set the arguments distinct, so `CountDistinct(column)` will generate `COUNT(DISTINCT column)`.
Aggregate functions are in the AggregateFunctions class:
```PHP
AggregateFunctions::Sum(string $column, [string $alias])
AggregateFunctions::SumDistinct(string $column, [string $alias])

AggregateFunctions::Count(string $column, [string $alias])
AggregateFunctions::CountDistinct(string $column, [string $alias])
AggregateFunctions::CountAll([string $alias])

AggregateFunctions::Avg(string $column, [string $alias])
AggregateFunctions::AvgDistinct(string $column, [string $alias])

AggregateFunctions::Min(string $column, [string $alias])
AggregateFunctions::MinDistinct(string $column, [string $alias])

AggregateFunctions::Max(string $column, [string $alias])
AggregateFunctions::MaxDistinct(string $column, [string $alias])
```

While other functions are in the SqlFunctions one:
```PHP
SqlFunctions::Coalesce(mixed $val1, [mixed $val2, ...])
```

[Index](#querybuilder)

##### Order By, Group By and Limit
`GroupBy()` is a simple method, here's its signature:
```PHP
public function GroupBy(string $column1, [string $column2, ...])
```
`OrderBy()` can accept a string or an array as parameter, and you can also indicate `ASC/DESC` order for each field.
Examples of Grouping:
```PHP
$query
    ->Select
    ([
        "field",
        AggregateFunctions::CountAll("count")
    ])
    ->From("table")
    ->GroupBy("field")
/*
SELECT field, COUNT(*) AS count
FROM table
GROUP BY count
*/

$query
    ->Select
    ([
        "field1",
        "field2",
        AggregateFunctions::CountAll("count")
    ])
    ->From("table")
    ->GroupBy("field1", "field2")
/*
SELECT field1, field2, COUNT(*) AS count
FROM table
GROUP BY field1, field2
*/
```

and Ordering:
```PHP
$query
	->Select(["field1", "field2"])
	->From("table")
	->OrderBy("field1")
    ->toSql();
    // SELECT field1, field2 FROM table ORDER BY field1
    
$query
	->Select(["field1", "field2"])
	->From("table")
	->OrderBy("field1 ASC")
    ->toSql();
    // SELECT field1, field2 FROM table ORDER BY field1
    
$query
	->Select(["field1", "field2"])
	->From("table")
	->OrderBy
    ([
        "field1",
        "field2 DESC"
    ])
    ->toSql();
    // SELECT field1, field2 FROM table ORDER BY field1, field2 DESC
```

Limit is very intuitive, it's placed always at the end of the query and its signature is:
```PHP
public function Limit(int $row_count)
public function Limit(int $offset, int $row_count)
```

[Index](#querybuilder)

##### Having
Having works along with AggregateFunctions, and is designed to accept only this kind of expressions.
There is the `Having()` method that works like this:
```PHP
$query
	->Select
    ([
        "field1",
        "field2"
    ])
    ->From("table")
    ->GroupBy("field2")
    ->Having(AggregateFunctions::Count("field2"), "> ?", 0)
/*
SELECT field1, field2
FROM table
GROUP BY field2
HAVING COUNT(field2) > 0
*/
```
Parameters are binded to the condition for security reason.

One shorter way to do this can be
```PHP
$query
	->Select
    ([
        "field1",
        "field2"
    ])
    ->From("table")
    ->GroupBy("field2")
    ->Having("field2")
/*
SELECT field1, field2
FROM table
GROUP BY field2
HAVING COUNT(field2) > 0
*/
```

[Index](#querybuilder)

##### Insert queries
With insert query you can fill the db with your data. There are two ways to do that, passing data or fill by query:
```PHP
$builder
    ->Insert(['column1', 'column2', 'column3'])
    ->Into("table")
    ->Values(['column1' => 1, 'column2' => 2, 'column3' => 3])
    // INSERT INTO table(column1,column2,column3) VALUES(1,2,3)
```
Of course the keys of the array that you pass to `Values()` must be the same in the array you pass to `Insert()`, or you'll get an exception.

This is the by-query:
```PHP
$builder
        ->Insert(['column1', 'column2', 'column3'])
        ->Into("table")
        ->FromQuery
        (
            $builder
                ->Select(['col1', 'col2', 'col3'])
                ->From("table2")
                ->WhereNot("field")
                //You donì't have to call toSql() here
        )
        // INSERT INTO table(0,1,2) SELECT col1, col2, col3 FROM table2 WHERE field IS NOT NULL
```

[Index](#querybuilder)

##### Update queries
With update queries you can set a single value:
```PHP
$builder
	->Update("lol")
	->Set("id", 2)
	// UPDATE lol SET id = 2
 ```
 Or a set of values:
 ```PHP
$builder
    ->Update("lol")
    ->Set(['x' => 1, 'y' => "lol"])
    // UPDATE lol SET x = 1, y = 'lol'
 ```

Or you can fill a column with a query:
 ```PHP
$builder
    ->Update("lol")
    ->Set
    ([
        'x' => 1,
        'y' => $builder
                ->SelectAll()
                ->From("asd")
        // you don't have to call toSql() here
    ])
    // UPDATE lol SET x = 1, y = (SELECT * FROM asd)
```

[Index](#querybuilder)

##### Delete queries
To delete from a table, use the method `DeleteFrom()` and set a Where condition if you like:
```PHP
$builder
	->DeleteFrom("table")
	->Where("a", 0)
	// DELETE FROM table WHERE a = 'lol'
```
[Index](#querybuilder)

##### Raw Query
This feature is to be enhances, for now you can only set a custom query like this:
```PHP
$query
	->RawQuery("SELECT * FROM table");
```

##### Union and Union All
Union can be performed using the Union() or UnionAll() methods, which work exactly the same:
```PHP
$query
	->SelectAll()
    ->From("table1")
    ->Limit(5)
    ->Union
    (
        $query
            ->SelectAll()
            ->From("table2")
            //You don't need to use toSql() here
    )
/*
SELECT *
FROM table1
LIMIT 5
UNION
SELECT * FROM table2
*/
```
[Index](#querybuilder)

## Utilities Namespace (Library\\Utilities)
This is a namespace in which I put a set of classes and function that I use around the library. This means that all the stuff in there is not meant for development, there are component used by other classes in the project.

For the moment I will not keep a documentation of this namespace.

[Main index](#library)
