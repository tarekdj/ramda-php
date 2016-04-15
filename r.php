<?php 
class PlaceHolder {}

class R {
	
	public static $_;

    public static $add;
    public static $sum;
    public static $negate;
    public static $inc;
    public static $dec;
    public static $multiply;
    public static $divide;
    public static $max;
    public static $min;
    public static $pow;     // added
    public static $and;
    public static $or;
    public static $both;
    public static $either;
    public static $once;

    public static $toLower;
    public static $toUpper;

    public static $keys;
    public static $values;
    public static $prop;
    public static $propOr;
    public static $propSatisfies;
    public static $propEq;
    public static $filter;
    public static $map;
    public static $reduce;
    public static $concat;
    public static $reverse;
    public static $pair;
    public static $pick;
    public static $pickAll;
    public static $flatten;
    public static $zip;
    public static $adjust;
    public static $append;
    public static $prepend;
    public static $of;
    public static $contains;
    public static $partition;
    public static $indexOf;
    public static $groupBy;

    public static $tail;
    public static $init;
    public static $nth;
    public static $last;
    public static $head;
    public static $slice;

    public static $sortBy;
    public static $identity;
    public static $has;

    public static $where;
    public static $whereEq;
    public static $equals;
    public static $gt;
    public static $gte;
    public static $lt;
    public static $lte;
    public static $not;
    public static $identical;
    public static $all;
    public static $any;
    public static $always;
    public static $aperture;
    public static $assoc;
    public static $comparator;
    public static $sort;

    public static $apply;

    public static $ap;
    public static $lift;
    public static $liftN;
    public static $complement;
    public static $tap;
    public static $times;

    public static $compose;
    public static $chain;
    public static $flip;

    private static $_has;
    private static $_identity;
    private static $_pipe;

    public static $pipe;

    public static $take;
    public static $mapObjIndexed;
    public static $project;
    public static $useWith;

	private static function _isPlaceholder($a) {
		return self::$_ === $a;
	}

    private static function getParametersCount($fn) {
        $rf = new ReflectionFunction($fn);
        return count($rf->getParameters());
    }


	public static function _curry1($fn) {
        return $f1 = function ($a) use($fn) {
        	$arguments = func_get_args();
            if (count($arguments) === 0 || self::_isPlaceholder($a)) {
                return $f1;
            } else {
            	return call_user_func_array($fn, $arguments);
            }
        };
    }

    public static function _curry2($fn) {
        return $f2 = function($a = null, $b = null) use($fn) {
        	$arguments = func_get_args();
            switch (count($arguments)) {
            case 0:
                return $f2;
            case 1:
                return self::_isPlaceholder($a) ? $f2 : self::_curry1(function ($_b) use($fn,$a) {
                    return $fn($a, $_b);
                });
            default:
                if(self::_isPlaceholder($a) && self::_isPlaceholder($b))
                    return $f2;
                if(self::_isPlaceholder($a))
                    return self::_curry1(function ($_a) use($fn,$b) {
                        return $fn($_a, $b);
                    });
                else if(self::_isPlaceholder($b)) {
                    return self::_curry1(function ($_b) use($fn,$a) {
                        return $fn($a, $_b);
                    });
                } else return $fn($a, $b);
            }
        };
    }
    

