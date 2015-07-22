(function($){
    //open job detail
    $('.job-detail').hide();
    $('.toggle-detail').each(function() {
        var trBgColor = $(this).parent('td').css('background-color');
        $(this).click(function() {
        $('#' + $(this).data('name')).toggle();
        });
    });
  // job detail switch
  $('.toggle-detail').each(function() {
    $(this).click(function() {
      $(this).parents('.job-detail').find('.detail-content').hide();
      $(this).parents('.job-detail').find('.' + $(this).data('name')).show();
    });
  });
})(jQuery);

