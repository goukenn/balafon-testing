<?php
require_once __DIR__.'/FExManifestBrowserSpecific.php';

/**
* auto generate doc.
*/
class FExManinest
{

    /**
    * auto generate doc.
    * @var array
    */
    var $content_scripts;

    /**
    * auto generate doc.
    * @var string
    */
    var $description;

    /**
    * auto generate doc.
    * @var mixed
    */
    var $icons;

    /**
    * auto generate doc.
    * @var number
    */
    var $manifest_version;

    /**
    * auto generate doc.
    * @var string
    */
    var $name;

    /**
    * auto generate doc.
    * @var number
    */
    var $version;

    /**
    * auto generate doc.
    * @var mixed
    */
    var $permissions;

    /**
    * auto generate doc.
    * @var mixed
    */
    var $background; // 
    // "scripts": ["background.js"],
    // "persistent": false

    /**
    * auto generate doc.
    * @var ?FExManifestBrowserSpecific
    */
    var $browser_specific_settings;
}