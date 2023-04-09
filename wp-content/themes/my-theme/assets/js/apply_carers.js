const uploadBtn = document.getElementById("uploadBtn");
const pdfFile = document.getElementById("pdfFile");

uploadBtn.addEventListener("click", (event) => {
    event.preventDefault();
    pdfFile.click();
    console.log(pdfFile.files[0]);
});
