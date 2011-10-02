/**************************************************
 * Token functions                                *
 **************************************************/

zs.token = {};

// the golden egg (aka the token)
zs.token.token = "";

// set the token
zs.token.set = function (token) {
   zs.token.token = token;
};

// get the token
zs.token.get = function () {
   if (zs.token.token === "") {
      zs.token.request();
   }
   return zs.token.token;
};

// request a new token from the server
zs.token.request = function () {
   $.ajax({"data" : {"app" : "csrf"},
           "async" : false,
           "success" : function (data) {
               zs.token.set(data.token);
           }
   });
};