    private static function _curry3($fn) {
        return $f3 = function ($a = null, $b = null, $c = null) use($fn) {
			$arguments = func_get_args();
            switch (count($arguments)) {
            case 0:
                return $f3;
            case 1:
                return self::_isPlaceholder($a) ? $f3 : self::_curry2(function ($_b, $_c) use($a) {
                    return $fn($a, $_b, $_c);
                });
            case 2:
                return self::_isPlaceholder($a) && self::_isPlaceholder($b) ? $f3 : self::_isPlaceholder($a) ? self::_curry2(function ($_a, $_c) use($fn, $b) {
                    return $fn($_a, $b, $_c);
                }) : self::_isPlaceholder($b) ? self::_curry2(function ($_b, $_c) use($fn, $a) {
                    return $fn($a, $_b, $_c);
                }) : self::_curry1(function ($_c) use($fn, $a,$b) {
                    return $fn($a, $b, $_c);
                });
            default:
                return self::_isPlaceholder($a) && self::_isPlaceholder($b) && self::_isPlaceholder($c) ? $f3 : self::_isPlaceholder($a) && self::_isPlaceholder($b) ? self::_curry2(function ($_a, $_b) use($fn,$c) {
                    return $fn($_a, $_b, $c);
                }) : self::_isPlaceholder($a) && self::_isPlaceholder($c) ? self::_curry2(function ($_a, $_c) use($fn, $b) {
                    return $fn($_a, $b, $_c);
                }) : self::_isPlaceholder($b) && self::_isPlaceholder($c) ? self::_curry2(function ($_b, $_c) use($fn, $a) {
                    return $fn($a, $_b, $_c);
                }) : self::_isPlaceholder($a) ? self::_curry1(function ($_a) use($fn, $b, $c) {
                    return $fn($_a, $b, $c);
                }) : self::_isPlaceholder($b) ? self::_curry1(function ($_b) use($fn, $a, $c) {
                    return $fn($a, $_b, $c);
                }) : self::_isPlaceholder($c) ? self::_curry1(function ($_c) use($fn, $a, $b) {
                    return $fn($a, $b, $_c);
                }) : $fn($a, $b, $c);
            }
        };
    }

    public static function _curryN($length, $received, $fn) {
        $fN = function () use($fn, $length, $received) {
            $combined = [];
            $argsIdx = 0;
            $left = $length;
            $combinedIdx = 0;
            $arguments = func_get_args();
            $n_received = count($received);
            $n_arguments = count($arguments);
                        
            while ($combinedIdx < $n_received || $argsIdx < $n_arguments) {
                if ($combinedIdx < $n_received && (!self::_isPlaceholder($received[$combinedIdx]) || $argsIdx >= $n_arguments)) {
                    $result = $received[$combinedIdx];
                } else {
                    $result = $arguments[$argsIdx];
                    $argsIdx += 1;
                }

                $combined[$combinedIdx] = $result;
                if (!self::_isPlaceholder($result)) {
                    $left -= 1;
                }
                $combinedIdx += 1;
            }
            return $left <= 0 ? call_user_func_array($fn, $combined) : self::_arity($left, self::_curryN($length, $combined, $fn));
        };
        return $fN;
    }

    private static function _arity($n, $fn) {
        /* eslint-disable no-unused-vars */
        switch ($n) {
        case 0:
            return function () use($fn){
            	$arguments = func_get_args();
                return call_user_func_array($fn, $arguments);
            };
        case 1:
            return function ($a0) use($fn){
            	$arguments = func_get_args();
                return call_user_func_array($fn, $arguments);
            };
        case 2:
            return function ($a0, $a1) use($fn){
            	$arguments = func_get_args();
                return call_user_func_array($fn, $arguments);
            };
        case 3:
            return function ($a0, $a1, $a2) use($fn){
            	$arguments = func_get_args();
                return call_user_func_array($fn, $arguments);
            };
        case 4:
            return function ($a0, $a1, $a2, $a3) use($fn){
            	$arguments = func_get_args();
                return call_user_func_array($fn, $arguments);
            };
        case 5:
            return function ($a0, $a1, $a2, $a3, $a4) use($fn){
            	$arguments = func_get_args();
                return call_user_func_array($fn, $arguments);
            };
        case 6:
            return function ($a0, $a1, $a2, $a3, $a4, $a5) use($fn){
            	$arguments = func_get_args();
                return call_user_func_array($fn, $arguments);
            };
        case 7:
            return function ($a0, $a1, $a2, $a3, $a4, $a5, $a6) use($fn){
            	$arguments = func_get_args();
                return call_user_func_array($fn, $arguments);
            };
        case 8:
            return function ($a0, $a1, $a2, $a3, $a4, $a5, $a6, $a7) use($fn){
            	$arguments = func_get_args();
                return call_user_func_array($fn, $arguments);
            };
        case 9:
            return function ($a0, $a1, $a2, $a3, $a4, $a5, $a6, $a7, $a8) use($fn){
            	$arguments = func_get_args();
                return call_user_func_array($fn, $arguments);
            };
        case 10:
            return function ($a0, $a1, $a2, $a3, $a4, $a5, $a6, $a7, $a8, $a9) use($fn){
            	$arguments = func_get_args();
                return call_user_func_array($fn, $arguments);
            };
        default:
            throw new Exception('First argument to _arity must be a non-negative integer no greater than ten');
        }
    }

