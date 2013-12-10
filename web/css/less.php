<?php
/**
 * File used to dynamically compile less files and serve to client.
 */

header('Content-Type: text/css'); header('X-Content-Type-Options: nosniff');

require_once dirname(__FILE__) . '/../../lib/lessc.inc.php';

// Less compiler
$oLess = new lessc;
$oLess->setFormatter('compressed');

// get file path parameter
$sCss = urldecode($_GET['c']);
if (empty($sCss)) {
  exit;
}
// compile less file into css
$sOutput = $oLess->compileFile(dirname(__FILE__) . "/{$sCss}");
// output result
echo $sOutput;
exit;