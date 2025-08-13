// @author: C.A.D. BONDJE DOUE
// @file: app.js
// @date: 20250204 07:41:28
// @desc: init swagger application
// @ts-nocheck
'use strict';(function(){var url=window.location.search.match(/url=([^&]+)/);if(url&&url.length>1){url=decodeURIComponent(url[1]);}else{url=window.location.origin;}// inject options = 
let op=igk.getParentScript().getAttribute("data-options");if(op){op=JSON.parse(op);}var options=op||{"swaggerDoc":{"openapi":"3.0.0","info":{"title":"\\igk\\docs\\swagger::class - module","description":"Documentation for the Project API","version":"1.0.0"},"basePath":"/api","servers":[{"url":"http://localhost/api","description":"Balafon Server #1"}],"paths":{"/user":{"get":{"summary":"","description":"","deprecated":false,"responses":{"200":{"description":"OK"},"401":{"description":"(Unauthorized) Invalid or missing Access Token"}},"security":[{"bearerAuth":[]}],"tags":["user"]}}},"tags":[],"components":{"schemas":{"User":{"type":"object","required":["id","name","email"],"properties":{"id":{"type":"bigint","example":2642927828526573600},"name":{"type":"string","example":"string"},"email":{"type":"string","example":"string"},"email_verified_at":{"type":"datetime","example":"2023-01-03 16:05:08"},"created_at":{"type":"datetime","example":"2023-01-03 16:05:08"},"updated_at":{"type":"datetime","example":"2023-01-03 16:05:08"}}}},"securitySchemes":{"bearerAuth":{"type":"http","scheme":"bearer","bearerFormat":"JWT"}}}},"customOptions":{"authAction":{"JWT":{"name":"JWT","schema":{"type":"apiKey","in":"header","name":"Authorization","description":"Bearer token"},"value":"Bearer YOUR_BEARER_TOKEN_HERE"}}}};const _authByName=n=>{let c=ui.authSelectors.definitionsToAuthorize().toJS();for(let i=0;i<c.length;i++){let t=c[i];if(n in t){return t[n];}}};url=options.swaggerUrl||url;const{customOptions}=options;var urls=options.swaggerUrls;var spec1=options.swaggerDoc;var swaggerOptions={spec:spec1,url:url,urls:urls,dom_id:'#swagger-ui',// deepLinking: true,
presets:[SwaggerUIBundle.presets.apis,SwaggerUIStandalonePreset],plugins:[SwaggerUIBundle.plugins.DownloadUrl],layout:"StandaloneLayout",onComplete(){// console.log('complete loading completedd...')
document.addEventListener('onLogout',function(){console.log("try logout ");fetch("/logout").then(()=>{console.log('exit');});});}// responseInterceptor(response){
//     console.log('intercepting....', response)
// }
};// + | update swage option with custom service 
for(let attrname in customOptions){swaggerOptions[attrname]=customOptions[attrname];}const ui=SwaggerUIBundle(swaggerOptions);// + | register auth service  
if(customOptions.oauth){ui.initOAuth(customOptions.oauth);}if(customOptions.authAction){// + |  
let{OAuth2Auth}=customOptions.authAction;// let m = (n)=>({l=})=>{
//     if (l = customOptions.authAction[n];
// };
// m('OAuth2Auth')
if(OAuth2Auth){const{token}=OAuth2Auth;if(token.token_type=='token'){const auth=_authByName('OAuth2Auth');ui.getSystem().authActions.authorizeOauth2WithPersistOption({auth:{name:'OAuth2Auth',...auth},token:{access_token:token.access_token}});}}// const { token } = OAuth2Auth;
//         ui.authActions.authorizeAccessCodeWithFormParams({
//             auth: { name:OAuth2Auth , code: token.access_token },
//         });
// console.log('done');
// ui.authActions.authorize({'auth':'OAuth2Auth', 'value': token.access_token })
}if(customOptions.preauthorizeApi){// + | inject bearer auth - key
const{name,key}=customOptions.preauthorizeApi;if(name&&key)ui.preauthorizeApiKey(name,key);}//else{
// const auth = _authByName('OAuth2Auth');
// const bauth = {name: 'OAuth2Auth', ...auth};
// console.log("auth :",  bauth);
// const l = 
// ui.getSystem().authActions.authorizeOauth2WithPersistOption({
//     auth: {
//         ...bauth 
//     }, 
//     token: {
//         access_token: 'f5262c---e4242f4eb82f08b93f455907f90a36a71ac0b6a755185ff120fdecfdbf'
//     }  
// });  
// console.log('binding ', {l});
//}
window.swagger=ui;window.addEventListener('message',function(e){if(e.origin!==window.location.origin)return;const accessToken=e.data.access_token;const type=e.data.type;const oauth2=window.swaggerUIRedirectOauth2;const state=e.data.state;const openerState=oauth2.state;const oauth2RedirectUrl='';if(state!=openerState){throw new Error('opener state failed. CSRF Attack');}const ac=ui.getSystem().authActions;// just authorize 
if(type=='authorization_code'){const _code=e.data.access_code;ac.authorizeAccessCodeWithFormParams({auth:{...oauth2.auth,code:_code},redirectUrl:oauth2RedirectUrl});}else{ac.authorizeOauth2WithPersistOption({auth:{...oauth2.auth},token:{access_token:accessToken}});}});})();