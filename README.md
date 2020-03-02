# ramda-php

This is a PHP implementation of ramda.js - a functional programming library. (http://ramdajs.com/)

So far only the curry functions and part of the functions implemented. The library is capable of doing the what's in the ramda.js examples.

### Implemented Methods

- [x] add
- [x] addIndex
- [x] adjust
- [x] all
- [ ] allPass
- [ ] allUniq
- [x] always
- [x] and
- [x] any
- [x] anyPass
- [x] ap
- [ ] aperture
- [x] append
- [x] apply
- [ ] applySpec
- [x] assoc
- [ ] assocPath
- [ ] binary
- [ ] bind
- [x] both
- [ ] call
- [x] chain
- [ ] clamp
- [ ] clone
- [x] comparator
- [ ] complement
- [x] compose
- [ ] composeK
- [ ] composeP
- [x] concat
- [x] cond
- [ ] construct
- [ ] constructN
- [x] contains
- [ ] converge
- [ ] countBy
- [x] curry
- [x] curryN
- [x] dec
- [x] defaultTo
- [ ] difference
- [ ] differenceWith
- [ ] dissoc
- [ ] dissocPath
- [x] divide
- [ ] drop
- [ ] dropLast
- [ ] dropLastWhile
- [ ] dropRepeats
- [ ] dropRepeatsWith
- [ ] dropWhile
- [x] either
- [ ] empty
- [ ] eqBy
- [ ] eqProps
- [x] equals
- [ ] evolve
- [x] filter
- [ ] find
- [ ] findIndex
- [ ] findLast
- [ ] findLastIndex
- [x] flatten
- [ ] flip
- [x] forEach
- [ ] fromPairs
- [x] groupBy
- [x] gt
- [x] gte
- [x] has
- [ ] hasIn
- [x] head
- [x] identical
- [x] identity
- [ ] ifElse
- [x] inc
- [ ] indexBy
- [x] indexOf
- [x] init
- [ ] insert
- [ ] insertAll
- [ ] internal
- [ ] intersection
- [ ] intersectionWith
- [ ] intersperse
- [ ] into
- [ ] invert
- [ ] invertObj
- [ ] invoker
- [ ] is
- [ ] isArrayLike
- [ ] isEmpty
- [ ] isNil
- [x] join
- [ ] juxt
- [x] keys
- [ ] keysIn
- [x] last
- [ ] lastIndexOf
- [ ] length
- [ ] lens
- [ ] lensIndex
- [ ] lensPath
- [ ] lensProp
- [ ] lift
- [ ] liftN
- [x] lt
- [x] lte
- [x] map
- [ ] mapAccum
- [ ] mapAccumRight
- [ ] mapObjIndexed
- [ ] match
- [ ] mathMod
- [x] max
- [ ] maxBy
- [ ] mean
- [ ] median
- [ ] memoize
- [ ] merge
- [ ] mergeAll
- [ ] mergeWith
- [ ] mergeWithKey
- [x] min
- [ ] minBy
- [x] modulo
- [ ] multiply
- [ ] nAry
- [x] negate
- [ ] none
- [x] not
- [x] nth
- [ ] nthArg
- [ ] objOf
- [x] of
- [ ] omit
- [x] once
- [x] or
- [ ] over
- [x] pair
- [ ] partial
- [ ] partialRight
- [x] partition
- [x] paths
- [x] path
- [x] pathEq
- [ ] pathOr
- [ ] pathSatisfies
- [x] pick
- [x] pickAll
- [ ] pickBy
- [x] pipe
- [ ] pipeK
- [ ] pipeP
- [x] pluck
- [x] prepend
- [ ] product
- [x] project
- [x] prop
- [x] propEq
- [ ] propIs
- [x] propOr
- [x] propSatisfies
- [ ] props
- [x] range
- [x] reduce
- [ ] reduceBy
- [ ] reduceRight
- [ ] reduced
- [x] reject
- [x] remove
- [ ] repeat
- [ ] replace
- [x] reverse
- [ ] scan
- [ ] sequence
- [ ] set
- [x] slice
- [x] sort
- [x] sortBy
- [ ] split
- [ ] splitAt
- [ ] splitEvery
- [ ] splitWhen
- [x] subtract
- [x] sum
- [ ] symmetricDifference
- [ ] symmetricDifferenceWith
- [x] tail
- [x] take
- [ ] takeLast
- [ ] takeLastWhile
- [ ] takeWhile
- [x] tap
- [x] test
- [x] times
- [x] toLower
- [ ] toPairs
- [ ] toPairsIn
- [ ] toString
- [x] toUpper
- [ ] transduce
- [ ] transpose
- [ ] traverse
- [ ] trim
- [ ] tryCatch
- [ ] type
- [ ] unapply
- [ ] unary
- [ ] uncurryN
- [ ] unfold
- [ ] union
- [ ] unionWith
- [ ] uniq
- [ ] uniqBy
- [ ] uniqWith
- [ ] unless
- [ ] unnest
- [ ] update
- [x] useWith
- [x] values
- [ ] valuesIn
- [ ] view
- [ ] when
- [x] where
- [x] whereEq
- [ ] without
- [ ] wrap
- [x] xprod
- [x] zip
- [ ] zipObj
- [ ] zipWith

### Introduced Methods
R::$flatten1 - flatten in non-recursive way (for 1 depth only).

### How to Use

Compose functions:

```
  require_once 'ramda.php';
  
  $getIncompleteTaskSummaries = function($membername) {
    return (R::$compose)(
      (R::$sortBy)((R::$prop)('dueDate')),
      (R::$map)((R::$pick)(['id', 'dueDate', 'title', 'priority'])),
      (R::$reject)((R::$propEq)('complete', true)),
      (R::$filter)((R::$propEq)('username', $membername)),
      (R::$prop)('tasks'));
  };

```

Use the function on data:
```
  $data = [
      'result' => "SUCCESS",
      'interfaceVersion' => "1.0.3",
      'requested' => "10/17/2013 15:31:20",
      'lastUpdated' => "10/16/2013 10:52:39",
      'tasks' => [
          ['id' => 104, 'complete' => false,            'priority' => "high",
                    'dueDate' => "2013-11-29",      'username' => "Scott",
                    'title' => "Do something",      'created' => "9/22/2013"],
          ['id' => 105, 'complete' => false,            'priority' => "medium",
                    'dueDate' => "2013-11-22",      'username' => "Lena",
                    'title' => "Do something else", 'created' => "9/22/2013"],
          ['id' => 107, 'complete' => false,             'priority' => "high",
                    'dueDate' => "2013-11-22",      'username' => "Mike",
                    'title' => "Fix the foo",       'created' => "9/22/2013"],
          ['id' => 108, 'complete' => false,            'priority' => "low",
                    'dueDate' => "2013-11-15",      'username' => "Punam",
                    'title' => "Adjust the bar",    'created' => "9/25/2013"],
          ['id' => 110, 'complete' => false,            'priority' => "medium",
                    'dueDate' => "2013-11-15",      'username' => "Scott",
                    'title' => "Rename everything", 'created' => "10/2/2013"],
          ['id' => 112, 'complete' => true,             'priority' => "high",
                    'dueDate' => "2013-11-27",      'username' => "Lena",
                    'title' => "Alter all quuxes",  'created' => "10/5/2013"],
          ['id' => 122, 'complete' => false,             'priority' => "high",
                    'dueDate' => "2013-11-01",      'username' => "Mike",
                    'title' => "Fix the bar",       'created' => "9/22/2013"],
          ['id' => 123, 'complete' => true,             'priority' => "high",
                    'dueDate' => "2013-11-22",      'username' => "Mike",
                    'title' => "Fix the foobar",       'created' => "9/22/2013"],

      ]
  ];
  $tasks = $getIncompleteTaskSummaries("Mike")($data);
  print_r($tasks);
```

Outputs:

```
(
    [0] => Array
        (
            [id] => 122
            [dueDate] => 2013-11-01
            [title] => Fix the bar
            [priority] => high
        )

    [1] => Array
        (
            [id] => 107
            [dueDate] => 2013-11-22
            [title] => Fix the foo
            [priority] => high
        )

)
```

Note: as a defect of PHP compiler, static method ```R::$add(1,2)``` wouldn't compile. To get around, place the static method inside parenthesis ```(R::$add)(1,2)```

Curry functions:

(Note: ```R::$_``` is the placeholder parameter.)

```
  $f3 = function($a, $b, $c) {
      return $a*2+$b*3+$c*4;
  };
  $c = R::curry($f3);
  $cc = $c(R::$_,2,R::$_);
  print_r($cc(1,3));
```
Outputs:

```
  20
```

(More examples can be found in the /tests folder.)
