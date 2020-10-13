
// Popup Window

    var scrollTop = '';
    var newHeight = '100';
     
    $(window).bind('scroll', function() {
      scrollTop = $( window ).scrollTop();
      newHeight = scrollTop + 100;
    });
            
    $('.popup-trigger').click(function(e) {
      e.stopPropagation();
      if(jQuery(window).width() < 767) {
        $(this).after( $( ".popup" ) );
        $('.popup').show().addClass('popup-mobile').css('top', 0);
        $('html, body').animate({
          scrollTop: $('.popup').offset().top
        }, 500);   
      } else {
        $('.popup').removeClass('popup-mobile').css('top', newHeight).toggle();
      };
    });
            
    $('html').click(function() {
      $('.popup').hide();
    });
     
    $('.popup-btn-close').click(function(e){
      $('.popup').hide();
    });
     
    $('.popup').click(function(e){
      e.stopPropagation();
    });
