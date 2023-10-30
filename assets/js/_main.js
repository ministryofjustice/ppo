/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 * ======================================================================== */

var Roots = {
  // All pages
  common: {
    init: function() {
      // JavaScript to be fired on all pages
    }
  },
  // Home page
  home: {
    init: function() {
      // JavaScript to be fired on the home page
    }
  },
  // About page
  about: {
    init: function() {
      // JavaScript to be fired on the about page
    }
  }
};

var UTIL = {
  fire: function(func, funcname, args) {
    var namespace = Roots;
    funcname = (funcname === undefined) ? 'init' : funcname;
    if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
      namespace[func][funcname](args);
    }
  },
  loadEvents: function() {
    UTIL.fire('common');

    $.each(document.body.className.replace(/-/g, '_').split(/\s+/),function(i,classnm) {
      UTIL.fire(classnm);
    });
  }
};

$(document).ready(UTIL.loadEvents);

/* Toggle show/hide button text on FII reports page*/

$('button.showHide').click(function() {
    if ($(this).text() === 'Show details') {
         $(this).text('Hide details');
    }
    else {
        $(this).text('Show details');
    }
});

/* Increase/decrease font sizes */

// $(document).ready(function(){
  
//   $(".sml").click(function(event){
//     event.preventDefault();
//     $("h1").animate({"font-size":"36px"});
//     $("h2").animate({"font-size":"30px"});
//     $("p").animate({"font-size":"14px", "line-height":"20px"});
//     $("li").animate({"font-size":"14px", "line-height":"20px"});
//     $("#breadcrumbs").animate({"font-size":"14px", "line-height":"20px"});
    
//   });
  
//   $(".med").click(function(event){
//     event.preventDefault();
//     $("h1").animate({"font-size":"48px"});
//     $("h2").animate({"font-size":"36px"});
//     $("p").animate({"font-size":"18px", "line-height":"24px"});
//     $("li").animate({"font-size":"18px", "line-height":"24px"});
//     $("#breadcrumbs").animate({"font-size":"18px", "line-height":"24px"});
    
//   });
  
//   $(".lrg").click(function(event){
//     event.preventDefault();
//     $("h1").animate({"font-size":"60px"});
//     $("h2").animate({"font-size":"48px"});
//     $("p").animate({"font-size":"24px", "line-height":"32px"});
//     $("li").animate({"font-size":"24px", "line-height":"32px"});
//     $("#breadcrumbs").animate({"font-size":"24px", "line-height":"32px"});
    
//   });
  
//   $( "a" ).click(function() {
//    $("a").removeClass("selected");
//   $(this).addClass("selected");
  
//  });

// });
