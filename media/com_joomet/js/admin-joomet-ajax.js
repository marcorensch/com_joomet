(function($) {
    $(document).ready(function () {
        $('#get-languages-btn').on('click', function (e) {
            e.preventDefault();

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
                        const textarea = document.querySelector("textarea[data-deepl-languages-field]");
                        textarea.value = JSON.stringify(data.languages);

                        // Update the both list textfields for source and target:
                        const sourceLanguagesTextarea = document.querySelector("#source");
                        const targetLanguagesTextArea = document.querySelector("#target");
                        sourceLanguagesTextarea.value = "";
                        targetLanguagesTextArea.value = "";

                        for (let i = 0; i < data.languages.source.length; i++) {
                            sourceLanguagesTextarea.value += data.languages.source[i].name + " (" + data.languages.source[i].code + ")";
                            if(i < data.languages.source.length - 1)
                                sourceLanguagesTextarea.value += ("\n");
                        }

                        for (let i = 0; i < data.languages.target.length; i++) {
                            targetLanguagesTextArea.value += data.languages.target[i].name + " (" + data.languages.target[i].code + ")";
                            if(i < data.languages.target.length - 1)
                                targetLanguagesTextArea.value += ("\n");
                        }

                        Joomla.renderMessages({
                            success: ["Languages successfully fetched from Deepl"]
                        });
                    }
                },
                error: function (xhr, status, error) {
                    const errorMessage = xhr.responseText || "Unknown error occurred";
                    const statusCode = xhr.status;
                    const statusText = xhr.statusText;

                    // Joomla Fehlermeldung anzeigen
                    Joomla.renderMessages({
                        error: [
                            "Could not fetch languages from Deepl.",
                            "Status: " + statusCode + " (" + statusText + ")",
                            "Details: " + errorMessage
                        ]
                    })
                    console.error('AJAX-Error:', error);
                }
            });
        });
    });
})(jQuery);