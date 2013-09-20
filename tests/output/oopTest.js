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
    TmpTo = new to();
    TmpFrom = new from();
    Parent = new from();
    var i;
    for (i=0;i<from._PRIVATE_;i++){
        delete TmpFrom.prototype[from._PRIVATE_[i]];
    }
    for(i in TmpFrom.prototype){
        if (!TmpFrom.prototype.hasOwnProperty(i)) continue;
        delete TmpFrom.prototype[i];
    }

    if (TmpTo.prototype.hasOwnProperty(_DEFINE_.constructor)){
        var constructor = TmpFrom.prototype[_DEFINE_.constructor];
        tmpFromConstructor = TmpFrom.prototype[_DEFINE_.constructor];
        delete TmpFrom.prototype[_DEFINE_.constructor];
    }
    to.prototype = new from();
}
function assert(a,b){

}

function Foo4(){
    this.parent = Foo1.prototype;
}

function Foo1(){
    this.private='Foo1.private';
    this.public='Foo1.public';
    this.privateTestConstruct=null;
    this.parent;

    this.__construct = function(){
        this.privateTestConstruct = 'Foo1.construct';
        return this;
    };

    this.privateFunc = function(){
        return 'Foo1.privateFunc';
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
        assert(this.privateFunc,'Foo1.privateFunc');
        assert(this.publicFunc,'Foo1.publicFunc');
    };
    this.parentTest = function(){
        assert(this.private,'Foo1.private');
        assert(this.public,'Foo2.public');
        assert(this.privateFunc,'Foo1.privateFunc');
        assert(this.publicFunc,'Foo2.publicFunc');
    };
}
Foo1.static = "Foo1.static";
Foo1._PRIVATE_ = ['private','privateFunc'];

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
        parent.parentTest();
        assert(parent.publicFunc(),'Foo1.publicFunc');
        assert(parent.public,'Foo1.public');
        assert(this.private,'Foo2.private');
        assert(this.public,'Foo2.public');
        assert(this.privateFunc,'Foo2.privateFunc');
        assert(this.publicFunc,'Foo2.publicFunc');
        assert(this.foo1Func,'Foo1.foo1Func');
    };
    this.parentTest = function(){
        assert(this.private,'Foo2.private');
        assert(this.public,'Foo3.public');
        assert(this.privateFunc,'Foo2.privateFunc');
        assert(this.publicFunc,'Foo3.publicFunc');
        assert(this.foo2Func,'Foo2.foo2Func');
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
        assert(this.privateFunc,'Foo3.privateFunc');
        assert(this.publicFunc,'Foo3.publicFunc');
        assert(this.foo1Func,'Foo1.foo1Func');
        assert(this.foo1Func,'Foo2.foo2Func');
    };
}
Foo3._PRIVATE_ = ['private','privateFunc','privateTestConstruct'];
extend(Foo3, Foo2);

