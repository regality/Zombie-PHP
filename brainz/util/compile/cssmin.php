<?php

require_once(__DIR__ . "/../../../config/config.php");

function strip_css_comments($css) {
   $css = preg_replace('/\/\*(.|\n)*?\*\//', '', $css);
   return $css . "\n";
}

function substitue_css_includes($css) {
   $matches = array();
   while (preg_match_all('/@include ([a-z0-9\/]+).css;(\s+)?/', $css, $matches) > 0) {
      for ($i = 0; $i < count($matches[0]); ++$i) {
         $sp = explode('/', $matches[1][$i]);
         $include_file_name = __DIR__ . "/../../../apps/" . $sp[0] . "/views/css/" . $sp[1] . ".css";
         $include_file = file_get_contents($include_file_name);
         $include_file = strip_css_comments($include_file);
         $css = str_replace($matches[0][$i], $include_file . "\n", $css);
      }
   }
   return $css;
}

function substitue_css_variables($css, $version = false) {
   $matches = array();
   preg_match_all('/@variables {([^}]+)(?:\s+)?}(?:\s+)?/', $css, $matches);
   $vars = array();
   for ($i = 0; $i < count($matches[0]); ++$i) {
      $var_list = explode(';', $matches[1][$i]);
      foreach ($var_list as $var) {
         $kv = explode(':', $var, 2);
         if (strlen(trim($kv[0])) && strlen(trim($kv[1]))) {
            $vars[trim($kv[0])]= trim($kv[1]);
         }
      }
      $css = str_replace($matches[0][$i], '', $css);
   }
   foreach ($vars as $key => $value) {
      $css = str_replace('var(' . $key . ')', $value, $css);
   }
   if ($version !== false) {
      $build = "/build/$version";
      preg_match_all("/url\(['\"]?(\/images\/[a-z0-9_]+\/[a-z_-]+\.[a-z]+)['\"]?\)/i", $css, $matches);
      for ($i = 0; $i < count($matches[0]); ++$i) {
         $new_url = "url('" . $build . $matches[1][$i] . "')";
         $css = str_replace($matches[0][$i], $new_url, $css);
      }
   }
   return $css;
}

function minify_css($css) {
   $css = preg_replace('/\n{2,}/', "\n", $css);
   $css = preg_replace('/\s{2,}/', ' ', $css);
   $css = preg_replace('/\s?:\s?/', ":", $css);
   $css = preg_replace('/\s?,\s?/', ", ", $css);
   $css = preg_replace('/\s?;\s?/', ";", $css);
   $css = preg_replace('/\s?\{\s?/', "{", $css);
   $css = preg_replace('/\s?\}\s?/', "}", $css);
   $css = preg_replace('/:([a-z]+){/', ":$1 {", $css);
   return $css . "\n";
}

function compile_css_file($css, $version = false) {
   $css = strip_css_comments($css);
   $css = substitue_css_includes($css);
   $css = substitue_css_variables($css, $version);
   $css = minify_css($css);
   return $css;
}

?>