    public static function curry($fn) {
        $n_params = self::getParametersCount($fn);

        return self::curryN($n_params, $fn);
        // return self::_curry1(function() use($fn, $n_params) {
        //     return self::curryN($n_params, $fn);
        // });
    }

    public static function curryN($length, $fn) {
        return self::_curryN($length, [], $fn);
/*
        return self::_curry2(function() use($length, $fn){
            if($length === 1) {
                return self::_curry1($fn);
            }
            return self::_arity($length, self::_curryN($length, [], $fn));
        });
        */
    }

    public static function _slice($args, $from, $to) {
        $arguments = func_get_args();
        switch(count($arguments)) {
            case 1:
                return self::_slice($args, 0, count($args));
            case 2:
                return self::_slice($args, $from, count($args));
            default:
                $list = [];
                $idx = 0;
                $len = max(0, min(count($args), $to) - $from);
                while($idx < $len) {
                    $list[$idx] = $args[$from + $idx];
                    $idx += 1;
                }
                return $list;
        }
    }

    public static function pipe() {
        $arguments = func_get_args();
        $length = count($arguments);
        if($length === 0) {
            throw new Exception("pipe requires at least one argument");
        }

        return self::_arity(count($arguments[0]), (self::$reduce)(self::$_pipe, $arguments[0], self::tail($arguments)));
    }

    public static function tail($array) {
        if(is_string($array)) {
            return substr($array, 1);
        }
        $result = [];
        for($i=1;$i<count($array);$i++) {
            array_push($result, $array[$i]);
        }
        return $result;
    }

    private static function flattRecursive($list) {
        $result = [];
        $ilen = count($list);
        for($idx = 0; $idx < $ilen; $idx++) {
            if(is_array($list[$idx])) {
                $value = self::flattRecursive($list[$idx]);
                $jlen = count($value);
                for($j = 0; $j < $jlen; $j++) {
                    array_push($result, $value[$j]);
                }
            } else {
                array_push($result, $list[$idx]);
            }
        }
        return $result;
    }

    private static function flatt($list) {
        $result = [];
        $ilen = count($list);
        for($idx = 0; $idx < $ilen; $idx++) {
            if(is_array($list[$idx])) {
                $value = $list[$idx];
                $jlen = count($value);
                for($j = 0; $j < $jlen; $j++) {
                    array_push($result, $value[$j]);
                }
            } else {
                array_push($result, $list[$idx]);
            }
        }
        return $result;
    }

    private static function _dispatchable($methodname, $xf, $fn) {
        return function() use($fn) {
            $arguments = func_get_args();
            $length = count($arguments);
            if($length === 0) {
                return $fn();
            }
            $obj = $arguments[$length-1];
            if(!is_array($obj)) {
                $args = self::_slice($arguments, 0, length -1);
                if(method_exists($obj, $methodname)) {
                    // TODO
                }
                if(is_callable($obj)) {
                    $transducer = $xf($args);
                    //$return self::transducer($obj); // TODO
                }
            }

            return call_user_func_array($fn, $arguments);
        };
    }

