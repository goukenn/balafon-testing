import * as Vue from 'vue';
import {renderToString} from 'vue/server-renderer';
const { createSSRApp } = Vue;
const app = createSSRApp({
render(){const{h}=Vue;return h('div',{class:'a',class:['primal', 'a'],innerHTML:'ok'})}
});
const ctx = {};
const html = await renderToString(app, ctx);
console.log('html:', html);