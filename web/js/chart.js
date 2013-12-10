/**
 * oThisPage.fnCalculateStandardDeviation
 * 
 * Function to calculate the the standard deviation of the chart data.
 * 
 * @return Float Standard deviation of data.
 */
oThisPage.fnCalculateStandardDeviation = function() {
  oThisPage.fMean = oThisPage.iTotalValue / oThisPage.iValuesCount;
  var fMeanSquareTotal = 0;
  $.each(oThisPage.aData, function(i) {
    var iValue = parseInt(this[1]);
    
    fMeanSquareTotal += Math.pow(iValue - oThisPage.fMean, 2);
  });
  
  var fMeanSquare = fMeanSquareTotal / oThisPage.iValuesCount;
  
  var fStandardDeviation = Math.sqrt(fMeanSquare);
  
  return fStandardDeviation;
}

/**
 * oThisPage.fnInit
 * 
 * Function to handle intialization of javascript code for this page.
 * 
 */
oThisPage.fnInit = function() {
  // set developer name
  $("#coder > span.name").text(oThisPage.sDeveloper);
  
  // grab chart element
  var $oChart = $('#chart');
  oThisPage.aChartList = [];
  
  // get the current time in seconds
  var iNow = Math.round(new Date().getTime() / 1000);
  
  // first pass, dom creation
  $.each(oThisPage.aData, function() {
    var sTimestamp = this[0];
    var sValue = this[1];
    
    var iNewValue = parseInt(sValue);
    // determine max value
    if (iNewValue > oThisPage.iMaxValue) {
      oThisPage.iMaxValue = iNewValue;
    }
    // increment counter
    oThisPage.iValuesCount++;
    // add to total value
    oThisPage.iTotalValue += iNewValue;
    // determine number of weeks since timestamp
    var iWeeks = Math.round((iNow - parseInt(sTimestamp)) / 60 / 60 / 24 / 7);
    // create dom element and add to chart element
    var $oValue = $(
      '<div class="value" title="' + iWeeks + ' weeks ago"></div>'
    ).appendTo($oChart);
    // save timestamp and value to memory instead of dom tag, faster lookup time
    $oValue.data('sTimestamp', sTimestamp);
    $oValue.data('iValue', iNewValue);
    // add gradient
    $oValue.fnAddGradient(oThisPage.sColor, '#000');
    // add create dom element to memory array for faster lookup
    oThisPage.aChartList.push($oValue);
  });
  
  // second pass, set position and dimensions
  $.each(oThisPage.aChartList, function(i) {
    var $oDiv = this;
    // grab value from memory
    var iValue = $oDiv.data('iValue');
    // assign new position and dimensions to element
    $oDiv.css({
      height: ((iValue / oThisPage.iMaxValue) * oThisPage.iChartHeight) + 'px',
      width: oThisPage.iBarWidth + 'px',
      left: (i * (oThisPage.iBarWidth + 10) + 5) + 'px'
    }).text(iValue);
  });
  
  // fit bar chart to window width, bind window resize
  oThisPage.$window = $(window);
  oThisPage.$window.resize(function() {
    
    var iTotalCount = oThisPage.aChartList.length;
    var iWindowWidth = oThisPage.$window.width();
    var iWidth = Math.floor(( iWindowWidth / iTotalCount ) - 10);
    // process through chart and resize and reposition bars based on window size.
    $.each(oThisPage.aChartList, function(i) {
      var $oDiv = this;
      $oDiv.css({
        width: iWidth + 'px',
        left: (i * (iWidth + 10) + 5) + 'px'
      });
    });
    
  });
  // trigger resize.
  oThisPage.$window.resize();
  
  // calculate standard diviation
  var fDiviation = oThisPage.fnCalculateStandardDeviation();
  // third pass, color green bars
  $.each(oThisPage.aChartList, function(i) {
    var $oDiv = this;
    var iValue = $oDiv.data('iValue');
    var fDiff = Math.abs(iValue - oThisPage.fMean);
    
    if (fDiff > fDiviation) {
      // if outsize of the standard diviation color green
      $oDiv.fnAddGradient('#00FF00', '#000');
    }
  });
  
  
} // end fnInit

// Do everything when the document is ready.
$(document).ready(function() {
  
  // extend jquery to have a cross browser add gradient function.
  jQuery.fn.extend({
    fnAddGradient: function(sFromColor, sToColor) {
      $(this)
        .css('background-image', '-webkit-gradient(linear,left top,left bottom,color-stop(0, ' + sFromColor + '),color-stop(1, ' + sToColor + ')')
        .css('background-image', '-o-linear-gradient(top, ' + sFromColor + ' 0%, ' + sToColor + ' 100%)')
        .css('background-image', '-moz-linear-gradient(top, ' + sFromColor + ' 0%, ' + sToColor + ' 100%)')
        .css('background-image', '-webkit-linear-gradient(top, ' + sFromColor + ' 0%, ' + sToColor + ' 100%)')
        .css('background-image', '-ms-linear-gradient(top, ' + sFromColor + ' 0%, ' + sToColor + ' 100%)')
        .css('background-image', 'linear-gradient(top, ' + sFromColor + ' 0%, ' + sToColor + ' 100%)');
    }
  });
  
  // initalize page
  oThisPage.fnInit();
});