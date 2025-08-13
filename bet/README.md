# connect to napoleon

- require internet connection 
- using navigator firefox
- required cookie session  
- be in Belgium - (VPN - to simulate living in belgium)




```sh
# invoke command
balafon --run .test/bet/napoleon.php 'xxxxxx'
```
where `xxxxxx`` is `ct-prod-bcknd` cookie session value


## process
- open firefox and navigate to https://napoleonsports.be
- connect with your login and password
- get the ct-prod-bcknd value from cookie session list 
- run the command

- now you can connect then - 



- for calculator need to pass credentials to connexion 


## firefox get cookie file location 

- hit : `about:profiles` in address bar.
- open `Root Directory` in finder/explorer to see the `cookies.sqlite`


### not the command will use session in priority 


## FRONT API - to caculate or retrieve ticket information 

- https://social-front-default-production.freetls.fastly.net/default/tickets/besuperbetsport


### get the front api token 
- required `session_id` in header 

-- example of session_id
Note: look like `ct-prod-bcknd` . change after each connexion request 
938d79d2-e63c-49c1-aaa4-ce2946a3323f|8200798


- https://social-front-default-production.freetls.fastly.net/default/authentication/besuperbetsport/public/generate?user_id=8200798&user_uuid=b80cef8f-5438-460c-a093-2f35c6b8d055

return {token: ''};
-- when recieve the token we can attack the soical guid user 

myuser-id = 8200798
myuser-uuid= b80cef8f-5438-460c-a093-2f35c6b8d055



-- ticket
--



## static resources 

https://napoleonsports.be/static/html/maintenance-fr-be.html
https://napoleonsports.be/static/html/maintenance-en-be.html


