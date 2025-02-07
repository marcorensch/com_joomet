let translationStopped = false;

function checkRequiredFields(requiredFields) {
    let areAllFieldsValid = true;
    requiredFields.forEach(field => {
        const isValid = checkRequiredField(field);
        areAllFieldsValid = isValid && areAllFieldsValid;
    });
    return areAllFieldsValid;
}

function checkRequiredField(requiredField) {
    const isValid = !!requiredField.value;
    toggleInvalidFieldClass(requiredField, isValid);
    return isValid;
}

function toggleInvalidFieldClass(field, isValid) {
    if (isValid) {
        field.classList.remove('invalid-field', 'invalid');
    } else {
        field.classList.add('invalid-field', 'invalid');
    }
}

function setSubmitActivityState(startTranslationButton, isActive) {
    startTranslationButton.disabled = !isActive;
}

// initiates the translation
// list of rows stored in "rowsToTranslate" which is defined in the global scope within the tmpl/default.php
async function handleStartTranslationClicked(event) {
    event.preventDefault();
    translationStopped = false;
    const startTranslationBtn = event.currentTarget;
    startTranslationBtn.disabled = true;

    // Make Stop Btn clickable
    const stopTranslationButton = document.querySelector('button#nxd-stop-translation-btn');
    stopTranslationButton.disabled = false;

    const sourceLanguage = document.querySelector('select#jform_source_language')?.firstChild?.value;
    const targetLanguage = document.querySelector('select#jform_target_language')?.firstChild?.value;
    const formality = parseInt(document.querySelector('input[name="jform[use_formality]"]:checked')?.value) === 1;
    const progressBar = document.querySelector('#nxd-translation-progress-bar');
    const $currentStringPreview = document.querySelector('#nxd-current-string-preview');

    // Reset Progressbar
    progressBar.textContent = "0%";
    progressBar.style.width = '0';


    if (sourceLanguage == null || targetLanguage == null) {
        Joomla.renderMessages({error: ["Please select a source and target language"]})
        return;
    }

    if (rowsToTranslate == null) return;

    let i = 0;

    for (const row of rowsToTranslate) {
        i++;

        // check if skip is enabled
        $skipCheckbox = document.querySelector(`input#jform_skip_element_${row.rowNum}_1`);
        if ($skipCheckbox && $skipCheckbox.checked) {
            console.log("Skip element", row.rowNum);
            placeTranslation(row, row.content);
            updateProgressBar(progressBar, i);
            continue;
        }

        const data = {
            sourceLanguage,
            targetLanguage,
            formality,
            content: row.content,
            rowNum: row.rowNum
        }

        $currentStringPreview.textContent = row.content;

        try {
            const status = await translateRow(data)
            if (!status || translationStopped) {
                break;
            }
        }catch (error){
            console.error("Translation failed", error);
            Joomla.renderMessages({
                error: ["Translation failed", `Details: ${error.responseText || error}`]
            });
            break;
        }

        updateProgressBar(progressBar, i);
    }

    startTranslationBtn.disabled = false;
    stopTranslationButton.disabled = true;
    $currentStringPreview.textContent = "";

}

function updateProgressBar(progressBar, i) {
    const percentages = Math.ceil(i / rowsToTranslate.length * 100);
    progressBar.textContent = percentages + "%";
    progressBar.style.width = percentages + "%";
}

function handleStopTranslationClicked(event) {
    event.preventDefault();
    const btn = event.currentTarget;
    btn.disabled = true;
    translationStopped = true;
}

// the AJAX Call to the backend
function translateRow(rowData) {

    return jQuery.ajax({
        url: 'index.php',
        data: {
            option: 'com_joomet',
            task: 'deeplagent.doTranslation',
            format: 'json',
            [Joomla.getOptions('csrf.token')]: 1,
            rowData: JSON.stringify(rowData)
        },
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            console.log(response);
            if(response.success){
                console.log('Translation successful');
                if(response.translation) {
                    placeTranslation(rowData, response.translation);
                    return true;
                }else {
                    console.log('No translation found', response);
                    return false;
                }
            } else {
                console.log('Translation failed');
                return false;
            }
        },
        error: function (xhr, status, error) {
            return false;
        }
    })
}

function placeTranslation(rowData, translation) {
    const $textareaElement = document.querySelector(`textarea#jform_translation_editor_${rowData.rowNum}`);
    const editor = Joomla.editors.instances['jform_translation_editor_' + rowData.rowNum];
    if(editor) {
        console.log("Editor found, updating content");
        editor.setValue(translation);
    }
    if($textareaElement) {

        $textareaElement.dispatchEvent(new Event('change'));
    }else {
        console.error("No textarea found for row", rowData.rowNum);
        $textareaElement.value = translation;
    }
}

function setUseFormalityState(formalityFieldset) {
    const switcher = formalityFieldset.querySelector('.switcher');
    const disabledOption = formalityFieldset.querySelector('input#jform_use_formality0');
    const enabledOption = formalityFieldset.querySelector('input#jform_use_formality1');
    const selectedTargetLanguageField = document.querySelector('select#jform_target_language');
    if (selectedTargetLanguageField.firstChild) {
        const selectedTargetLanguageCode = selectedTargetLanguageField.firstChild.value;
        if (selectedTargetLanguageCode) {
            const formalityOption = formalitySupportTable[selectedTargetLanguageCode];
            if (formalityOption) {
                switcher.classList.remove('disabled');
                formalityFieldset.disabled = false;
                return;
            }
        }
    }

    disabledOption.checked = true;
    enabledOption.checked = false;
    switcher.classList.add('disabled');
    formalityFieldset.disabled = true;
}

function handleMutateAllFields(enabled)
{
    const elementsToSkip = document.querySelectorAll('.nxd-skip-element-checkbox');
    console.log(elementsToSkip)
    for (const el of elementsToSkip) {
        if(el.value == enabled) {
            el.checked = true;
        }else{
            el.checked = false;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const requiredFields = document.querySelectorAll('input.required, select.required');
    const startTranslationButton = document.querySelector('button#nxd-start-translation-btn');
    const stopTranslationButton = document.querySelector('button#nxd-stop-translation-btn');
    const formalityFieldset = document.querySelector('fieldset#jform_use_formality');
    const $skipAllToggler = document.querySelector('#jform_skip_all_toggler');
    const $skipAllTogglerActive = document.querySelector('#jform_skip_all_toggler1');
    const $skipAllTogglerInactive = document.querySelector('#jform_skip_all_toggler0');

    $skipAllTogglerActive.addEventListener('change', () => handleMutateAllFields(true))
    $skipAllTogglerInactive.addEventListener('change', () => handleMutateAllFields(false))

    // Initial Status for Stop Btn
    stopTranslationButton.disabled = true;

    const updateFormState = () => {
        const allFieldsValid = checkRequiredFields(requiredFields);
        setSubmitActivityState(startTranslationButton, allFieldsValid);
        setUseFormalityState(formalityFieldset)
    };

    updateFormState();

    requiredFields.forEach(field => {
        field.addEventListener('change', updateFormState);
    });

    startTranslationButton.addEventListener('click', handleStartTranslationClicked);
    stopTranslationButton.addEventListener('click', handleStopTranslationClicked);
});