$(document).ready(function() {
    $('.validate-comment').click(function() {
        var commentId = $(this).data('comment-id');
        var $commentRow = $(this).closest('tr');

        $.ajax({
            type: 'POST',
            url: '/comment/validate/' + commentId,
            success: function(response) {
                $('#toast-container').append('<div class="toast" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header"><strong class="mr-auto">Confirmation</strong></div><div class="toast-body">' + response.message + '</div></div>');
                $('.toast').toast({ autohide: true, delay: 5000 });
                $('.toast').toast('show');
                $commentRow.remove();
            },
            error: function(response) {
                $('#toast-container').append('<div class="toast" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header"><strong class="mr-auto">Erreur</strong></div><div class="toast-body">' + response.message + '</div></div>');
                $('.toast').toast({ autohide: true, delay: 5000 });
                $('.toast').toast('show');
            }
        });
    });
    $('.delete-comment').click(function() {
        var commentId = $(this).data('comment-id');
        var csrfToken = $(this).data('csrf-token');
        var $commentRow = $(this).closest('tr');

        $.ajax({
            type: 'POST',
            url: '/comment/delete/' + commentId,
            data: {
                _token: csrfToken
            },
            success: function(response) {
                $('#toast-container').append('<div class="toast" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header"><strong class="mr-auto">Confirmation</strong></div><div class="toast-body">' + response.message + '</div></div>');
                $('.toast').toast({ autohide: true, delay: 5000 });
                $('.toast').toast('show');
                $commentRow.remove();
            },
            error: function(response) {
                $('#toast-container').append('<div class="toast" role="alert" aria-live="assertive" aria-atomic="true"><div class="toast-header"><strong class="mr-auto">Erreur</strong></div><div class="toast-body">' + response.message + '</div></div>');
                $('.toast').toast({ autohide: true, delay: 5000 });
                $('.toast').toast('show');
            }
        });
    });
});
