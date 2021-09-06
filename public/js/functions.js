window.onbeforeunload = function () {
    if (window.formSubmitting) {
        return undefined;
    }

    return "Are you sure you want to leave this page? Changes you made may not be saved"
}

window.onunload = function() {
    if (window.formSubmitting) {
        return undefined;
    }

    localStorage.setItem('tabClosed', true)
}
