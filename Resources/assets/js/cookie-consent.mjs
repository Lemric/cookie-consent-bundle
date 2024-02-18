document.addEventListener("DOMContentLoaded", function () {
    const cookieConsent = document.querySelector('.cookie-consent');
    const cookieConsentForm = document.querySelector('.cookie-consent__form');

    if (cookieConsentForm) {
        // we got a form
        const submitButtons = cookieConsentForm.querySelectorAll('.js-submit-cookie-consent-form');
        const formAction = cookieConsentForm.action || location.href;

        cookieConsentForm.addEventListener('submit', function (event) {
            event.preventDefault();
        });

        const cookieConsentDialog = document.querySelector('.cookie-consent-dialog');
        if (cookieConsentDialog) {
            // we got a dialog, show it
            cookieConsentDialog.showModal();
        }

        cookieConsentForm.querySelectorAll('.js-reject-all-cookies').forEach(function (rejectButton) {
            rejectButton.addEventListener('click', function (event) {
                // reject all was clicked
                // parse form and send information about rejection to set only minimal cookies
                fetch(formAction, {
                    method: 'POST',
                    body: new FormData(cookieConsentForm, rejectButton)
                }).then(function (res) {
                    if (res.status >= 200 && res.status < 300) {
                        hideCookieConsentForm(cookieConsent, cookieConsentDialog);
                        dispatchSuccessEvent(rejectButton);
                    }
                }).catch(function (error) {
                    console.error('Error:', error);
                });
            });
        });

        // Submit form via ajax
        submitButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                document.querySelector('.js-reject-all-cookies').disabled = true;

                fetch(formAction, {
                    method: 'POST',
                    body: new FormData(cookieConsentForm, event.target)
                }).then(function (res) {
                    if (res.status >= 200 && res.status < 300) {
                        hideCookieConsentForm(cookieConsent, cookieConsentDialog);
                        dispatchSuccessEvent(event.target);
                    }
                }).catch(function (error) {
                    console.error('Error:', error);
                });
            }, false);
        });
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