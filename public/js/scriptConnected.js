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


const showMenu = document.querySelector(".show-menu")
const popUp = document.querySelector(".profil-pop-up")
let isPopUp = false

showMenu.addEventListener('click', () => {
    if (isPopUp) {
        popUp.style.display = 'none';
        isPopUp = false
    }else {
        popUp.style.display = 'block';
        isPopUp = true
    }
})



delteBtn = document.querySelectorAll('#delete');
if (delteBtn) {
    delteBtn.forEach(btn => {
        btn.addEventListener('click', () => {
            alert("Vous allez supprimer cet element");
        })
    });
}

// Pour ouvrire le formulaire du message
document.addEventListener("DOMContentLoaded", function() {
    const btn = document.querySelector(".btn-nouveau-message"); // bonne classe
    const form = document.querySelector(".new-msg"); 

    btn.addEventListener('click', function () {
        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = "flex"; 
        } else {
            form.style.display = "none"; // Cacher si déjà affiché
        }
    });
});

document.querySelectorAll('img').forEach(img => {
    img.setAttribute('draggable', false);
    img.addEventListener('contextmenu', e => e.preventDefault());
});


function toggleForm(id) {
    const form = document.getElementById('edit-form-' + id);
    if (form.style.display === 'block') {
        form.style.display = 'none';
    } else {
        form.style.display = 'block';
    }
}
function toggleReplyForm(id) {
    const form = document.getElementById('reply-form-' + id);
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}
