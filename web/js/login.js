function setupLogin() {
   $("#desktop").hide();
   $("#username").focus();
}

$(function() {
   $("#login-btn input").click(function() {
      pass_hash = hex_sha1($("#password").val());
      username = $("#username").val();
      success = $.ajax({
         url:"/apps/auth",
         data:{password:pass_hash, username:username},
         async:false,
         dataType: "text"
      });
      if (success.responseText == "success") {
         $("#login-modal").fadeOut();
         $.ajax({
            url:"/apps/desktop",
            success: function(data) {
               $("#main-content").html(data).fadeIn();
            }
         });
      } else {
         //$("#login-error").html("Wrong username or password.<br />hint:use 'student' and 'student'");
         $("#login-error").html("Wrong username or password.<br />");
         $("#password").val("").focus();
      }
   });
});
