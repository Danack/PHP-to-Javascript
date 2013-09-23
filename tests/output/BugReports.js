(function(){
//Auto-generated file by PHP-To-Javascript at Mon, 23 Sep 13 15:09:08 +0200
//
//DO NOT EDIT - all changes will be lost.
//
//Please edit the file BugReports.php and then reconvert to make any changes


var test = function (){
	//https://github.com/Danack/PHP-to-Javascript/issues/14
	//Modulus doesn't work

	var p2 = 8;
	var step = 6;

	p2 -= (p2 % step);

	assert(p2, 6);

	//*************************************************************
	//*************************************************************

	//https://github.com/Danack/PHP-to-Javascript/issues/15
	var countValue = 4;
	countValue--;

	assert(countValue, 3);

	countValue--;

	assert(countValue, 2);


	testEnd();


};
test();

})();
