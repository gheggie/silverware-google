/* SilverWare Google Boot
===================================================================================================================== */

import $ from 'jquery';

$(function() {
  
  // Initialise:
  
  if (window.___gcfg === undefined) {
    window.___gcfg = {};
  }
  
  // Set Language:
  
  if ($('body').is('[data-google-api-lang]')) {
    window.___gcfg.lang = $('body').data('google-api-lang');
  }
  
  // Load Google Platform:
  
  $.getScript('//apis.google.com/js/platform.js');
  
  // Load Google Analytics:
  
  if ($('body').is('[data-google-tracking-id]')) {
    
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    
    ga('create', $('body').data('google-tracking-id'), 'auto');
    ga('send', 'pageview');
    
  }
  
});
