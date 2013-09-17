/**
 * Created by jozefm on 17.9.2013.
 */
var success = true;
function assert(a,b){
    if (a!==b){
        success=false;
    }
}
function testEnd(){

}
function testStart(id){
    success=true;
    $('#'+id).html('testing...');
}
function setTestsResult(id){
    var color = success ? 'green':'red';
    $('#'+id+'>span').html('finish').css('background-color','green');
    $('#'+id).prev().html('finished.');
}