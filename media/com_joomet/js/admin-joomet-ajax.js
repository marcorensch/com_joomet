(function($) {
    $(document).ready(function () {
        $('#get-languages-btn').on('click', function (e) {
            e.preventDefault();

            console.log("Btn clicked")

            // AJAX-Aufruf
            $.ajax({
                url: 'index.php',
                data: {
                    option: 'com_joomet',
                    task: 'deeplagent.getLanguagesFromDeepl',
                    format: 'json',
                    [Joomla.getOptions('csrf.token')]: 1
                },
                type: 'GET',
                success: function (response) {
                    const data = JSON.parse(response);

                    if (data.success) {
                        console.log('Sprachen:', data.languages);
                        // Verarbeiten Sie hier die Rückgabe der Sprachen
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX-Fehler:', error);
                }
            });
        });
    });
})(jQuery);