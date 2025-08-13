#!/bin/bash


balafon --sync:update-corelib --name:console.ttre.be
balafon --sync:module igk/js/common --name:console.ttre.be
balafon --sync:module igk/js/Vue3 --name:console.ttre.be
balafon --sync:module igk/adminDashboard/corc --name:console.ttre.be
balafon --sync:module igk/ios/SfSymbols --name:console.ttre.be
balafon --sync:project TtreController --name:console.ttre.be
balafon --sync:clearcache --name:console.ttre.be
balafon --sync:clearsession --name:console.ttre.be

# reset database in dev
balafon --db:resetdb TtreController --force
balafon --db:initdb TtreController --force
# seed with dummy data
balafon --db:seed TtreController  


#for dev 
balafon --users:login TtreController cbondje@igkdev.com
balafon --users:bind-group --controller:TtreController cbondje@igkdev.com operator

# generate postmap api document

balafon --postman:build-export TtreController -srv_baseuri:'https://localhost:7300' ./.test/ttre/postman.export.json