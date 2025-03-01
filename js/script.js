const sidePanel = document.querySelector('.sidepanel');
const navbar = document.querySelector('.navbar');
const toggleClose = document.querySelector('.toggle');
const toggleOpen = document.querySelector('.toggle-open');
const hr = document.getElementsByTagName('hr');
const width = window.innerWidth;

sidePanel.classList.remove('sidepanel');

if (width <= 352) {
    toggleOpen.addEventListener('click', () => {
        sidePanel.classList.remove('navbar');
        navbar.classList.add('sidepanel');
        toggleOpen.style.display = "none";
        toggleClose.style.display = "block";
        hr.style.display = "inline";
    });
    
    toggleClose.addEventListener('click', () => {
        sidePanel.classList.remove('sidepanel');
        navbar.classList.add('navbar');
        toggleClose.style.display = "none";
        toggleOpen.style.display = "block";
        hr.style.display = "none";
    });
}else {
    toggleClose.style.display = "none";
    toggleOpen.style.display = "none";
}


console.log(width);