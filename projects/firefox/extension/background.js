// background reference 
console.log("Background script loaded!");
const _domains = ['.napoleonsports.be'];
// Function to get cookies
async function getHttpOnlyCookies() {
    let cookies = await browser.cookies.getAll({ domain: _domains[0] }).then(o=>{o});
    return cookies;
}
// Get cookies on extension startup
chrome.runtime.onInstalled.addListener(() => {
    // getHttpOnlyCookies(); 
});
// Listen for messages (optional trigger from main.js)
chrome.runtime.onMessage.addListener((message, sender, sendResponse) => {
    // console.log("receive message ", message.action);
    if (message.action === "requestCookies") {
        browser.cookies.getAll({ domain: _domains[0] }).then(s=>{
            sendResponse({cookies:s, action:'sendCookies'});
        });
    }
    return true;
});
browser.runtime.onInstalled.addListener(() => {
    console.log("Extension Installed!!!!"); 
});