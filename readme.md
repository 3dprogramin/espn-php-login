ESPN login
----

I tried few things and here's what I got to.

This is the request that's being done to login / send username and password, alogin with an `apikey` value

![login request 1](http://i.imgur.com/fSZ1iU1.png)

The response is a JSON, which looks clearer like this:

![json response](http://i.imgur.com/fERuS0r.png)

The response contains a `token` object, which contains an `access_token`, `refresh_token` and some other interesting values and cookies.
I'm pretty sure those are used later on to do requests, most likely also getting the content from the page you want to get it from.

From this, I can positively say that the site is mostlly pure JS. When this is the case, it's pretty hard to automate stuff, because they obfuscate things and you usually need a browser to interpret the JS right.

If you make a single GET request to the login URL (when you go to login page, before you actually login), this is what's returned:

![login request GET](http://i.imgur.com/P3RxVGW.png)

Basically, site responds with very few text/bytes back, and it's pretty easy to even understand what they respond. The `brain` behind it is inside this script though: `https://cdn.registerdisney.go.com/v2/outer/DisneyID.js`

Looks like they're using a 3rd party library or framework to handle everything in their site, including the API key.

Talking about the API key because that's a value that's sent along with the username and password with the login. From my tests, if you reuse the same API key over and over, it still works, but not sure if it does for the next hour, next day or forever. So ye, it's probably an important piece of the puzzle at some point. Although, if this works for 1 week let's say, you can have an external bot that logs into browser, and get the APIKEY from there and forward it to a DB for the requests bot / server to use it.

Here's how the 3rd party JS file looks like:
![3rd party lib](http://i.imgur.com/tLD0fEB.png)

Shows that the APIKEY is created from that file, but it's minified and obfuscated, to make it harder to read.

That's what I was able to find out.

Would be interesting to see what has been done until now or before to do the login.
