$(function () {
   $("#console-clear").click(function () {
      $("#console-messages").find("td").each(function() {
         $(this).parent().remove();
      });
   });

   $(".console-mesg-close").live('click', function () {
      $(this).parent().remove();
   });

   undead.util.require("undead/defaultvalue");
   $("#console-run").defaultvalue("Console...", "#999999", "#000000");

   $("#console-run").keydown(function(e) {
      var ENTER = 13;
      var UP = 38;
      var DOWN = 40;
      if (e.keyCode == ENTER) {
         var ret = eval($(this).val());
         undead.ui.logMessage($(this).val(), String(ret));
         $(this).val("");
      }
   });
});
