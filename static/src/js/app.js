//# Application object
//
//*

'use strict';

var App = {
    $win: $(window),
    $doc: $(document),
    // $html: $('html'),
    $body: $('body')
},
    mq = window.matchMedia('(min-width: 992px)'),
    mobileFirst = 1,
    width = 0,
    table = {},
    mql = window.matchMedia('screen and (min-width: 768px)'),
    offset;

// Menu
$('.js-hamburger').on('click', function (event) {
    event.preventDefault();
    $('html').toggleClass('is-menu');
});
// Menu end

// Search
$('.js-search-btn').on('click', function (event) {
    if (!$('.c-banner').hasClass('is-open')) {
        event.preventDefault();
        $('.c-banner').addClass('is-open');
        $('.js-search-input').focus();
    } else if ($('.js-search-input').val().length < 1) {
        event.preventDefault();
        $('.c-banner').removeClass('is-open');
    }
});
$(document).on('click', function (event) {
    if (!$(event.target).closest('.js-search').length) {
        $('.c-banner').removeClass('is-open');
    }
});
// Search end

// Facebook
if ($('.js-fb').length) {
    $(window).on('resize', _.debounce(function () {
        var fbWidth = $('.js-fb').width();
        $('.fb-page').data('width', fbWidth).attr('data-width', fbWidth)
            .children('span').css('width', fbWidth);
        FB.XFBML.parse();
    }, 150));
}
// Facebook end

// Img loaded
$('.js-img').each(function () {
    $(this).imagesLoaded(function (obj) {
        $(obj.elements).addClass('is-loaded');
    });
});
// Loaded end

// Social buttons
$('a[data-service]').social();
$('a[data-service]').on('click', function (event) {
    var socialPopup;
    var socialPopup_width = 600;
    var socialPopup_height = 450;
    var center_left = (screen.width / 2) - (socialPopup_width / 2);
    var center_top = (screen.height / 2) - (socialPopup_height / 2);
    event.preventDefault();
    socialPopup = window.open($(this).attr('href'), '', 'scrollbars=1, width=' + socialPopup_width + ', height=' + socialPopup_height + ', left = ' + center_left + ', top = ' + center_top);
    socialPopup.focus();
});
// Social end

function media(mq) {
    if (mq.matches) {
        // Fixed header
        $('.js-banner').headroom({
            offset : 100
        });
        // Header end
        // Sticky sidebar
        $('.js-sticky').stick_in_parent({
            parent: '.js-parent',
            offset_top: 146
        });
        // Sticky sidebar end
        mobileFirst = 0;
        // Facebook widget
        $('.fb-page').attr({
            'data-small-header': false,
            'data-tabs': 'timeline'
        });
        // Facebook widget end
    } else {
        // Fixed header, sticky sidebar
        if (mobileFirst === 0) {
            $('.js-banner').headroom('destroy');
            $('.js-sticky').trigger('sticky_kit:detach');
        }
        // Fixed header, sticky sidebar end
        // Facebook widget
        $('.fb-page').attr({
            'data-small-header': true
        }).removeAttr('data-tabs');
        // Facebook widget end
    }
}
mq.addListener(media);
media(mq);

// Mobile dropdown
$('.js-dropdown').on('click', function () {
    event.preventDefault();
    $(this).closest('.o-block').toggleClass('is-open');
});
// Mobile dropdown end

// Swipebox
$('.slick-slide:not(.slick-cloned) .swipebox').swipebox({
    hideBarsDelay: 0
});
// Swipebox end

// Iscroll
function tablet(mql) {
    if (mql.matches) {
        offset = -235;
    } else {
        offset = -295;
    }
}

function changeOffset(currentScroll) {
    if (currentScroll < offset) {
        $('.js-session')
            .find('.td-4, .td-5').addClass('is-fixed')
            .css({
                '-webkit-transform': 'translateX(' + (-currentScroll + offset) + 'px)',
                'transform': 'translateX(' + (-currentScroll + offset) + 'px)'
            });
    } else {
        $('.js-session')
            .find('.td-4, .td-5').removeClass('is-fixed').removeAttr('style');
    }
}

mql.addListener(tablet);
tablet(mql);

$(window).on('load', function () {
    $('.js-lesson').find('.c-schedule__head .td').each(function (index, el) {
        width += $(el).outerWidth();
    });
    $('.js-session').find('.c-schedule__body .tr:first-child .td').each(function (index, el) {
        width += $(el).outerWidth();
    });
    $('.js-lesson, .js-session').find('.c-schedule__head, .c-schedule__body').children().width(width);


    $('.js-scroll').each(function () {
        var $this = $(this),
            id = $this.attr('id'),
            siblings =  $this.siblings('.js-scroll').attr('id');

        table[id] = new IScroll('#' + id, {
            scrollX: true,
            scrollY: false,
            eventPassthrough: true,
            bounce: false,
            probeType: 3,
            momentum: false
            // mouseWheel: true,
            // disablePointer: true
        });

        table[id].on('scroll', function () {
            var x = Math.round(this.x);

            table[siblings].scrollTo(x, 0);

            if ($('.c-schedule').hasClass('js-lesson')) {
                if (x < 0) {
                    $('.c-schedule__body')
                        .find('.lesson, .day .td')
                        .css({
                            '-webkit-transform': 'translateX(' + -x + 'px)',
                            'transform': 'translateX(' + -x + 'px)'
                        });
                } else {
                    $('.c-schedule__body')
                        .find('.lesson, .day .td').removeAttr('style');
                }
            } else {
                changeOffset(x);
            }
        });
    });
});

$(window).on('resize', function () {
    var schedule = $('.js-lesson').outerWidth();

    if ($('.js-lesson').length && width < schedule) {
        $('.c-schedule__body')
            .find('.lesson, .day .td').removeAttr('style');
    }

    if ($('.js-session').length) {
        $.each(table, function (index, val) {
            val.refresh();
            changeOffset(val.x);
        });
    }
});
// Iscroll end

// Schedule fix
$('.c-schedule__head').stick_in_parent({
    parent: '.c-schedule'
});
// Schedule fix end

// Hightlight
function mark(text) {
    $('.c-schedule__body').unmark().mark(text);
    $('.js-unmark').addClass('is-mark');
}

$('.js-keyword').on('keyup', function (event) {
    if (event.keyCode == 13) {
        mark($(this).val());
    }
});

$('.js-mark').on('click', function (event) {
    event.preventDefault();
    mark($('.js-keyword').val());
});

$('.js-unmark').on('click', function (event) {
    event.preventDefault();
    $('.js-keyword').val('').focus();
    $('.js-unmark').removeClass('is-mark');
    $('.c-schedule__body').unmark();
});

// Hightlight end

App.transition = 300;
