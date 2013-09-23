(function(){
//Auto-generated file by PHP-To-Javascript at Mon, 23 Sep 13 15:09:08 +0200
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file continue.php and then reconvert to make any changes



var test = function (){
	var total = 0;

	for(var i=0;i<10;i++){

		if ((i%2) == 0) {
			continue;
		}

		total++;
	}


	assert(total, 5);

	testEnd();

};
test();})();
