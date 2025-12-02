const menuToggle = document.getElementById("menu-toggle");
const menuContent = document.getElementById("menu-content");
const arrowIcon = document.querySelector(".arrow-icon");
const extraContent = document.querySelector(".extra-content");

menuToggle.addEventListener("click", (e) => {
    e.preventDefault();
    if (menuContent.style.display === "block") {
        menuContent.style.display = "none";
        arrowIcon.style.transform = "rotate(0deg)";
        extraContent.style.display = "block"; 
    } else {
        menuContent.style.display = "block";
        arrowIcon.style.transform = "rotate(180deg)";
        extraContent.style.display = "none"; 
    }
});