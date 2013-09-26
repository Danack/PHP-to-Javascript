<?php
$value = 1;
function foo($x){ echo "x is ".$x;}
?>

This is outside of PHP.

<?php  echo "Value is ".$value; ?>
<?php/*ignore*/
foo(5);
?>