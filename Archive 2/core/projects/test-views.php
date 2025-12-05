<?php

// @author: C.A.D. BONDJE DOUE
// @filename: test-views.php
// @date: 20250717 12:29:32
// @desc: check controllers views 
// @command: balafon --run .test/core/projects/test-views.php --controller:
$views = $ctrl->getViews();

igk_wln_e('views', $views, $ctrl->getName(), $ctrl->getDeclaredDir());


