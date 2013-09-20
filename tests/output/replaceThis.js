

var fooFunc = function (){
    this.foo=5;
    /* --this-- */
    // --this--
    this.ttt=5;
    ;this.ttt=5;
    var s =this.ttt=5;
    var g =this;
    this['s']=5;
    this(5);
    var f = this+"";
    tthis.ttt=9;
    thiss.ttt=5;
    tthiss.ttt=5;
    var foo2 = function(){
        this.foo=6;
    };
    this.foo4 = "-this-\"-this\"\"'-this-"+'-this-\"-this\"\" "-this-';
    this.foo3=function(){
        this.x=5;
    };
    {
        {
            this.foo=5;
        }
        var foo3 = function(){
            this.foo=6;
        };
        this.foo4 = "-this-\"-this\"\"'-this-"+'-this-\"-this\"\" "-this-';
        this.foo3=function(){
            this.x=5;
        }
    }
    this.foo3=7;
};



function replaceThis(func,replace,words){
    var str = func.toString();
    //str = str.substring(str.indexOf('function (){')+"function (){".length,str.lastIndexOf("}"));
    var lastWord = "";
    var isBrackets=-1;
    var inString="";
    var inComment=null;
    var prevChar="";
    var isThis=false;
    var currentWords=["this"];
    function setPrevChar(char){
        if (" ".indexOf(char)==-1){
            prevChar = char;
        }
    }
    function checkThis(char,nextChar){
        if (char=='9'){
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
        if (inString!=""){
            inString = char==inString && prevChar!="\\"? "" : inString;
            return false;
        }
        if (["'",'"'].indexOf(char)>=0 && prevChar!="\\"){
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
        for(var e in currentWords){
            if (!currentWords.hasOwnProperty(e)) continue;
            if (currentWords[e].charAt(lastWord.length)==char){
                if (currentWords.length == lastWord.length-1){
                    if (isThis){
                        var tmp = lastWord;
                        lastWord="";
                        isThis=false;
                        return tmp;
                    }else{
                        isThis=true;
                        currentWords = words.slice(0);
                        lastWord="";
                    }
                }
            }else if(lastWord.length>1){
                delete currentWords[e];
            }
        }
        if (currentWords.length==0){
            isThis=false;
            lastWord="";
            currentWords = ['this'];
        }
        lastWord+=char;
        if ("this".indexOf(char)>=0){
            if (lastWord=="this"){
                lastWord="";
                return (" ;.+-*/[({!=><".indexOf(nextChar)>=0)
            }
            return false;
        }else{
            lastWord="";
            return false;
        }
    }
    var i=0;
    do{
        var chr = str.charAt(i);
        document.body.childNodes[0].remove();
        document.write("<pre>"+str.substring(0,i)+"<b>["+chr.toUpperCase()+"]</b>"+str.substring(i+1,str.length)+"</pre>");
        if (checkThis(chr,str.charAt(i+1))){
            str=str.substring(0,i-3)+replace+str.substring(i+1,str.length);
            i+=replace.length-4;
        }
        setPrevChar(chr)
    }while(i++<str.length);
    return str;
}
document.write("<body><a></a></body>");
console.log(document.body);
console.log(replaceThis(fooFunc,"__SELF__"));