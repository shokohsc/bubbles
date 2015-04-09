$(document).ready(function() {
    $('.lightbox').magnificPopup({type:'image'});

    var height = $('footer').height();
    $('body').css({
        "margin-bottom": height
    });

    $('.search-panel .dropdown-menu').find('a').click(function(e) {
        e.preventDefault();
        var param = $(this).attr("href").replace("#","");
        var concept = $(this).text();
        $('.search-panel span#search_concept').text(concept);
        $('#entity').val(param.toLowerCase());
    });
});
