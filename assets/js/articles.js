$(document).ready(function() {
    $('.delete-button').click(function() {
        var articleId = $(this).data('id');
        var csrfToken = $(this).data('csrf-token');

        $('#confirm-delete-btn').data('id', articleId);
        $('#confirm-delete-btn').data('token', csrfToken);

        console.log('Article ID to delete:', articleId);
    });

    $('#confirm-delete-btn').click(function() {
        var articleId = $(this).data('id');
        var csrfToken = $(this).data('token');

        $.ajax({
            type: 'POST',
            url: `/admin/article/delete/${articleId}`,
            data: {
                _token: csrfToken
            },
            success: function(response) {
                if (response.message === "Article was deleted successfully.") {
                    showToast(response.message);
                    $('button[data-id="' + articleId + '"]').closest('tr').remove();
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function(xhr) {
                showToast("An error occurred.", 'error');
            }
        });
    });

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
        var $toast = $('#toast-container .toast:last');
        $toast.toast({ autohide: true, delay: 5000 });
        $toast.toast('show');
    }
});
