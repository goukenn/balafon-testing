"use strict";
// command: node .test/sitenotebook/site_favicon.js
const { default: axios } = require('axios');
const cli = require('cli-color')
const https = require('https')
const fs = require('fs')
// + | utility function 
function _log(){
    console.log.apply(null, arguments)
}
let data = fs.readFileSync('/Volumes/Data/Dev/JsonDatas/sites.json', 'utf-8');
let ci = JSON.parse(data);
const { sites } = ci;
const prom = [];
let v_count = 0;
let v_error = 0;
sites.sort((a, b) =>{
    return a.site.localeCompare(b.site);
}).forEach((i)=>{
    prom.push(
        axios({
            url:i.site+"/favicon.ico",
            httpsAgent: new https.Agent({
                rejectUnauthorized :false
            })
        }).then((o)=>{
            //_log(cli.green("resolve ") +i.site)
            v_count++;
        }).catch((e)=>{
            console.log(cli.red("error : ")+i.site);
            v_error++;
        })
    );
});
Promise.all(prom).then(function(){
    _log("finish loading.... .",{ loaded:  v_count, error: v_error})
})
// in node use 'return' to stop script execution 
// @ts-ignore
return;
/*
// const agent = new Agent({ca: ISRGCAs});
_log("-------------------------------")
_log(cli.red("sampling") + " argument")
_log("-------------------------------")
let list = [
   // "https://google.com",
   "https://local.com:7300"
];
let v_count = 0;
for(let i = 0; i < list.length; i++){
    let uri = list[i];
    prom.push(
        axios({
            url:uri+"/favicon.ico",
            httpsAgent: new https.Agent({
                rejectUnauthorized :false
            })
        }).then((o)=>{
            _log("resolve " +uri)
            v_count++;
        })
    );
}
_log('wait to finish');
Promise.all(prom); 
**/
_log(cli.green('done'))