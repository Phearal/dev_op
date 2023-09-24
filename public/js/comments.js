$(document).ready(function() {
    $('.validate-comment').click(function() {
        var commentId = $(this).data('comment-id');
        var $commentRow = $(this).closest('tr'); // Sélectionnez la ligne du commentaire

        $.ajax({
            type: 'POST',
            url: '/comment/validate-comment/' + commentId,
            success: function(response) {
                // Afficher le toast Bootstrap
                $('#toast-container').append('<div class="toast" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header"><strong class="mr-auto">Confirmation</strong></div><div class="toast-body">' + response.message + '</div></div>');
                $('.toast').toast({ autohide: true, delay: 3000 }); // Masquer le toast après 3 secondes
                $('.toast').toast('show');

                // Supprimer la ligne du commentaire du DOM
                $commentRow.remove();
            },
            error: function() {
                alert('Une erreur s\'est produite lors de la validation du commentaire');
            }
        });
    });
});
