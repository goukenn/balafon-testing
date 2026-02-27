# Conditional function declaration 
```php

if (!function_exists('local_conditional')){
    function local_conditional(){
        igk_wln_e("basic call");
    }
}
// single is not allowed 
if (!function_exists('version'))
    function version(){
        echo 'sample';
    };
```

