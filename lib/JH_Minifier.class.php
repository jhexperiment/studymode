<?
require_once dirname(__FILE__) . '/lessc.inc.php';
/**
 * class JH_Minifier
 * 
 * Class to compress a list of asset files into a single file.
 * 
 */
class JH_Minifier {
  /**
   * fnStylesheets()
   * 
   * Function to compress and combine a list of stylesheet files into a single file.
   * 
   * @param $aOptions Array Array of options.
   * @param $aOptions['aFileList'] Array Array of files.
   * @param $aOptions['sOutFile'] String Path of file to output results.
   * 
   * @return boolean Result from puting results in output file.
   */
  public static function fnStylesheets($aOptions) {
    $sRootDir = dirname(__FILE__) . '/../';
    $sCssOutput = '';
    $sNeedle = '.less';
    
    // Less compiler
    $oLess = new lessc;
    $oLess->setFormatter("compressed");
    
    // process file list
    foreach ($aOptions['aFileList'] as $sFile) {
      if ((0 === strpos($sFile, 'http')) || (0 === strpos($sFile, '//'))) {
        // outside file
        $sCssOutput .= file_get_contents($sFile) . "\n";
      } 
      else {
        // local file
        if (substr($sFile, -strlen($sNeedle)) === $sNeedle) {
          // is less file
          $sCssOutput .= $oLess->compileFile("{$sRootDir}/web/{$sFile}") . "\n";
        }
        else {
          // is css file
          $sCssOutput .= file_get_contents("{$sRootDir}/web/{$sFile}") . "\n";
        }
      }
    }
    return file_put_contents($aOptions['sOutFile'], $sCssOutput);
  }
  
  
  /**
   * fnJavascripts()
   * 
   * Function to compress and combine a list of javascript files into a single file.
   * 
   * @param $aOptions Array Array of options.
   * @param $aOptions['aFileList'] Array Array of files.
   * @param $aOptions['sOutFile'] String Path of file to output results.
   * 
   * @return boolean Result from puting results in output file.
   */
  public static function fnJavascripts($aOptions) {
    $sRootDir = dirname(__FILE__) . '/../';
    // Google closure for PHP  
    $oClosure = new PhpClosure();
    
    // process file list
    foreach ($aOptions['aFileList'] as $sFile) {
      if ((0 === strpos($sFile, 'http')) || (0 === strpos($sFile, '//'))) {
        // outside file
      }
      else {
        // local file
        $sFile = "{$sRootDir}/web/{$sFile}";
      }
      
      // add file to google closure to be compiled
      $oClosure->add($sFile);
    }
    
    // compile files and write to output file
    $oClosure
      ->simpleMode()
      //->advancedMode()
      ->quiet()
      ->useClosureLibrary()
      //->cacheDir(dirname(__FILE__) . "/../../cache/closure")
      ->write($aOptions['sOutFile']);  
  } 
}





