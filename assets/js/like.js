// Sélectionner tous les boutons de like
document.querySelectorAll('.like-btn').forEach(button => {
    button.addEventListener('click', function () {
        const articleId = this.dataset.articleId; // ID de l'article
        const csrfToken = this.dataset.csrf; // Token CSRF

        // Sélectionne l'icône et le compteur associés au bouton
        const icon = this.querySelector('i');
        const likeCount = this.querySelector('p');

        // Envoie de la requête POST pour liker/unliker l'article
        fetch(`/like/${articleId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': csrfToken
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            // Met à jour le compteur de likes
            likeCount.textContent = data.totalLikes;

            // Ajoute ou retire la classe 'liked-icon' sur l'icône
            if (data.liked) {
                icon.classList.add('liked-icon', 'bi-hand-thumbs-up-fill');
                icon.classList.remove('bi-hand-thumbs-up');
            } else {
                icon.classList.remove('liked-icon', 'bi-hand-thumbs-up-fill');
                icon.classList.add('bi-hand-thumbs-up');
            }
        })
        .catch(error => console.error('Erreur:', error));
    });
});
