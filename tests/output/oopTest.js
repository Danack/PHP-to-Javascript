/**
 * Created by jozefm on 20.9.2013.
 */
window._DEFINE_ = {
    'constructor':'__construct'
};
function extend(to, from){
    var TmpTo = function(){};
    var TmpFrom = function(){};
    var Parent = function(){};
    TmpTo.prototype = new to();
    TmpFrom.prototype = new from();
    Parent.prototype = new from();
    var i;
    for (i=0;i<from._PRIVATE_.length;i++){
        delete TmpFrom.prototype[from._PRIVATE_[i]];
    }
    /*for(i in TmpFrom.prototype){
        if (!TmpFrom.prototype.hasOwnProperty(i)) continue;
        delete TmpFrom.prototype[i];
    }*/

    /*if (TmpTo.prototype.hasOwnProperty(_DEFINE_.constructor)){
        var constructor = TmpFrom.prototype[_DEFINE_.constructor];
        tmpFromConstructor = TmpFrom.prototype[_DEFINE_.constructor];
        delete TmpFrom.prototype[_DEFINE_.constructor];
    }*/
    to.prototype = new TmpFrom();
}
var assertUl;
function assertCategory(cat){
    $(document.body).append('<div>'+cat+'</div>')
    assertUl = $('<ul>')
    $(document.body).append(assertUl)
}
function assert(a,b,state){
    if (typeof state == "undefined"){
        state=true;
    }
    var ok=false;;
    try{
        ok = a==b;
        if (!state) ok = !ok;
    }catch(e){
        if (!state) ok=true;
    }
    var operand = state ? '==' : '!=';
    var color = ok ? "green" : 'red';
    $(assertUl).append('<li style="background-color: '+color+'">'+a+' <b style="color: blue;">'+operand+'</b> '+b+'</li>');
}


function Foo1(){
    this.private='Foo1.private';
    this.foo1Private='Foo1.foo1Private';
    this.public='Foo1.public';
    this.foo1Public = "Foo1.foo1Public";
    this.privateTestConstruct=null;
    this.parent;

    this.__construct = function(){
        this.privateTestConstruct = 'Foo1.construct';
        return this;
    };

    this.privateFunc = function(){
        return 'Foo1.privateFunc';
    };

    this.private2Func = function(){
        return 'Foo1.private2Func';
    };
    this.publicFunc = function(){
        return 'Foo1.publicFunc';        
    };
    this.foo1Func = function(){
        return 'Foo1.foo1Func';
    };
    this.test = function(){
        assert(this.private,'Foo1.private');
        assert(this.public,'Foo1.public');
        assert(this.privateFunc(),'Foo1.privateFunc');
        assert(this.publicFunc(),'Foo1.publicFunc');
    };
    this.parentTest = function(){
        assert(this.private,'Foo1.private');
        assert(this.public,'Foo2.public');
        assert(this.privateFunc(),'Foo1.privateFunc');
        assert(this.publicFunc(),'Foo2.publicFunc');
    };
}
Foo1.static = "Foo1.static";
Foo1._PRIVATE_ = ['private','privateFunc','foo1Private','Foo1.private2Func'];


function Foo2(){
    this.private='Foo2.private';
    this.public='Foo2.public';
    this.privateTestConstruct=null;

    this.__construct = function(){
        parent.__construct();
        this.privateTestConstruct = 'Foo2.construct';
        return this;
    };

    this.privateFunc = function(){
        return 'Foo2.privateFunc';
    };
    this.publicFunc = function(){
        return 'Foo2.publicFunc';
    };
    this.foo2Func = function(){
        return 'Foo2.foo2Func';
    };
    this.test = function(){
        //parent.parentTest();
        //assert(parent.publicFunc(),'Foo1.publicFunc');
        //assert(parent.public,'Foo1.public');
        assert(this.private,'Foo2.private');
        assert(this.foo1Private,'Foo1.foo1Private',false);
        assert(this.public,'Foo2.public');
        assert(this.foo1Public,'Foo1.foo1Public');
        assert(this.privateFunc(),'Foo2.privateFunc');
        assert(this.private2Func,'Foo1.private2Func',false);
        assert(this.publicFunc(),'Foo2.publicFunc');
        assert(this.foo1Func(),'Foo1.foo1Func');
    };
    this.parentTest = function(){
        assert(this.private,'Foo2.private');
        assert(this.public,'Foo3.public');
        assert(this.privateFunc(),'Foo2.privateFunc');
        assert(this.publicFunc(),'Foo3.publicFunc');
        assert(this.foo2Func(),'Foo2.foo2Func');
    };
}
Foo2._PRIVATE_ = ['private','privateFunc','privateTestConstruct'];
extend(Foo2, Foo1);

function Foo3(){
    this.private='Foo3.private';
    this.public='Foo3.public';
    this.privateTestConstruct=null;

    this.__construct = function(){
        parent.__construct();
        this.privateTestConstruct = 'Foo3.construct';
        return this;
    };

    this.privateFunc = function(){
        return 'Foo3.privateFunc';
    };
    this.publicFunc = function(){
        return 'Foo3.publicFunc';
    };
    this.foo3Func = function(){
        return 'Foo3.foo3Func';
    };
    this.test = function(){
        parent.parentTest();
        assert(parent.publicFunc(),'Foo2.publicFunc');
        assert(parent.public,'Foo2.public');
        assert(this.private,'Foo3.private');
        assert(this.public,'Foo3.public');
        assert(this.privateFunc(),'Foo3.privateFunc');
        assert(this.publicFunc(),'Foo3.publicFunc');
        assert(this.foo1Func(),'Foo1.foo1Func');
        assert(this.foo1Func(),'Foo2.foo2Func');
    };
}
Foo3._PRIVATE_ = ['private','privateFunc','privateTestConstruct'];
//extend(Foo3, Foo2);
setTimeout(function(){
    var foo1 = new Foo1();
    var foo2 = new Foo2();
    assertCategory('Foo1');
    foo1.test();
    assertCategory('Foo2');
    foo2.test();
},1);
