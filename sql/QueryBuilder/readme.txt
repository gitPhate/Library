WHERE:
- where("field", "value") -> field = 'value'
- where("field") -> field IS NULL

- where("field > ?", x) -> field > x
- where("field => ?", x) -> field => x
- where("field <> ?", x) -> field <> x
and so on
- where("field LIKE %?", x) -> field LIKE %x
- where("field LIKE %?%", x) -> field LIKE %x%
- where("field LIKE ?%", x) -> field LIKE x%

- where("field BETWEEN :value1 AND :value2", array(':value1' => 'x', ':value2' => 'y')) -> field BETWEEN 'x' AND 'y'

- where("field", array(1, 2, 3)) -> field IN (1, 2, 3)
- where("field", array('a', 'b', 'c')) -> field IN ('a', 'b', 'c')

SELECT:
- selectAll() -> SELECT *
- selectAll("table") -> SELECT table.*

- select(array(			-> SELECT field1 AS alias1, field2 AS field2
	"alias1" => "field1",
	"alias2" => "field2"
	)
)

- select(array(		-> SELECT field1, field2
	"field1",
	"field2"
	)
)

- select("field", "alias") -> SELECT field AS alias
- select("field") -> SELECT field

Same for SelectDistinct()

- GroupBy("field") 		-> GROUP BY field
- GroupBy("field1", "field2") -> GROUP BY field1, field2

- OrderBy("field") 		-> ORDER BY field DESC
- OrderBy("field DESC") -> ORDER BY field DESC
- OrderBy(array(                   -> ORDER BY field1 DESC, field2, field3
	"field1 DESC",
	"field2",
	"field3 ASC"
	)
)

- Having(AggregateFunctions::Count("field"), "> ?", 2) -> HAVING COUNT(field) > 2
- Having("field") -> HAVING COUNT(field) > 0

- Limit(10) -> LIMIT 10
- Limit(10, 3) LIMIT 10, 3

- SqlFunction::Coalesce()

- AggregateFunctions::Count(), CountAll(), Avg(), Min(), Max(), Sum() and Distinct

- RawQuery("SELECT * FROM table") -> SELECT * FROM table //unsafe: query not sanitized

- InnerJoin("table2", "table.id = table2.id") -> INNER JOIN table2 ON table.id = table2.id
- InnerJoin("table2", "table.id = ?", 2) -> INNER JOIN table2 ON table.id = 2
same for left and right join
- AndCondition("table.id = ?", 2) -> AND table.id = 2
also for OrCondition()

- Union(query) -> UNION query
        - Union($builder->SelectAll()-From("lol")) -> UNION SELECT * FROM lol
Same for UnionAll()

INSERT:
$builder
    ->Insert(['column1', 'column2', 'column3'])
    ->Into("table")
    ->Values(['column1' => 1, 'column2' => 2, 'column3' => 3])
    
INSERT INTO table(column1,column2,column3) VALUES(1,2,3)



$builder
        ->Insert(['column1', 'column2', 'column3'])
        ->Into("table")
        ->FromQuery
        (
            $builder
                ->Select(['col1', 'col2', 'col3'])
                ->From("table2")
                ->WhereNot("field")
        )
        ->ToSql()
        
INSERT INTO table(0,1,2) SELECT col1, col2, col3 FROM table2 WHERE field IS NOT NULL



UPDATE:
- $builder
    ->Update("lol")
    ->Set("id", 2)
 
UPDATE lol SET id = 2

- $builder
    ->Update("lol")
    ->Set(['x' => 1, 'y' => "lol"])

UPDATE lol SET x = 1, y = 'lol'

- $builder
    ->Update("lol")
    ->Set
    ([
        'x' => 1,
        'y' => $builder
                ->SelectAll()
                ->From("asd")
    ])

UPDATE lol SET x = 1, y = (SELECT * FROM asd)

DELETE:

- $builder
    ->DeleteFrom("table")
    ->Where("a", 0)
    
DELETE FROM table WHERE a = 0