$(document).ready(function() {
    $('.lightbox').magnificPopup({type:'image'});

    var height = $('footer').height();
    $('body').css({
        "margin-bottom": height
    });

    $('.dropdown-menu li > a').click(function(e){
      e.preventDefault();

      var selected = $(this).attr('title');
      $('.category').val(selected);
    });
});
