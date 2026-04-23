<?php

if (true) {
    /**
     * auto generate doc.
     */
    function conditional()
    {
        echo 'init conditional';
    }
    echo "after call";
} else {
    echo $j + 1;
    /**
     * auto generate doc.
     */
    function jump()
    {
        return 23;
    }
    /**
     * auto generate doc.
     */
    function conditional()
    {
        return 23;
    }
}
echo 'finish';