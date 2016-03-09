<?php 
class PlaceHolder {}

class R {
	
	public static $_;

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

    
    public static $curry;
    public static $add;
    public static $sum;
    public static $curryN;

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


    public static function init() {
        self::$_ = new PlaceHolder();

    }
    
}

R::init();

?>
