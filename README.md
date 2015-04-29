# Library
The purpose of this little project is to keep me busy when at work I have some idle moments. I firstly decided to build a query builder, but now I want to make a small framework.

So this is the main documentation, as I'm too lazy to build a website.

I'll introduce myself: I'm a 20-year-old guy from Florence, Italy. I work in an IT company and I'm learning C# and OOP basics.

Let's start with the docs. There are some namespace, I will split docs by namespace to keep things ordered. I tried to follow the [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) standard, so they will match the directory tree.
- [Library/](#main-namespace-library)
  - [Collection/](#colletion-namespace-librarycollection)
    - ICollection
    - Collection
  - [Exceptions/](#exceptions-namespace-libraryexceptions)
    - [LibraryException](#libraryexception)
    - [ArgumentException](#libraryexception)
    - [InvalidOperationException](#libraryexception)
    - [NotFoundException](#libraryexception)
  - [Sql/](#sql-namespace-librarysql)
    - [QueryBuilder](#querybuilder)
    - [Database](#database)
    - [DatabaseConfig](#databaseconfig)
  - Utilities/
    - CallBackManager
    - UtilitiesService
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

Other exception classes work the same; the prototype of a generic exceptions is:
```PHP
public function __construct($message, $code = 0, Exception $previous = null);
```

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
- [Insert](#insert-queries)
- [Update](#update-queries)
- Delete
- SubQueries
- Having
- Limit
- Order By
- Group By
- Aggregate and sql functions
  - Count
  - Sum, Avg, Min and Max
  - Coalesce
- Raw Query
- Union and Union All

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
$builder
	->DeleteFrom("table")
	->Where("a", 0)
	// DELETE FROM table WHERE a = 'lol'
