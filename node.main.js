import * as Vue from 'vue';
import {renderToString} from 'vue/server-renderer';
const { createRSSApp } = Vue;
const app = createRRSApp({
render(){const{h}=Vue;return h('div',{class:'a',class:[primal , 'a'],innerHTML:'ok'})}
});
const html = await renderToString(app, ctx);console.log(html);