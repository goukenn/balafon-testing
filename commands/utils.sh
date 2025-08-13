#!/bin/sh

balafon --request:view TtreController api/login -srv_request:'{"login":"cbondje@igkdev.com","password":"admin@123"}' --method:POST --content-type:application/json