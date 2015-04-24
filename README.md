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
  * BaseEnum
  * Lazy
  * Singleton

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
It's my version of the [lazy loading](http://en.wikipedia.org/wiki/Lazy_loading).

<table>
  <tr>
    <td>Nome</td>
    <td>Descrizione</td>
  </tr>
</table>
