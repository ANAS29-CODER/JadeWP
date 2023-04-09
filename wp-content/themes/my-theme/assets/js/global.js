const navLinks = document.querySelectorAll(".navbar-nav .nav-item a");
navLinks.forEach((link) => {
    const href = link.getAttribute("href");
    const media = document.querySelector(".navbar-nav .media a");
    if (href == window.location.pathname) {
        href == "/photo-gallery" || href == "/latest-news"
            ? media.classList.add("active")
            : link.classList.add("active");
    } else link.classList.remove("active");
});
