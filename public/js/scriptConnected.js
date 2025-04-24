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



document.addEventListener('DOMContentLoaded', () => {
    const modalButtons = document.querySelectorAll('.show-state');
    const closeButtons = document.querySelectorAll('.close-modal');
    const overlays = document.querySelectorAll('.modal-overlay');

    // Ouvre le modal
    modalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal-id');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('active');

                const modalContent = modal.querySelector('.modal-content');
                modalContent.style.top = '50vh';
                modalContent.style.left = '50vw';
                modalContent.style.transform = 'translate(-50%, -50%)'

                //Scroll automatique vers le haut
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });

    // Ferme le modal avec le bouton fermer
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('.modal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = ''; // Restaure le scroll
            }
        });
    });

    // Ferme le modal en cliquant sur l'overlay
    overlays.forEach(overlay => {
        overlay.addEventListener('click', () => {
            const modal = overlay.closest('.modal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = ''; // Restaure le scroll
            }
        });
    });

    // Ferme le modal avec la touche Échap
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const activeModal = document.querySelector('.modal.active');
            if (activeModal) {
                activeModal.classList.remove('active');
                document.body.style.overflow = ''; // Restaure le scroll
            }
        }
    });
});

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