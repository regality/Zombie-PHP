var undead = undead || {};

/**************************************************
 * Undead settings                                *
 **************************************************/

undead.settings = undead.settings || {};

undead.settings.mode = undead.settings.mode || 'dev';
undead.settings.debug = undead.settings.debug || false;
undead.settings.baseUrl = undead.settings.baseUrl || '';

/**************************************************
 * Util functions                                *
 **************************************************/

undead.util = undead.util || {};

undead.util.scripts = undead.util.scripts || {};
undead.util.stylesheets = undead.util.stylesheets || {};

undead.util.require = function (js, callback) {
   var sp = js.split('/', 2);
   var module = sp[0];
   var file = sp[1];
   if (undead.settings.mode == 'dev') {
      url = '/js/' + module + '/' + file + '.js';
   } else if (undead.settings.mode == 'prod') {
      url = '/build/' + undead.settings.version + '/js/' + 
            module + '/' + file + '.js';
   }
   undead.util.importJs(url, callback);
};

undead.util.importJs = function (url, callback) {
   if (typeof undead.util.scripts[url] === "undefined") {
      var ajaxData = {"url" : undead.settings.baseUrl + url,
                      "dataType" : "script",
                      "cache" : true};
      if (typeof callback == "function") {
         ajaxData.success = callback;
      } else {
         ajaxData.async = false;
      }
      undead.util.scripts[url] = "loaded";
      $.ajax(ajaxData);
   } else if (typeof callback == "function") {
      callback();
   }
};

undead.util.loadCSS = function (url) {
   if (typeof undead.util.stylesheets[url] === "undefined") {
      var link = $("<link />");
      link.attr({
         rel : "stylesheet",
         type : "text/css",
         href : undead.settings.baseUrl + url
      });
      $("head").append(link);
      undead.util.stylesheets[url] = "loaded";
   }
};

/**************************************************
 * Init functions                                 *
 **************************************************/

undead.init = {};

undead.init.init = function () {
   undead.util.require('undead/ui');
   undead.util.require('undead/crypt');
   undead.util.require('undead/stack');
   undead.util.require('undead/token');
   if (typeof JSON === "undefined") {
      undead.util.require("undead/json2");
   }
   undead.init.setupAjax();
};

// setup ajax for the undead
undead.init.setupAjax = function () {
   $.ajaxSetup({
      "dataType" : "json",
      "url" : undead.settings.baseUrl + "/app.php",
      "cache" : "false",
      "error" : function (xhr, status, error) {
         undead.ui.warn('An error occured:' + error + status);
      },
      "dataFilter" : function (rawData, type) {
         var data, mesg, i;
         if (undead.settings.debug) {
            undead.ui.message('raw data:' + rawData);
         }
         if (type === "json") {
            try {
               data = $.parseJSON(rawData);
               if (data.status === "logged out") {
                  undead.stack.push("login");
               } else if (data.status === "reload") {
                  window.location.assign(undead.settings.baseUrl);
               }
               if (typeof data.query !== "undefined") {
                  undead.ui.message(data.query);
               }
               if (typeof data.php_errors === "object") {
                  for (i = 0; i < data.php_errors.length; i += 1) {
                     mesg = data.php_errors[i].errstr + " in " +
                            data.php_errors[i].errfile + " on line " +
                            data.php_errors[i].errline + ".";
                     undead.ui.error(mesg, data.php_errors[i].errno);
                  }
               }
               if (typeof data.errors === "object") {
                  for (i = 0; i < data.errors.length; i += 1) {
                     undead.ui.error(data.errors[i]);
                  }
               }
               if (typeof data.messages === "object") {
                  for (i = 0; i < data.messages.length; i += 1) {
                     undead.ui.message(data.messages[i]);
                  }
               }
               if (typeof data.warnings === "object") {
                  for (i = 0; i < data.warnings.length; i += 1) {
                     undead.ui.warn(data.warnings[i]);
                  }
               }
            } catch (e) {
               // this needs to only catch json exceptions
               undead.ui.error('error parsing json:' + rawData);
            }
         } else {
            if (rawData === "logged out") {
               undead.stack.push("login");
            }
         }
         return rawData;
      },
      "timeout" : 10000,
      "type" : "get"
   });

   $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
      var csrf;
      if (typeof options.data !== "undefined" &&
          options.data.search("app=csrf") === -1 &&
          options.data.search("csrf=") === -1) {
         csrf = "csrf=" + undead.token.get();
         if (options.data.length > 0) {
            csrf = "&" + csrf;
         }
         options.data += csrf;
      }
      if (typeof options.data !== "undefined") {
         options.data += "&format=" + options.dataType;
      } else {
         options.data = "format=" + options.dataType;
      }
      if (undead.settings.debug) {
         undead.ui.message("ajax data:" + options.data);
      }
   });
};
