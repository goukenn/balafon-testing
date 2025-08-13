<?php
// balafon --run .test/db/clear_cart.php
use com\igkdev\bantubeat\Models\Carts;
bantubeatController::ctrl()->register_autoload();
bantubeatController::ctrl();
igk_require_module(igk\ecommerce::class);
$user = igk_get_user(1);
Carts::clearShoppingCart($user);
igk_wln_e("clear cart ");