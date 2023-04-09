        var swiper = new Swiper(".swiper-container", {
            slidesPerView: 3,
            spaceBetween: 20,
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
                900: {
                    slidesPerView: 1,
                },
                995: {
                    slidesPerView: 2,
                },
                1420: {
                    slidesPerView: 3,
                }
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
