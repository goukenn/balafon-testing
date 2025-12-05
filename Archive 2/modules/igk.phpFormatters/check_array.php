<?php
// @command: balafon --run .test/module/igk.phpFormatter/check_array.php
$c = ["o"=>"one","t"=>"three","l"=>"last"];
echo $m = end($c);
reset($c);
echo $m = current($c); 

<<<MD
#array manipulation 
- end
- reset
- current
- prev
- next
MD;

exit;