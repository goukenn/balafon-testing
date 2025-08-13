# PHP NOTE

## NOTE 1: Function can be nested in php 
```php
function a()
{
    function b(){

        echo "b call";
    } 
}
```

`b` will be defined only the first time a is called. and can be call outside the `a` function 