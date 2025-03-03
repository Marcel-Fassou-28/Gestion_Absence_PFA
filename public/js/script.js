const navbar = document.querySelector('.nav-bar');
const toggleClose = document.querySelector('.toggle');
const toggleOpen = document.querySelector('.toggle-open');
const hr = document.querySelector('hr');

toggleOpen.addEventListener('click', () => {
    navbar.style.display = "block";
    toggleClose.style.display = "block";
    toggleOpen.style.display = "none";
    hr.style.display = "inline";
});

toggleClose.addEventListener('click', () => {
    navbar.style.display = "none";
    toggleClose.style.display = "none";
    toggleOpen.style.display = "block";
    hr.style.display = "none";
});

