/*--l'animation de mon titre de site--*/
const monTitre = "Découvrez l'élégance à votre poignet !";
let i = 0;
function typeWriter() {
    if (i < monTitre.length) {
        document.getElementById("titre").innerHTML += monTitre.charAt(i);
        i++;
        setTimeout(typeWriter, 75 );
    }
}
typeWriter();


/*--le boutton berger en version mobile--*/
const burgerBtn = document.querySelector(".navbar-toggler");
const menu = document.querySelector(".navbar-collapse");
console.log(burgerBtn);
burgerBtn.addEventListener("click", () => {
    if (menu.style.display === "block") {
        menu.style.display = "none"; 
    } else {
        menu.style.display = "block"; 
    }
});
