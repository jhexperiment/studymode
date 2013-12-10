<?
/**
 * index.php
 * 
 * Combination of Controller and View template.
 * 
 * Notes:
 *   - Short tags look better in the template.
 *   - I like to keep HTML and PHP indented seperately.
 *   - I've included external libraries, I don' re-invent the wheel if I don't have to.
 */

// include external libraries
$sCurrentPath = dirname(__FILE__);
require_once("{$sCurrentPath}/../lib/Reader.class.php");
require_once("{$sCurrentPath}/../lib/JH_Minifier.class.php");
require_once("{$sCurrentPath}/../lib/php-closure.php");

$sColor = '#C00';
if (isset($_GET['color'])) {
  if (preg_match('/^[a-f0-9]{6}$/i', $_GET['color'])) {
    // is hex number
    $sColor = "#{$_GET['color']}";
  }
  else {
    // must be color string
    $sColor = $_GET['color'];
  }
}
 


$aData = array();
// attempt to read data
try {
  // default to dataset.csv
  $sDataFile = "{$sCurrentPath}/../data/dataset.csv";
  if (isset($_GET['datafile']) && ('new_data_file' == $_GET['datafile'])) {
    // user wants new_data_file, set file path and use other Reader class.
    $sDataFile = "{$sCurrentPath}/../data/new_data_file";
    $oReader = new JsonReader($sDataFile);
  }
  else {
    // user wants CSV file
    $oReader = new CSVReader($sDataFile);
  }
  // popuplate $aData with data from file.
  $oReader->fnGetData($aData);
  
}
catch (Exception $e) {
  // catch and ignore any thrown exceptions
}

/**
 * The following is use to compile the stylesheet and javascript files
 * into a single compressed stylesheet and a single javascript file. Done to
 * minimize the amount of connections required to be made from the client
 * to the server.
 * 
 * Uncommenting the following code will recompile the stylesheet and javascript
 * files and replace the existing studymode.min.css and studymode.min.js files.
 * 
 * /css/less.php - File used to dynamically compile less files and serve to client.
 */
 
/*
$aCssList = array(
  '/css/bootstrap.min.css',
  '/css/studymode.less'
  //'/css/less.php?c=studymode.less'
);

JH_Minifier::fnStylesheets(array(
  'aFileList' => $aCssList,
  'sOutFile' => "{$sCurrentPath}/css/min/studymode.min.css"
));

$aJsList = array(
  'http://code.jquery.com/jquery-1.4.2.js',
  '/js/chart.js'
);

JH_Minifier::fnJavascripts(array(
  'aFileList' => $aJsList,
  'sOutFile' => "{$sCurrentPath}/js/min/studymode.min.js"
));
*/

// add minified assets 
$aCssList = array('/css/min/studymode.min.css');
$aJsList = array('/js/min/studymode.min.js');


/**
 * Because of the task requested is to display a bar chart from a set of 
 * data, I decided to have the visualization of the chart done in javascript. 
 * I then use this index.php file to setup the layout and to pass data
 * from the server to the client.
 */
?>

<!DOCTYPE html>
<html>

  <head>
<?
foreach ($aCssList as $sFile) {
?>
    <link type="text/css" rel="stylesheet" href="<?=$sFile?>">
<?
}
?>
  </head>
  
  <body>
    <h3 id="coder">Lines of code written by <span class="name">jh</span>.</h3>
    <div id="chart"></div>
    
    <script type="application/javascript">
      var oThisPage = {
        sDeveloper: 'John Hauge',
        iMaxValue: 0,
        iTotalValue: 0,
        iValuesCount: 0,
        iChartHeight: 200,
        iBarWidth: 50,
        sColor: '<?= $sColor ?>',
        aData: <?=json_encode($aData)?>
      };
    </script>
    
<?

foreach ($aJsList as $sFile) {
?>
    <script type="application/javascript" src="<?=$sFile?>"></script>
<?
}
?>
  </body>
  
  
</html>









