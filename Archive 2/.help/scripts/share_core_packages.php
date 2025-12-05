<?php

// 1. with resources link - install to root dir
// ftp /sites/domains/src/public/

 
symlink(realpath(__DIR__."/../../../../core/Packages"), __DIR__."/../application/Packages");

unlink(__FILE__);