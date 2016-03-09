<?php 
include 'r.php';

function f2($a, $b) {
    return $a + 2*$b;
}

function f1($a) {
    return 2 * $a;
}

$c = R::_curry1(f1);
print_r($c(6));

$c = R::_curry2(f2);

//print_r($c);

echo "\n";
print_r($c(1,3));
$cc = $c(1);
echo "\n";
print_r($cc(3));
$cc = $c(R::$_, 3);
echo "\n";
print_r($cc(1));
echo "\n";
echo "\n";

function f3($a, $b, $c) {
    return $a*2+$b*3+$c*4;
}
$c = R::_curryN(3, [], f3);
echo "\n";
print_r($c(1,2,3));
echo "\n";
$cc = $c(1,2,R::$_);
echo "\n";
print_r($cc(3));
echo "\n";
$cc = $c(R::$_,2,R::$_);
echo "\n";
print_r($cc(1,3));
echo "\n";

$c = R::curryN(3, f3);
echo "\n";
print_r($c(1,2,3));
echo "\n";

$c = R::curryN(1, f1);
echo "\n";
print_r($c(14));
echo "\n";

$c = R::curry(f1);
echo "\n";
print_r($c(15));
echo "\n";

$c = R::curry(f3);
echo "\n";
print_r($c(1,2,3));
echo "\n";
$cc = $c(1,2,R::$_);
echo "\n";
print_r($cc(3));
echo "\n";
$cc = $c(R::$_,2,R::$_);
echo "\n";
print_r($cc(1,3));
echo "\n";
?>
