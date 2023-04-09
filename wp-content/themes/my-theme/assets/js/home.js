var swiper = new Swiper(".swiper-container", {
    slidesPerView: 2,
    spaceBetween: 0,
    breakpoints: {
        100: {
            slidesPerView: 1,
        },
        200: {
            slidesPerView: 1,
        },
        300: {
            slidesPerView: 1,
        },
        650: {
            slidesPerView: 2,
        },
        1024: {
            slidesPerView: 2,
        },
        1600: {
            slidesPerView: 2,
        },
    },
    grabCursor: true,
    allowTouchMove: true,
    loopedSlides: 2,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    loop: true,
});
