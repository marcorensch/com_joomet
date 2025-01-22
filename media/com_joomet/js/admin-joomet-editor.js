document.addEventListener('DOMContentLoaded', function() {
    const observer = new MutationObserver(function(mutations) {
        const cmEditor = document.querySelector('.cm-editor'); // Joomla CSS-Klasse überprüfen
        if (cmEditor) {
            console.log('CodeMirror ist geladen:', cmEditor);
            observer.disconnect(); // Beenden, wenn geladen

            const containerHeight = cmEditor.closest('.container-main').clientHeight;
            const headerHeight = document.querySelector('#subhead-container').clientHeight;
            cmEditor.style.height = containerHeight - (headerHeight * 2) + 'px';
        }
    });

    observer.observe(document.body, { childList: true, subtree: true });
});