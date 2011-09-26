/**************************************************
 * Token functions                                *
 **************************************************/

undead.token = {};

// the golden egg (aka the token)
undead.token.token = "";

// set the token
undead.token.set = function (token) {
   undead.token.token = token;
};

// get the token
undead.token.get = function () {
   if (undead.token.token === "") {
      undead.token.request();
   }
   return undead.token.token;
};

// request a new token from the server
undead.token.request = function () {
   $.ajax({"data" : {"app" : "csrf"},
           "async" : false,
           "success" : function (data) {
               undead.token.set(data.token);
           }
   });
};
