<?php


if (true){
    $t->div()->Content = $ctrl->getUser()->display().": Sample";
} else {
    $t->div()->Content = "User not found";
}