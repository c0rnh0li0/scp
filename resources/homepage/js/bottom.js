$(function () {
    $('#test').scrollToFixed();
    $('.res-nav_click').click(function() {
        $('.main-nav').slideToggle();
        return false;
    });

    $('.main-nav li a, .servicelink').not('.popup-link').bind('click', function(e) {
        var $anchor = $(this);

        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top - 102
        }, 1500, 'easeInOutExpo');
        /*
        if you don't want to use the easing effects:
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1000);
        */
        if ($(window).width() < 768) {
            $('.main-nav').hide();
        }
        e.preventDefault();
    });

    function toJSONString( form ) {
        var obj = {};
        var elements = form.querySelectorAll( "input, select, textarea" );
        for( var i = 0; i < elements.length; ++i ) {
            var element = elements[i];
            var name = element.name;
            var value = element.value;

            if( name ) {
                obj[ name ] = value;
            }
        }

        return JSON.parse(JSON.stringify(obj));
    }

    function initializeForms() {
        $('.scp-form').off('submit').on('submit', function (e) {
            e.preventDefault();

            var frmData = toJSONString(this);

            $.post(this.action, frmData )
                .done(function(response, msg, jqx) {
                    if (response.success) {
                        window.localStorage.setItem('token', response.success.token);
                        window.location.href = response.success.goto;
                    }
                    else {

                    }
                })
                .fail(function(jqx, error, msg) {
                    console.log('login fail', arguments);
                    //
                })
                .always(function() {
                    console.log('login always', arguments);
                    //
                });

            return false;
        });
    }

    $('.popup-link').bind('click', function(e) {
        e.preventDefault();

        $.get($(this).attr('href'), function (response) {
            $.featherlight(response, {
                variant: null,
                afterContent: function () {
                    initializeForms();
                }
            });
        });

        return false;
    });

    var $container = $('.portfolioContainer'),
        $body = $('body'),
        colW = 375,
        columns = null;


    $container.isotope({
        // disable window resizing
        resizable: true,
        masonry: {
            columnWidth: colW
        }
    });

    $(window).smartresize(function() {
        // check if columns has changed
        var currentColumns = Math.floor(($body.width() - 30) / colW);
        if (currentColumns !== columns) {
            // set new column count
            columns = currentColumns;
            // apply width to container manually, then trigger relayout
            $container.width(columns * colW)
                .isotope('reLayout');
        }

    }).smartresize(); // trigger resize to set container width
    $('.portfolioFilter a').click(function() {
        $('.portfolioFilter .current').removeClass('current');
        $(this).addClass('current');

        var selector = $(this).attr('data-filter');
        $container.isotope({

            filter: selector,
        });
        return false;
    });
});
