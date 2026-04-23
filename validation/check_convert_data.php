<?php
use IGK\Helper\Activator;
use IGK\System\Console\Logger;
use IGK\System\Html\Forms\Validations\ConvertTypeValidator;
use IGK\System\Html\Forms\Validations\FormValidation;

$v_fv = new FormValidation;
$v_fv->storage = false;
$book_fields = [
    'title'=>['type'=>'string'],
    'page'=>['type'=>'int']
];
$book_validator = new ConvertTypeValidator;
$book_validator->supportArray(true)
->returnType(DummyBookDefinition::class)
->setFields($book_fields); 
$g = $v_fv->fields([
    "name"=>[""],
    "email"=>[],
    "phone"=>[],
    "books"=>[
        "validator"=>$book_validator
    ]
])->validate((array)json_decode(file_get_contents(__DIR__."/data.json")));
if(($g === false) && ($v_fv->hasError()))
{
    $error = ['msg'=>'data not valid', 'errors'=>$v_fv->getErrors()];
    Logger::danger(json_encode($error, JSON_PRETTY_PRINT));
    igk_exit(-1);
}
/**
* auto generate doc.
*/
class DummyDefinition{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $name;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $books;
}
/**
* auto generate doc.
*/
class DummyBookDefinition{
    /**
    * auto generate doc.
    * @var mixed
    */
    var $title;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $page;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $user;
    /**
    * auto generate doc.
    * @var mixed
    */
    var $contributor;
}
$i = Activator::CreateNewInstance(DummyDefinition::class, $g);
igk_wln_e($i);