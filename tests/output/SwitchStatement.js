//Auto-generated file by PHP-To-Javascript at Sun, 24 Feb 13 21:08:02 +1100
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file SwitchStatement.php and then reconvert to make any changes



function testSwitchFunction (name, value  /*false*/){
		if(typeof value === "undefined"){
			value = false;
		}


	var result = false;

	switch(name){

		case('output'):{
			result =  'output';
			break;
		}

		case('silent'):{
			result =  'notloud';
			break;
		}

		case('custom'):{
			result = value;
			break;
		}

		default:{
			result = 'Unknown';
		}
	}

	return result;
}


assert(testSwitchFunction('output'), 'output');
assert(testSwitchFunction('custom', 'bar'), 'bar');
assert(testSwitchFunction('shamoan'), 'Unknown');

