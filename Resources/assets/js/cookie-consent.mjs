document.addEventListener("DOMContentLoaded", function () {
    const cookieConsent = document.querySelector('.cookie-consent');
    const cookieConsentForm = document.querySelector('.cookie-consent__form');

    if (cookieConsentForm) {
        // we got a form
        const submitButtons = cookieConsentForm.querySelectorAll('.js-submit-cookie-consent-form');
        const formAction = cookieConsentForm.action || location.href;

        cookieConsentForm.addEventListener('submit', function (event) {
            event.preventDefault();

            fetch(formAction, {
                method: 'POST',
                body: new FormData(cookieConsentForm, event.submitter)
            }).then(function (res) {
                if (res.status >= 200 && res.status < 300) {
                    hideCookieConsentForm(cookieConsent, cookieConsentDialog);
                    dispatchSuccessEvent(event.submitter);
                }
            }).catch(function (error) {
                console.error('Error:', error);
            });
        });

        const cookieConsentDialog = document.querySelector('.cookie-consent-dialog');
        if (cookieConsentDialog) {
            // we got a dialog, show it
            cookieConsentDialog.showModal();
        }
    }
});

function dispatchSuccessEvent(submitter) {
    const formSubmittedEvent = new CustomEvent('cookie-consent-form-submit-successful', {
        detail: submitter
    });
    document.dispatchEvent(formSubmittedEvent);
}

function hideCookieConsentForm(cookieConsent, cookieConsentDialog) {
    if (cookieConsentDialog) {
        cookieConsentDialog.close();
    }
    cookieConsent.remove();
}