import mammoth from 'mammoth';

document.getElementById('word-import')?.addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();

    reader.onload = function (loadEvent) {
        const arrayBuffer = loadEvent.target.result;

        mammoth.convertToHtml({ arrayBuffer: arrayBuffer })
            .then(function (result) {
                const htmlContent = result.value;

                if (window.tinymce && tinymce.get('mon-editeur')) {
                    tinymce.get('mon-editeur').setContent(htmlContent);
                    tinymce.get('mon-editeur').save();
                }
            })
            .catch(function (err) {
                alert("Erreur lors de la lecture du fichier Word : " + err.message);
            });
    };

    reader.readAsArrayBuffer(file);
});