const categories = document.querySelectorAll(".header-section a p");
categories.forEach((category) => {
    category.addEventListener("click", function () {
        categories.forEach((category) => category.classList.remove("active-categories"));
        this.classList.add("active-categories");
    });
});
