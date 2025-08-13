(function () {
    document.body.style.border = "5px solid red";
    // chrome.runtime.onMessage.addListener((message, sender, sendResponse) => {
    //     console.log('core ..... recieve message ::: bg message ', message);
    //     if (message.action === "sendCookies") {
    //         console.log("Received Cookies:", message.cookies);
    //         // send response back
    //         sendResponse({capture:true});
    //     }
    //     return true; 
    // });
    // Optionally request cookies from background script
    // chrome.runtime.sendMessage({ action: "requestCookies" }); 
    // sessionStorage.setItem('igkdev-cookie', 'ff-extension'); 
    // console.log("raise background session "); 
    // only  accessed in background 
    // console.log('cookies', { cook : cookie.get()});
    // fetch('https://local.com:7300/bet/retrieve', {
    //    credentials: 'include'
    // }).then(o=>{
    //     console.log('retrieve = ', )
    //     return o.text();
    // })
    // .then(data=>{
    //     console.log(data);
    // })
    // .catch(o=>{
    //     console.log("error");
    // })
    // console.log("done", { chrome, browser });
    function nap_coookies(cookies) {
        const o = {};
        cookies.forEach(i => {
            if (/\b(aws-waf-token|ct-prod-bcknd|f3k2kqs7xc)\b/.test(i.name)) {
                o[i.name] = i.value;
            }
        });
        return o;
    };
    function nap_str_session(l){
        let s = [];
        for(let i in l){
            s.push( i+'='+l[i]);
        }
        return s.join(';');
    }
    function fn_request() {
    //    console.log("send message to background. ")
        try {
            chrome.runtime.sendMessage({ action: "requestCookies" }, function (response) {
                // console.log('finish send requestCookies complete .... ', response);
                const { action , data } = response;
                if (action == 'sendCookies'){
                    let l = nap_coookies(response.cookies);
                    console.log(btoa(nap_str_session(l)));
                    console.log(JSON.stringify(l, null, 4));
                    fetch('https://local.com:7300/bet/store_session', {
                        method:'POST', 
                        body:btoa(nap_str_session(l)),
                        headers:[
                            ['Content-Type', 'text/plain']
                        ]
                    }).then(o=>o.text()).then(o=>{
                        console.log(o)
                    });
                }
            });
        }
        catch (e) {
            console.log("error ::: ", e);
        }
    };
function loadApp(){
    console.log('load app', document.URL);
    const bd = document.body;
    const q = document.createElement('div');
    bd.insertBefore(q, bd.firstChild);
    let fc = null;
    if (/napoleonsports\.be/.test(document.URL)){
        q.style = 'background-color: yellow; height:32px; top:0; width:100%; z-index:1000; position:fixed;';
        //bd.style += 'padding-top:32px;overflow-y: scroll;';
        fc = fn_request;
    }
    if (fc){
        q.addEventListener('click', fc);
    }
};
if (document.readyState=='complete'){
    loadApp();
}else{
    document.addEventListener('readystatechange',function(e){    
        if(document.readyState =='complete'){
            loadApp();
        }
    });
}
})();