    private static function _indexOf($list, $a, $idx) {
        $l = count($list);
        while($idx < $l) {
            $item = $list[$idx];
            if($item == $a) {
                return $idx;
            }
            $idx += 1;
        }
        return -1;
    }

    private static function _contains($a, $list) {
        return self::_indexOf($list, $a, 0) >= 0;
    }

    public static function _map($fn, $functor) {
        return array_map($fn, $functor);
    }

    public static function map($fn, $functor) {
        return self::_curry2(self::dispatchable(
            'map', _xmap, function() {


            }));

    }

    public static function addIndex($fn) {
        return self::curryN(count($fn), function() use($fn) {
            $idx = 0;
            $arguments = func_get_args();
            $length = count($arguments);
            $origFn = $arguments[0];
            $list = $srguments[$length - 1];
            $args = self::_slice($arguments);
            $args[0] = function() use($origFn, $idx, $list){
                $arguments = func_get_args();
                $result = call_user_func_array($origFn,
                    self::_concat($arguments, [$idx, $list]));
                $idx +=1;
                return $result;
            };

            return call_user_func_array($fn, $arguments);
        });
    }

    private static function _checkForMethod($methodname, $fn) {
        return function() use($methodname, $fn){
            $arguments = func_get_args();
            $length = count($arguments);
            if($length ===0) {
                return $fn();
            }
            $obj = $arguments[$length-1];
            return is_array($obj) || !is_callable($obj->$methodname) ?
                     $fn($arguments) : $obj->$methodname($obj, self::_slice($arguments, 0, $length -1));
        };
    }

