#!/bin/bash
balafon --sync:project TtreController --name:console.ttre.be
balafon --sync:update-corelib --name:console.ttre.be
balafon --sync:module igk/js/Vue3 --name:console.ttre.be
balafon --sync:module igk/adminDashboard/corc --name:console.ttre.be
balafon --sync:module ionicons --name:console.ttre.be