(function(){
//Auto-generated file by PHP-To-Javascript at Mon, 23 Sep 13 15:09:08 +0200
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


var test = function (){
	assert(testSwitchFunction('output'), 'output');
	assert(testSwitchFunction('custom', 'bar'), 'bar');
	assert(testSwitchFunction('shamoan'), 'Unknown');

	testEnd();
};
test();

})();
