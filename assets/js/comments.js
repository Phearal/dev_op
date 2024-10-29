$(document).ready(function() {
    // Gestionnaire de clics pour les boutons de suppression
    $('.delete-data-btn').click(function() {
        var articleId = $(this).data('id'); // Récupération de l'ID de l'article
        $('#confirm-delete-btn').data('id', articleId); // Mise à jour du bouton de confirmation
        console.log('Article ID to delete:', articleId); // Debug
    });

    // Gestionnaire de clic pour le bouton de confirmation
    $('#confirm-delete-btn').click(function() {
        var articleId = $(this).data('id'); // Récupération de l'ID à supprimer
        var csrfToken = $(this).data('token'); // Récupération du token CSRF

        console.log('Sending request to delete ID:', articleId); // Debug

        $.ajax({
            type: 'POST',
            url: '/admin/article/delete/' + articleId,
            data: {
                _token: csrfToken // Envoi du token CSRF
            },
            success: function(response) {
                if (response.message === "Article was deleted successfully.") {
                    // Afficher le toast de succès
                    showToast(response.message);
                    // Suppression de la ligne de l'article
                    $('button[data-id="' + articleId + '"]').closest('tr').remove();
                } else {
                    // Afficher le toast d'erreur
                    showToast(response.message, 'error');
                }
            },
            error: function(xhr) {
                // Afficher le toast d'erreur en cas de problème
                showToast("An error occurred.", 'error');
            }
        });
    });

    // Fonction pour afficher les toasts
    function showToast(message, type = 'success') {
        var toastHtml = `
            <div class="toast ${type === 'error' ? 'text-danger' : 'text-success'}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="mr-auto">${type === 'error' ? 'Erreur' : 'Confirmation'}</strong>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">${message}</div>
            </div>
        `;
        $('#toast-container').append(toastHtml);
        var $toast = $('#toast-container .toast:last'); // Récupérer le dernier toast ajouté
        $toast.toast({ autohide: true, delay: 5000 });
        $toast.toast('show');
    }
});
