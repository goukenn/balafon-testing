(function (_modules) {
    _modules[0] = (function(){
        const c = {ref:null};                
        return function(){
            if ('u'<typeof(c.init)){
                c.init = 1;
                c.ref = (function () {
                    const module = { exports: {} , default:null};
                    class Panel { }
                    module.exports = {lib : Panel};
                    return { default: module.exports, ...module.exports };
                }).apply([]);
            }
            return c.ref;
        };
    })();
    _modules[0].location = "";
    const {lib} = _modules[0].apply();
    console.log(lib);
    (function () {
        console.log('call 2');
        const { lib } = _modules[0].apply();
        console.log(lib);
    })();
})({})