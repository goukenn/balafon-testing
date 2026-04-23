<?php

symlink(realpath(__DIR__."/../../../../core/Packages"), __DIR__."/../application/Packages");
unlink(__FILE__);