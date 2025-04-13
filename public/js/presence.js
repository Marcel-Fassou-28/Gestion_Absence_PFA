document.addEventListener('DOMContentLoaded', () => {
    const presenceListButton = document.querySelector('.presence-list');
    const closeButtons = document.querySelectorAll('.close-modal');
    const overlays = document.querySelectorAll('.modal-box-overlay');
    const modal = document.getElementById('scanner-modal');
    const modalContent = modal.querySelector('.modal-box-content');

    // Ouvre le modal
    if (presenceListButton) {
        presenceListButton.addEventListener('click', () => {
            modal.classList.add('active');
            modal.querySelector('.modal-box-overlay').classList.add('active');
            modalContent.classList.add('active');
            document.body.style.overflow = 'hidden';
            // Centre dynamiquement
            modalContent.style.top = '50%';
            modalContent.style.left = '50%';
            modalContent.style.transform = 'translate(-50%, -50%)';
        });
    }

    // Ferme le modal avec le bouton Annuler
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('.modal-box');
            if (modal) {
                modal.classList.remove('active');
                modal.querySelector('.modal-box-overlay').classList.remove('active');
                modal.querySelector('.modal-box-content').classList.remove('active');
                document.body.style.overflow = '';
                // Réinitialiser le formulaire
                modal.querySelector('form').reset();
            }
        });
    });

    // Ferme le modal en cliquant sur l'overlay
    overlays.forEach(overlay => {
        overlay.addEventListener('click', () => {
            const modal = overlay.closest('.modal-box');
            if (modal) {
                modal.classList.remove('active');
                overlay.classList.remove('active');
                modal.querySelector('.modal-box-content').classList.remove('active');
                document.body.style.overflow = '';
                // Réinitialiser le formulaire
                modal.querySelector('form').reset();
            }
        });
    });

    // Ferme le modal avec la touche Échap
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const activeModal = document.querySelector('.modal-box.active');
            if (activeModal) {
                activeModal.classList.remove('active');
                activeModal.querySelector('.modal-box-overlay').classList.remove('active');
                activeModal.querySelector('.modal-box-content').classList.remove('active');
                document.body.style.overflow = '';
                // Réinitialiser le formulaire
                activeModal.querySelector('form').reset();
            }
        }
    });

    // Gestion de la soumission du formulaire
    if (modalContent) {
        modalContent.addEventListener('submit', (e) => {
            e.preventDefault();
            const fileInput = document.getElementById('presence-file');
            const cameraInput = document.getElementById('presence-camera');
            if (!fileInput.files.length && !cameraInput.files.length) {
                alert('Veuillez sélectionner un fichier ou prendre une photo.');
                return;
            }
            alert('Formulaire soumis avec succès !');
            // Fermer le modal
            modal.classList.remove('active');
            modal.querySelector('.modal-box-overlay').classList.remove('active');
            modalContent.classList.remove('active');
            document.body.style.overflow = '';
            // Réinitialiser le formulaire
            modalContent.reset();
            // TODO : Ajouter une requête AJAX pour envoyer le fichier au serveur si nécessaire
        });
    }

    // Recentrer lors du défilement
    window.addEventListener('scroll', () => {
        const activeModal = document.querySelector('.modal-box.active');
        if (activeModal) {
            const modalContent = activeModal.querySelector('.modal-box-content');
            modalContent.style.top = '50%';
            modalContent.style.left = '50%';
            modalContent.style.transform = 'translate(-50%, -50%)';
        }
    });

    // Recentrer lors du redimensionnement
    window.addEventListener('resize', () => {
        const activeModal = document.querySelector('.modal-box.active');
        if (activeModal) {
            const modalContent = activeModal.querySelector('.modal-box-content');
            modalContent.style.top = '50%';
            modalContent.style.left = '50%';
            modalContent.style.transform = 'translate(-50%, -50%)';
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const uploadMobile = document.querySelector('#upload-mobile');
    const uploadDestok = document.querySelector('#upload-desktop');

    function isMobileDevice() {
        return /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
    }
    
    // Affiche uniquement l'input correspondant à l'appareil
    window.addEventListener('DOMContentLoaded', () => {
        if (isMobileDevice()) {
            document.querySelector('#upload-desktop').style.display = 'none';
            document.querySelector('#upload-mobile').style.display = 'flex';
        } else {
            document.querySelector('#upload-mobile').style.display = 'none';
            document.querySelector('#upload-desktop').style.display = 'flex';
        }
    });
})