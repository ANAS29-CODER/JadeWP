const page_number = document.querySelectorAll(".page-link");
page_number.forEach((page) => {
    page.addEventListener("click", function(e) {
        e.preventDefault();
        page_number.forEach((page) => page.classList.remove("active-page"));
        this.classList.add("active-page");
    });
});