    public static function initialize() {
        self::$_ = new PlaceHolder();

        self::$keys = self::_curry1(function($obj) {
            return array_keys($obj);
        });

        self::$values = self::_curry1(function($obj) {
            return array_values($obj);
        });

        self::$prop = self::_curry2(function($p, $obj) {
            if(is_array($obj)) {
                if(!array_key_exists($p, $obj)) {
                    return null;
                }
                return $obj[$p];
            }
            return $obj->$p;
        });

        self::$propOr = self::_curry3(function($val, $p, $obj) {
            if(!$obj) {
                return $val;
            }
            $v = (self::$prop)($p, $obj);
            return $v ? $v : $val;
        });

        self::$propSatisfies = self::_curry3(function($pred, $p, $obj) {
            return $pred((self::$prop)($p, $obj));

        });

        self::$propEq = self::_curry3(function($name, $val, $obj) {
            return (self::$propSatisfies)((self::$equals)($val), $name, $obj);
        });

        self::$map = self::_curry2(function($fn, $functor) {
            return array_map($fn, $functor);
        });

        self::$filter = self::_curry2(function($pred, $filterable) {
            return array_filter($filterable, $pred);
        });

        self::$reduce = self::_curry3(function($fn, $initial_value, $array) {
            return array_reduce($array, $fn, $initial_value);
        });

        self::$flatten = self::_curry1(function($list) {
            return self::flattRecursive($list);
        });

        self::$partition = self::_curry2(function($pred, $list) {
            return (self::$reduce)(function($acc, $elt) use($pred){
                $xs = $acc[$pred($elt) ? 0 : 1];
                array_push($xs, $elt);
                return $acc;
            }, [[],[]], $list);
        });

        self::$indexOf = self::_curry2(function($target, $xs) {
            return self::_indexOf($xs, $target, 0);
        });

        self::$groupBy = self::_curry2(function($fn, $list) {
            return (self::$reduce)(function($acc, $elt) use($fn, $list) {
                $key = $fn($elt);
                if(!array_key_exists($key, $acc)) {
                    $acc[$key] = [];
                }
                array_push($acc[$key], $elt);
                return  $acc;
            }, [], $list);
        });

        self::$contains = self::_curry2(function($a, $list) {
            return self::_contains($a, $list);
        });

        self::$zip = self::_curry2(function($a, $b) {
            $rv = [];
            $la = count($a);
            $lb = count($b);
            $len = min($la, $lb);
            for($idx=0;$idx < $len;$idx++) {
                $rv[$idx] = [$idx < $la ? $a[$idx] : null, $idx < $lb ? $b[$idx]: null];
            }
            return $rv;
        });

        self::$adjust = self::_curry3(function($fn, $idx, $list) {
            $length = count($list);
            if($idx >= $length || $idx < -$length) {
                return $list;
            }
            $start = $idx < 0 ? $length : 0;
            $_idx = $start + $idx;
            //$_list = self::_concat($list);
            $_list[$_idx] = $fn($list[$_idx]);
            return $_list;
        });

        self::$append = self::_curry2(function($el, $list) {
            return array_merge($list, [$el]);
            
        });
        self::$prepend = self::_curry2(function($el, $list) {
            return array_merge([$el], $list);
        });

        self::$of = self::_curry1(function($x) {return [$x];});

        self::$chain = self::_curry2(function($fn, $monad) {
            return (self::$flatten)((self::$map)($fn, $monad));
        });

        self::$equals = self::_curry2(function($a,$b) {
            return $a === $b;
        });

        self::$gt = self::_curry2(function($a,$b) {
            return $a > $b;
        });

        self::$gte = self::_curry2(function($a,$b) {
            return $a >= $b;
        });

        self::$lt = self::_curry2(function($a,$b) {
            return $a < $b;
        });

        self::$lte = self::_curry2(function($a,$b) {
            return $a <= $b;
        });

        self::$not = self::_curry1(function($a) {
            return !$a;
        });

        self::$add = self::_curry2(function($a, $b) {
            return $a + $b;
        });

        self::$sum = (self::$reduce)(self::$add, 0);

        self::$negate = self::_curry1(function($n) {
            return -$n;
        });

        self::$inc = (self::$add)(1);
        self::$dec = (self::$add)(-1);

        self::$multiply = self::_curry2(function($a, $b) {
            return $a * $b;
        });
        self::$divide = self::_curry2(function($a, $b) {
            return $a / $b;
        });

        self::$pow = self::_curry2(function($a, $b) {
            return pow($a, $b);
        });

        self::$and = self::_curry2(function($a,$b) {return $a && $b;});
        self::$or = self::_curry2(function($a,$b) {return $a || $b;});

        self::$max = self::_curry2(function($a,$b) {
            return $b > $a ? $b : $a;
        });

        self::$min = self::_curry2(function($a,$b) {
            return $b < $a ? $b : $a;
        });

        self::$both = self::_curry2(function($f, $g) {
            return function() use($f,$g){
                $args = func_get_args();
                return call_user_func_array($f, $args) 
                        && call_user_func_array($g, $args);
            };
        });

       self::$either = self::_curry2(function($f, $g) {
            return function() use($f,$g){
                $args = func_get_args();
                return call_user_func_array($f, $args) 
                        || call_user_func_array($g, $args);
            };
        });

       self::$once = self::_curry1(function($fn) {
            $called = false;
            $result = null;
            return self::_arity(self::getParametersCount($fn), function() use($fn, &$called, &$result){
                if (!$called) {
                    $called = false;
                }

                if ($called) {
                    return $result;
                }
                $called = true;
                $arguments = func_get_args();
                $result = call_user_func_array($fn, $arguments);
                return $result;
            });
       });

        self::$concat = self::_curry2(function($set1, $set2) {
            if(is_string($set1) || is_string($set2)) {
                return $set1 . $set2;
            }
            if(!$set1) {
               $set1 = [];
            }
            if(!$set2) {
                $set2 = [];
            }

            return array_merge($set1, $set2);
        });

        self::$sortBy = self::_curry2(function($fn, $list) {
            $array = self::_slice($list);
            usort($array, $fn);
            return $array;
        });

        self::$toLower = self::_curry1(strtolower);
        self::$toUpper = self::_curry1(strtoupper);

        self::$_identity = function($x) {return $x;};
        self::$identity = self::_curry1(self::$_identity);
        self::$_has = function($prop, $obj) {
            if(is_array($obj)) {
                return array_key_exists($prop, $obj);
            }
            return $obj->$prop;
        };
        self::$has = self::_curry2(self::$_has);

        self::$identical = self::_curry2(function($a, $b) {
            if($a === $b) {
                return $a !== 0 || 1 / $a == 1 / $b;
            }
            return $a !== $a && $b !== $b;
        });

        self::$all = self::_curry2(function($fn, $list) {
            $length = count($list);
            for($i=0;$i<$length;$i++) {
                if(!$fn($list[$i])) {
                    return false;
                }
            }
            return true;
        });

        self::$always = self::_curry1(function($val) {
            return function() use($val){return $val;};
        });

        self::$any = self::_curry2(function($fn, $list) {
            $length = count($list);
            for($i=0;$i<$length;$i++) {
                if($fn($list[$i])) {
                    return true;
                }
            }
            return false;
        });

        self::$aperture = self::_curry2(function($n, $list) {
            $idx = 0;
            $limit = count($list) - ($n - 1);
            $acc = [];
            while ($idx < $limit) {
                array_push($acc, self::_slice($list, $idx, $idx + $n));
                $idx += 1;
            }
            return $acc;
        });

        self::$apply = self::_curry2(function($fn, $args) {
            return call_user_func_array($fn, $args);
        });

        self::$assoc = self::_curry3(function($prop, $val, $array) {
            $result = [];
            foreach($array as $key => $value) {
                $result[$key] = $value;
            }
            $result[$prop] = $val;
            return $result;
        });

        self::$comparator = self::_curry1(function($pred) {
            return function($a,$b) use($pred){
                return $pred($a, $b) ? -1 : $pred($b, $a) ? 1 : 0;
            };
        });

        self::$sort = self::_curry2(function($comparator, $list) {
            $l = $list;
            usort($l, $comparator);
            return $l;
        });

        // self::$flip = self::_curry1(function($fn) {
        //     return self::_curry2(function() use($fn){
        //         $arguments = func_get_args();
        //         $a = $arguments[0];
        //         $arguments[0] = $arguments[1];
        //         $arguments[1] = $a;

        //         return call_user_func_array($fn, $arguments);
        //     });
        // });

        self::$_pipe = function ($f, $g) {
            return function() use($f, $g){
                $arguments = func_get_args();
                $result = call_user_func_array($f, $arguments);
                return call_user_func_array($g, [$result]);
            };
        };

        self::$pipe = function() {
            $arguments = func_get_args();
            if(count($arguments) === 0) {
                throw new Exception("pipe requires at least one argument");
            }
            $l = self::getParametersCount($arguments[0]);
            return self::_arity($l, (self::$reduce)(self::$_pipe, $arguments[0], (self::$tail)($arguments)));
        };

        self::$compose = function() {
            $arguments = func_get_args();
            if(count($arguments) === 0) {
                throw new Exception("pipe requires at least one argument");
            }
            return call_user_func_array(self::$pipe, (self::$reverse)($arguments));
        };

        self::$mapObjIndexed = self::_curry2(function($fn, $obj) {
            return (self::$reduce)(function($acc, $key) use($fn, $obj) {
                $acc[$key] = $fn($obj[$key], $key, $obj);
                return $acc;
            }, [], (self::$keys)($obj));
        });

        self::$take = self::_curry2(function ($n, $xs) {
            return (self::$slice)(0, $n < 0 ? INF : $n, $xs);
        });

        self::$useWith = self::_curry2(function($fn, $transformers) {
            $l = count($transformers);
            return self::curryN($l, function() use($l, $transformers, $fn) {
                $args = [];
                $arguments = func_get_args();
                for($i=0;$i<$l;$i++) {
                    array_push($args, call_user_func_array($transformers[$i], [$arguments[$i]]));
                }
                return call_user_func_array($fn, $args);
            });
        });

        self::$nth = self::_curry2(function ($offset, $list) {
            if(is_array($list)) {
                $idx = $offset < 0 ? count($list) + $offset : $offset;
                return array_key_exists($idx, $list) ? $list[$idx] : null;
            } else {
                $len = strlen($list);
                $idx = $offset < 0 ? $len + $offset : $offset;
                return $idx >=0 && $idx < $len ? $list[$idx] : '';
            }
        });

        self::$head = (self::$nth)(0);
        self::$last = (self::$nth)(-1);

        self::$slice = self::_curry3(function($fromIndex, $toIndex, $list) {
            if(is_string($list)) {
                return substr($list, $fromIndex, $toIndex - $fromIndex);
            }
            return  array_splice($list, $fromIndex, $toIndex - $fromIndex);
        });     // TODO: _checkForMethod

        self::$init = (self::$slice)(0, -1);
        self::$tail = (self::$slice)(1, 2147483647);    // NOTE: was Infinity in js

        self::$tap = self::_curry2(function($fn, $x) {
            $fn($x);
            return $x;
        });

        self::$times = self::_curry2(function($fn, $n) {
            if($n < 0 || is_nan($n)) {
                throw new Exception('n must be a non-negative number');
            }
            $list = [];
            for($i=0;$i<$n;$i++) {
                array_push($list, $fn($i));
            }
            return $list;
        });

        //self::$init = self::_curry

/*
        self::$liftN = self::_curry2(function($arity, $fn) {
            $lifted = self::curryN($arity, $fn);
            return self::curryN($arity, function() use($fn, $lifted) {
                $arguments = func_get_args();
                return self::$_reduce(self::$ap, self::map($lifted, $arguments[0]),
                                        self::_slice($arguments,1));
            });
        });

        self::$lift = self::_curry1(function($fn) {
            $rf = new ReflectionFunction($fn);
            $n_params = count($rf->getParameters());
            return self::liftN($n_params, $fn);
        });

        self::$complement = self::$lift(self::$not);
*/

        self::$whereEq = self::_curry2(function($spec, $testObj) {
            return (self::$where)((self::$map)(self::$equals, $spec), $testObj);
        });

        self::$where = self::_curry2(function($spec, $testObj) {
            if(is_array($testObj)) {
                foreach($spec as $prop => $pred) {
                    if(!array_key_exists($prop, $testObj) || !$pred($testObj[$prop])) {
                        return false;
                    }
                }
                return true;
            } else {
                foreach($spec as $prop => $pred) {
                    if(!$testObj->$prop || !$pred($testObj->$prop)) {
                        return false;
                    }
                }
                return true;
            }
        });

        self::$reverse = self::_curry1(function($list) {
            if(is_string($list)) {
                return implode((self::$reverse)(split('', $list)),'');
            }
            return array_reverse($list);
        });

        self::$pair = self::_curry2(function($e1, $e2) {return [$e1, $e2];});

        self::$pick = self::_curry2(function($names, $obj) {
            $result = [];
            if(is_array($obj)) {
                foreach($names as $name) {
                    if(array_key_exists($name, $obj)) {
                        $result[$name] = $obj[$name];
                    }
                }
            } else {
                foreach($names as $name) {
                    if($obj->$name) {
                        $result[$name] = $obj->$name;
                    }
                }
            }
            return $result;
        });

       self::$pickAll = self::_curry2(function($names, $obj) {
            $result = [];
            if(is_array($obj)) {
                foreach($names as $name) {
                    $result[$name] = array_key_exists($name, $obj) ? $obj[$name] : null;
                }
            } else {
                foreach($names as $name) {
                    $result[$name] = $obj->$name;
                }
            }
            return $result;
        });

        self::$project = (self::$useWith)(self::$map, [self::$pickAll, self::$identity]);

    }
    
}

R::initialize();

?>
