<?php
include 'r.php';

$arr = array('a'=>'A', 'b'=>'B');
print_r(call_user_func(R::$keys, $arr));
print_r(call_user_func(R::$values, $arr));
print_r(call_user_func(R::$prop, 'a', $arr));

echo "\n";
?>
