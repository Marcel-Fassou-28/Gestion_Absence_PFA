const togglerHamburger = document.querySelector(".menu-hamburger");
const navLinksContainer = document.querySelector(".navlink-container");

togglerHamburger.addEventListener('click', () => {
    togglerHamburger.classList.toggle('open');
    navLinksContainer.classList.toggle('open');
    
    const isExpanded = togglerHamburger.getAttribute('aria-expanded') === 'true' ? 'false' : 'true';
    togglerHamburger.setAttribute('aria-expanded', isExpanded);
})

new ResizeObserver(entries => {
    if (entries[0].contentRect.width <= 900) {
        navLinksContainer.style.transition = "transition 0.3s ease-out";
    } else {
        navLinksContainer.style.transition = "none";
    }
})

function adjustLineBreaks() {
    const paragraph = document.querySelector(".welcome-text");
    if (!paragraph) return;
    
    const words = paragraph.innerText.split(" ");
    let wordsPerLine;

    if (window.innerWidth > 726) {
        wordsPerLine = 5;
    } else if (window.innerWidth <= 726 &&  window.innerWidth > 327) {
        wordsPerLine = 3; // Écran moyen
    } else {
        wordsPerLine = 3; // Petit écran
    }

    let formattedText = "";
    for (let i = 0; i < words.length; i++) {
        formattedText += words[i] + " ";
        if ((i + 1) === wordsPerLine) {
            formattedText += "<br>";
            formattedText += words.slice(i + 1).join(" ");
            break;
        }
    }

    paragraph.innerHTML = formattedText.trim();
}

// Exécuter au chargement et lors du redimensionnement de la fenêtre
window.addEventListener("load", adjustLineBreaks);
window.addEventListener("resize", adjustLineBreaks);

