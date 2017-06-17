;(function($) {

    $(document).ready(function() {

        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $("#delete-user").click(function() {
            if (!confirm("Confirmar exclusão da conta?")) {
                return false;
            }
        })
    })
})(jQuery);
