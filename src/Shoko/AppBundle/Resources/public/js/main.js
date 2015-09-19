$(document).ready(function() {
    var height = $('footer').height();
    $('body').css({
        'margin-bottom': height
    });

    $('.search-panel .dropdown-menu').find('a').click(function(e) {
        e.preventDefault();
        var param = $(this).attr('href').replace('#','');
        var concept = $(this).text();
        $('.search-panel span#search_concept').text(concept);
        $('#entity').val(param.toLowerCase());
    });

    //google-analytics
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-55046906-2', 'auto');
    ga('send', 'pageview');
});

$(document).on('click', '.lightbox', function(e) {
    $(this).magnificPopup({type:'image'});
});

var load_comic = function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    $.ajax({
        url: Routing.generate('comic', { id: id })
    }).done(function(data) {
        $('#content').html(data);
    });
};
$(document).on('click', '.comic-link', load_comic);

var load_serie = function(e) {
    var id = $(this).attr('data-id');
    var title = $(this).text();
    $.ajax({
        url: Routing.generate('api_serie_comics', { id: id })
    }).done(function(data) {
        $('#content').html('');
        $('#content').append('<h1 class="text-center">'+title+'</h1>');
        $('#content').append(data);
    });
    e.preventDefault();
};
$(document).on('click', '.serie-link', load_serie);
