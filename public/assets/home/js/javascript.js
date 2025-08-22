
var swiper = new Swiper(".mySwiper", {
    effect: "coverflow",
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: "auto",
    coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: true,
    },
    pagination: {
        el: ".swiper-pagination",
    },
});
toggler = document.getElementById("ul");
btn = document.getElementById("textlogo");
btn.onclick = function () {
    toggler.classList.toggle("open");
}
animate1 = document.querySelector(".sec2")
animate = document.querySelectorAll(".animate")

window.onscroll = function () {
    if (window.scrollY >= (animate1.offsetTop - 20)) {

        animate.forEach(anim => {
            anim.classList.add("fade-left");
        });

    }
}



const boxes = document.querySelectorAll('.Sbox');
boxes[0].classList.add('show');
window.addEventListener('scroll', () => {

    const triggerBtn = window.innerHeight / 5 * 4;

    boxes.forEach(box => {
        const boxTop = box.getBoundingClientRect().top;
        if (boxTop < triggerBtn) {
            box.classList.add('show');
        } else {
            box.classList.remove('show');
        }
    })
})


