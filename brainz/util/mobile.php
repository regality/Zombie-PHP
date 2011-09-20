<?php

function isMobile($user_agent, $default_to_mobile = true) {
   // Common mobile browsers
   if (preg_match("/iphone|ipod|blackberry|android|palm|windows ce|fennec/i", $user_agent) === 1)
      return true;

   // Desktop and bots
   else if (preg_match("/windows|linux|os x|solaris|bsd|spider|crawl|slurp|bot/i", $user_agent) === 1)
      return false;

   // Assume it's an uncommon mobile browser
   // unless $default_to_mobile is false
   else
      return $default_to_mobile;
}

?>
