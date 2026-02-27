<?php
require_once __DIR__.'/FExManifestBrowserSpecific.php';

/**
* auto generate doc.
*/
class FExManinest
{
    /**
     * @var array
     */
    var $content_scripts;
    /**
     * @var string
     */
    var $description;
    /**
     * @var mixed
     */
    var $icons;
    /**
     * @var number
     */
    var $manifest_version;
    /**
     * @var string
     */
    var $name;
    /**
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
     * 
     * @var ?FExManifestBrowserSpecific
     */
    var $browser_specific_settings;
}