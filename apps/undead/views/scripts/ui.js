/**************************************************
 * UI functions                                   *
 **************************************************/

undead.ui = {};

undead.ui.alertShowTime = 2000;

undead.ui.errorLevels = {
   100 : "Message",
   101 : "Warning",
   102 : "Error",
   2 : "PHP Warning",
   8 : "PHP Notice",
   512 : "PHP User Warning",
   1024 : "PHP User Notice",
   2048 : "PHP Strict"
};

// reports an error 
undead.ui.error = function (mesg, level) {
   var title;
   if (level != null) {
      title = undead.ui.errorLevels[level];
   } else {
      title = "error";
   }
   undead.ui.addAlert("error", title, mesg);
   undead.ui.logError(title, mesg);
};

// warning message
undead.ui.warn = function (mesg) {
   undead.ui.addAlert("warning", "warning", mesg);
   undead.ui.logError("Warning", mesg);
};

// you just wanted to say hi
undead.ui.message = function (mesg) {
   undead.ui.addAlert("message", "message", mesg);
   undead.ui.logMessage("message", mesg);
};

undead.ui.addAlert = function(type, title, mesg) {
   mesg = $("<div/>").text(mesg).html();
   mesg = mesg.replace(/\n/g,"<br />");
   var flash = $("<div style=\"display:none;\" class=\"" + type + "\"></div>");
   flash.html("<span class=\"title\">" + title + ":</span>" + mesg);
   $("#alerts").append(flash);
   flash.slideDown("slow", function() {
      $(this).animate({"left":1}, undead.ui.alertShowTime, function() {
         $(this).slideUp("slow");
      });
   });
   flash.click(function() {
      $(this).stop(true).css({"border-color":"black"}).unbind('click').click(function() {
         $(this).slideUp("slow");
      });
   });
}

// add a message to the console
undead.ui.logMessage = function (title, mesg) {
   var html = "<div class=\"console-title\">" + title + "</div>";
   undead.ui.consoleAdd(html, mesg);
};

// add an error to the console
undead.ui.logError = function (title, mesg) {
   var html;
   html = "<div class=\"console-error\">" + title + "</div>";
   // fix this ugly hack that doesn't work properly
   undead.ui.oldConsoleColor = $("a[href^='/console']").css("color");
   $("a[href^='/console']").css({"color" : "red", "font-weight" : "bold"}).click(function () {
      $(this).css({"color" : undead.oldConsoleColor, "font-weight" : "normal"});
   });
   undead.ui.consoleAdd(html, mesg);
};

// add html to the console
undead.ui.consoleAdd = function (html, text) {
   text = $("<div/>").text(text).html();
   text = text.replace(/\n/g,"<br />");
   var tr = $("<tr />");
   var td = $("<td />");
   td.html("<div class=\"console-mesg-close\">X</div>" + html + text);
   tr.append(td);
   $("#console-messages tr th").parent().after(tr);
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

undead.ui.tinymceOptions = {
   script_url : undead.settings.baseUrl + '/js/tiny_mce/tiny_mce.js',
   theme : "simple"
};

undead.ui.wysiwyg = function (textarea) {
   undead.util.importJs('/js/tiny_mce/jquery.tinymce.js');
   $(textarea).tinymce(undead.ui.tinymceOptions);
};
