var undead = {};

/**************************************************
 * Undead settings                                *
 **************************************************/

undead.settings = {};

undead.settings.debug = false;
undead.settings.baseUrl = '';

/**************************************************
 * UI functions                                   *
 **************************************************/

undead.ui = {};

// reports an error 
undead.ui.error = function (mesg) {
   window.alert(mesg);
};

// warning message
undead.ui.warn = function (mesg) {
   window.alert(mesg);
};

// you just wanted to say hi
undead.ui.message = function (mesg) {
   window.alert(mesg);
};

// add a message to the console
undead.ui.addMessage = function (title, mesg) {
   var html = "<div class=\"console-title\">" + title + "</div>" + mesg;
   undead.ui.consoleAdd(html);
};

// add an error to the console
undead.ui.addError = function (level, mesg) {
   var levels, title, html;
   levels = {0 : "Javascript Error",
                 2 : "PHP Warning",
                 8 : "PHP Notice",
                 512 : "PHP User Warning",
                 1024 : "PHP User Notice",
                 2048 : "PHP Strict"};
   title = levels[level];
   html = "<div class=\"console-error\">" + title + "</div>" + mesg;
   // fix this ugly hack that doesn't work properly
   undead.oldConsoleColor = $("a[href^='/console']").css("color");
   $("a[href^='/console']").css({"color" : "red", "font-weight" : "bold"}).click(function () {
      $(this).css({"color" : undead.oldConsoleColor, "font-weight" : "normal"});
   });
   undead.ui.consoleAdd(html);
};

// add html to the console
undead.ui.consoleAdd = function (html) {
   html = "<div class=\"console-mesg\">" +
          "<div class=\"console-mesg-close\">X</div>" + html + "</div>";
   $("#console-messages").append(html);
   $(".console-mesg:first").css({"border-top-width" : "1px"});
   $(".console-mesg:first").css({"padding" : "0px 2px"});
};

// check for incomplete required fields
undead.ui.verifyForm = function (form) {
   var formDone = true;
   form.find("input.required, textarea.required, select.required").each(function () {
      if ($(this).val() === "") {
         formDone = false;
         $(this).css({"background" : "#fdd"});
      } else {
         $(this).css({"background" : "#fff"});
      }
   });
   return formDone;
};

undead.tinymceOptions = {
   script_url : undead.settings.baseUrl + '/js/tiny_mce/tiny_mce.js',
   theme : "simple"
};

undead.ui.wysiwyg = function (textarea) {
   undead.util.loadScript('/js/tiny_mce/jquery.tinymce.js');
   $(textarea).tinymce(undead.tinymceOptions);
};

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

/**************************************************
 * Util functions                                *
 **************************************************/

undead.util = {};

undead.util.scripts = [];
undead.util.stylesheets = [];

undead.util.loadScript = function (url) {
   if (typeof undead.util.scripts[url] === "undefined") {
      $.ajax({"url" : undead.settings.baseUrl + url,
              "dataType" : "script",
              "async" : false});
      undead.util.scripts[url] = "loaded";
   }
};

undead.util.loadCSS = function (url) {
   if (typeof undead.util.stylesheets[url] === "undefined") {
      $("head").append("<link>");
      $("head").children(":last").attr({
         rel : "stylesheet",
         type : "text/css",
         href : undead.settings.baseUrl + url
      });
      undead.util.stylesheets[url] = "loaded";
   }
};

/**************************************************
 * Crypt functions                                *
 **************************************************/

undead.crypt = {};

undead.crypt.hash = function (m) {
   undead.util.loadScript("/js/sha256.min.js");
   return Sha256.hash(m);
};

/**************************************************
 * Stack functions                                *
 **************************************************/

undead.stack = {};

undead.stack.defaultApp = "welcome";
undead.stack.defaultAction = "index";
undead.stack.ignoreHash = false;

// load the default application
undead.stack.loadDefault = function () {
   var re = window.location.hash.match(/([a-z_]+)\/?([a-z_]+)?/);
   if (re === null) {
      re = window.location.pathname.match(/([a-z_]+)\/?([a-z_]+)?/);
      if (typeof window.history.replaceState === "function") {
         window.history.replaceState({}, "", "/");
      }
   }
   if (re !== null) {
      if (typeof re[1] !== "undefined") {
         undead.stack.defaultApp = re[1];
      }
      if (typeof re[2] !== "undefined") {
         undead.stack.defaultAction = re[2];
      }
   }
   if (undead.stack.size(undead.stack.defaultApp) === 0 || 
       undead.stack.topAction(undead.stack.defaultApp) !== undead.stack.defaultAction) {
      undead.stack.push(undead.stack.defaultApp, undead.stack.defaultAction);
   } else {
      undead.stack.focus(undead.stack.defaultApp);
   }
};

// focus on a stack
undead.stack.focus = function (appStack) {
   var newHash;
   $(".app-stack:visible").hide().attr("active",null);
   $("#" + appStack + "-stack").show().attr("active","true");
   $("#" + appStack + "-stack").find(".app-content").hide();
   $("#" + appStack + "-stack").find(".app-content").last().show();
   $(".item").removeClass("active");
   $(".item[href^='/" + appStack + "']").addClass("active");
   newHash = "/" + appStack + "/" + undead.stack.topAction(appStack);
   if (newHash !== window.location.hash) {
      window.location.hash = newHash;
   }
};

