<?php

function strip_css_comments($css) {
   $css = preg_replace('/\/\*(.|\n)*?\*\//', '', $css);
   return $css . "\n";
}

function substitue_css_includes($css) {
   $matches = array();
   preg_match_all('/@include ([a-z0-9\/]+).css;(\s+)?/', $css, $matches);
   for ($i = 0; $i < count($matches[0]); ++$i) {
      $sp = explode('/', $matches[1][$i]);
      $include_file = file_get_contents(__DIR__ . "/../../apps/" . $sp[0] . "/views/css/" . $sp[1] . ".css");
      $css = str_replace($matches[0][$i], $include_file . "\n", $css);
   }
   return $css;
}

function substitue_css_variables($css) {
   $matches = array();
   preg_match_all('/@variable ([0-9a-z-]+) (.*);(\s+)?/', $css, $matches);
   for ($i = 0; $i < count($matches[0]); ++$i) {
      $css = str_replace($matches[0][$i], '', $css);
      $css = str_replace('$' . $matches[1][$i], $matches[2][$i], $css);
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

function compile_css($css) {
   $css = strip_css_comments($css);
   $css = substitue_css_variables($css);
   $css = minify_css($css);
   return $css;
}

?>
