

var fooFunc = function (){
    this.ttt=1;
    this['ttt']=1.5;
    this.foo=2;
    tthis.ttt=3;
    this.htt=3.5;
    this.ttth=3.7;
    thiss.ttt=4;
    tthiss.ttt=5;
    this.tttt=6;
    this.tttti=7;
     /* --this-- */
    // --this--
    this.ttt=5;
    ;this.ttt=5;
    var s =this.ttt=5;
    var g =this;
    this['s']=5;
    this['ttts']=5;
    this(5);
    var f = this+"";
    var foo2 = function(){
        this.ttt=6;
    };
    tthis.ttt=9;
    this.foo4 = "-this-\"-this\"\"'-this-"+'-this-\"-this\"\" "-this-';
    this.ttt=function(){
        this.ttt=5;
    };
    {
        {
            this.ttt=5;
        }
        var foo3 = function(){
            this.foo3=6;
        };
        this.foo4 = "-this-\"-this\"\"'-this-"+'-this-\"-this\"\" "-this-';
        this.foo3=function(){
            this.x=5;
        }
    }
    this.foo3=7;
};

/*
*/


function replaceThis(func,replace,words){
    var str = func.toString();
    var lastWord = "";
    var isBrackets=-1;
    var inString="";
    var inComment=null;
    var prevChar="";
    var isThis=false;
    var wantString=false;
    var currentWords=["this"];
    function setPrevChar(char){
        if (" \n\r".indexOf(char)==-1){
            prevChar = char;
        }
    }
    function checkThis(char,nextChar){
        if (char=='\''){
            var t = 5;
        }
        if(inComment){
            if ((inComment == "/" && [10,13].indexOf(char.charCodeAt(0))>-1) || inComment == "*" && char=="/"){
                inComment=null;
            }
            return false;
        }
        if ("*/".indexOf(char)>-1 && prevChar=="/"){
            inComment=char;
            return false;
        }
        if (!wantString && inString!=""){
            inString = char==inString && prevChar!="\\"? "" : inString;
            return false;
        }
        if (!wantString && ["'",'"'].indexOf(char)>=0 && prevChar!="\\"){
            inString = char;
            return false;
        }
        if (char=="{" ){
            if (!(isBrackets == 0 && ")".indexOf(prevChar)==-1))
                isBrackets+=1;
            return false;
        }
        if (isBrackets && char=="}" && isBrackets>0){
            isBrackets--;
            return false;
        }
        if (isBrackets>0){
            return false;
        }
        if (isThis && lastWord==""){
            if (isThis===true){
                if (char=="."){
                    isThis=1;
                    return false;
                }
                if (char=="["){
                    isThis=2;
                    wantString=true;
                    return false;
                }
                isThis=false;
                return false;
            }
            if (isThis==2){
                if (["'",'"'].indexOf(char)>=0 && prevChar=='['){
                    inString=char;
                    return false;
                }
                if (!(wantString && ["'",'"'].indexOf(prevChar)>=0)){
                    wantString=false;
                    isThis=false;
                    return false;
                }
            }
        }
        var find = false;
        for(var e=0;e<currentWords.length;e++){
            var _word = currentWords[e];
            if (currentWords[e].charAt(lastWord.length)==char){
                if (currentWords[e].length == lastWord.length+1 && " ;.+-/*['\"({!=><".indexOf(nextChar)>=0){
                    if (isThis){
                        var len = currentWords[e].length + isThis - 1;
                        lastWord="";
                        isThis=false;
                        wantString=false;
                        return len;
                    }else if (".[".indexOf(nextChar)>=0){
                        isThis=true;
                        currentWords = words.slice(0);
                        lastWord="";
                        wantString=false;
                        return false;
                    }
                }
                find=true;
            }else if(!(currentWords.length==1 && currentWords[0]=='this')){
                currentWords.splice(e,1);
                e--;
            }
        }
        if (currentWords.length==0){
            currentWords = ['this'];
        }
        if (find){
            lastWord+=char;
        }else{
            isThis=false;
            lastWord="";
            wantString=false;
        }
        return false;
    }
    var i=0;
    do{
        var chr = str.charAt(i),varName;
        document.body.childNodes[0].remove();
        document.write("<pre>"+str.substring(0,i)+"<b style='color:red;'>["+chr.toUpperCase()+"]</b>"+str.substring(i+1,str.length)+"</pre>");
        if (varNameLen = checkThis(chr,str.charAt(i+1))){
            str=str.substring(0,i-4-varNameLen)+replace+str.substring(i-varNameLen,str.length);
            i+=replace.length-4;
        }
        setPrevChar(chr)
    }while(i++<str.length);
    return str;
}
document.write("<body><a></a></body>");

replaceThis(fooFunc,"__SELF__",['thh','tth','htt','ttth','ttt','foo3','foo4']);