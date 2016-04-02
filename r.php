<?php 
class PlaceHolder {}

class R {
	
	public static $_;

    public static $curry;
    public static $add;
    public static $sum;
    public static $curryN;

    public static $keys;
    public static $values;
    public static $prop;
    public static $filter;
    public static $map;

    public static $where;
    public static $whereEq;
    public static $equals;
    public static $gt;
    public static $gte;
    public static $lt;
    public static $lte;
    public static $not;

    public static $ap;
    public static $lift;
    public static $liftN;
    public static $complement;


	private static function _isPlaceholder($a) {
		return self::$_ === $a;
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
        $rf = new ReflectionFunction($fn);
        $n_params = count($rf->getParameters());

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
                $len = max(0, min(count($args), to) - $from);
                while($idx < $len) {
                    $list[$idx] = $args[$from + $idx];
                    $idx += 1;
                }
                return $list;
        }
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

    private static function _xgroupBy() {
        // TODO
    }
/*
    public static function groupBy($fn, $list) {
        $groupBy = self::_reduce(function($acc, $elt) use($fn, $list){
            $key = $fn($elf);
            $acc[$key] = self::append($elt, $acc[$key] || ($acc[$key]=[]));
            return $acc;
        }, {}, $list);
        return self::_curry2(self::_dispatchable('groupBy', self::x_groupBy, $groupBy));
    }
*/
    public static function _concat($set1, $set2) {
        $set1 = $set1 || [];
        $set2 = $set2 || [];
        return array_merge($set1, $set2);
    }

    public static function append($el, $list) {
        return self::_curry2(function() use($el, $list){
            return self::_concat($list, [$el]);
        });
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

    public static function init() {
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

        self::$map = self::_curry2(function($fn, $functor) {
            return array_map($fn, $functor);

        });

        self::$filter = self::_curry2(function($pred, $filterable) {
            return array_filter($filterable, $pred);
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

    }
    
}

R::init();

?>