// get the name of the active stack
undead.stack.activeName = function () {
   return $(".app-stack[active=true]").attr("app");
};

// pop the active stack
undead.stack.popActive = function () {
   undead.stack.pop(undead.stack.activeName());
};

// generic pop event
$(".pop-active").live('click', function (e) {
   e.preventDefault();
   undead.stack.popActive();
});

// get the size of a stack
undead.stack.size = function (appStack) {
   return $("#" + appStack + "-stack").find(".app-content").length;
};

// get the top (most recent) action of a stack
undead.stack.topAction = function (appStack) {
   var action = $("#" + appStack + "-stack").find(".app-content").last().attr("action");
   return action;
};

// handle the changing of the hash
undead.stack.handleHashChange = function (hash) {
   var app, action, re;
   if (undead.stack.ignoreHash === false) {
      re = hash.match(/([a-z_]+)\/([a-z_]+)/);
      if (typeof re !== "undefined") {
         app = re[1];
         action = re[2];
         if (undead.stack.size(app) > 1 && undead.stack.topAction(app) !== action) {
            undead.stack.pop(app);
         } else if (undead.stack.size(app) > 0) {
            undead.stack.focus(app);
         }
      } else {
         if (undead.stack.activeName() !== undead.stack.defaultApp) {
            undead.stack.focus(undead.stack.defaultApp);
         } else if (undead.stack.size(undead.stack.defaultApp) > 1) {
            undead.stack.pop(undead.stack.defaultApp);
         }
      }
   } else {
      undead.stack.ignoreHash = false;
   }
};

$(window).bind('hashchange', function () {
   undead.stack.handleHashChange(window.location.hash);
});

// refresh the top of a stack
undead.stack.refresh = function (appStack) {
   var topFrame, data;
   topFrame = $("#" + appStack + "-stack").find(".app-content").last();
   data = $.parseJSON(window.unescape(topFrame.attr("json")));
   $.ajax({"data" : data,
           "dataType" : "html",
           success : function (data) {
               topFrame.html(data);
               undead.stack.focus(appStack);
               undead.ui.addMessage("App Refreshed", "The app <i>" + 
                                 appStack + "." + topFrame.attr("action") + 
                                 "</i> was successfully refreshed");
           }
   });
};

// delete everything from a stack
undead.stack.empty = function (appStack) {
   $("#" + appStack + "-stack").find(".app-content").remove();
};

// pop a stack
undead.stack.pop = function (appStack) {
   $("#" + appStack + "-stack").find(".app-content").last().remove();
   undead.stack.focus(appStack);
};

// push onto a stack
undead.stack.push = function (appStack, appAction, data) {
   var stackDiv, jsonStr;
   if (typeof appAction === "undefined") {
      appAction = "index";
   }
   if (typeof data === "undefined" || typeof data !== "object") {
      data = {"app" : appStack,
              "action" : appAction};
   } else {
      data.app = appStack;
      data.action = appAction;
   }
   stackDiv = $("#" + appStack + "-stack");
   if (stackDiv.length === 0) {
      stackDiv = $('<div app="' + appStack + '" class="app-stack" id="' + appStack + '-stack"></div>');
      $("#content").append(stackDiv);
   }
   jsonStr = window.escape(JSON.stringify(data));
   $.ajax({"data" : data,
           "dataType" : "html",
           "success" : function callback (data) {
               var div;
               undead.stack.ignoreHash = true;
               window.location.hash = "/" + appStack + "/" + appAction;
               div = '<div class="app-content" json="' + jsonStr + '" action="' + appAction + '">' + data + '</div>';
               stackDiv.append(div);
               stackDiv.show();
               undead.stack.focus(appStack);
               undead.ui.addMessage("App Loaded", "The app <i>" + 
                                 appStack + "." + appAction + 
                                 "</i> was successfully loaded");
           }
   });
};

/**************************************************
 * Init functions                                 *
 **************************************************/

undead.init = {};

undead.init.init = function () {
   if (typeof JSON === "undefined") {
      undead.util.loadScript("/js/json2.js");
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
         undead.ui.warn(xhr.getAllResponseHeaders());
      },
      "dataFilter" : function (rawData, type) {
         var data, mesg, i;
         if (undead.settings.debug) {
            undead.ui.warn('raw data:' + rawData);
         }
         if (type === "json") {
            try {
               data = $.parseJSON(rawData);
               if (data.status === "logged out") {
                  undead.stack.push("login");
               } else if (data.status === "reload") {
                  window.location.reload();
               }
               if (typeof data.query !== "undefined") {
                  undead.ui.warn(data.query);
               }
               if (typeof data.errors === "object") {
                  for (i = 0; i < data.errors.length; i += 1) {
                     mesg = "<i>" + data.errors[i].errstr + "</i> in " +
                            data.errors[i].errfile + " on line " +
                            data.errors[i].errline + ".";
                     undead.ui.addError(data.errors[i].errno, mesg);
                  }
               }
               if (typeof data.alert === "object") {
                  for (i = 0; i < data.alert.length; i += 1) {
                     undead.ui.warn(data.alert[i]);
                  }
               } else if (typeof data.alert !== "undefined") {
                  undead.ui.warn(data.alert);
               }
            } catch (e) {
               undead.ui.warn('error parsing json:' + rawData);
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
         undead.ui.warn("ajax data:" + options.data);
      }
   });
};
