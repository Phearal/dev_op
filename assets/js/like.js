// Selects all the like buttons
document.querySelectorAll('.like-btn').forEach(button => {
    button.addEventListener('click', function () {
        const articleId = this.dataset.articleId; // ID de l'article
        const csrfToken = this.dataset.csrf; // Token CSRF

        // Select the icon and counter associated
        const icon = this.querySelector('i');
        const likeCount = this.querySelector('p');

        // Send POST request to add/remove like from post
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
            // Updates like count
            likeCount.textContent = data.totalLikes;

            // Adds/removes class 'liked-icon' on the icon
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
