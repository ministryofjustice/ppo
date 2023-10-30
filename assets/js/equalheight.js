equalheight = function(container){

var currentTallest = 0,
     currentRowStart = 0,
     rowDivs = new Array(),
     $el,
     topPosition = 0;
 $(container).each(function() {

   $el = $(this);
   $($el).height('auto')
   topPostion = $el.position().top;

   if (currentRowStart != topPostion) {
     for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
       rowDivs[currentDiv].height(currentTallest);
     }
     rowDivs.length = 0; // empty the array
     currentRowStart = topPostion;
     currentTallest = $el.height();
     rowDivs.push($el);
   } else {
     rowDivs.push($el);
     currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
  }
   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
     rowDivs[currentDiv].height(currentTallest);
   }
 });
};

$(window).load(function() {
//    columnConform('nav>ul>li>a');
//    columnConform('#home-cta-container .cta-inner h2');
//    columnConform('#home-cta-container .cta-inner');    
//    equalheight('nav>ul>li>a');
    equalheight('#home-cta-container .cta-inner h2');
    equalheight('#home-cta-container .cta-inner'); 
});

$(window).resize(function() {
    //equalheight('nav>ul>li>a');
    equalheight('#home-cta-container .cta-inner h2');
    equalheight('#home-cta-container .cta-inner'); 
});