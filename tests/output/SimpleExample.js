//Auto-generated file by PHP-To-Javascript at Sat, 23 Feb 13 18:27:07 +1100
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file SimpleExample.php and then reconvert to make any changes


total = 0;

for (i=1 ; i<=5 ; i++){
	total += i;
}

assert(total, 15);

value = '123';
delta = 123;
value = +value + delta;

assert(value, 246);

result = str_pad(value, 6, '0', 'STR_PAD_LEFT');

assert(result, '000246');

