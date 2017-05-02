//# Carousel
//
//*

'use strict';

function initCarousel($carousel, initialSlide) {
    initialSlide = initialSlide ? initialSlide : 0;

    $carousel.slick({
        prevArrow: '<button class="c-btn-prev"><span class="icon i-arrow"></span></button>',
        nextArrow: '<button class="c-btn-next"><span class="icon i-arrow"></span></button>',
        slidesToShow: 1,
        slidesToScroll: 1,
        initialSlide: initialSlide,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    dots: true,
                    arrows: false
                }
            }
        ]
        // dots: true,
        // arrows: false,
        // autoplay: true,
        // autoplaySpeed: 2000,
        // centerMode: true,
        // variableWidth: true,
        // infinite: false,
        // pauseOnFocus: true,
        // pauseOnHover: true,
    });
}


//## Initialize
//
//*

$('.js-carousel').each(function () {
    var $self = $(this);

    initCarousel($self);
});
