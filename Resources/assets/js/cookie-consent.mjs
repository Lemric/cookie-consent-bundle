document.addEventListener("DOMContentLoaded", function () {
    const cookieConsent = document.querySelector('.cookie-consent');
    const cookieConsentForm = document.querySelector('.cookie-consent__form');
    const cookieConsentFormBtn = document.querySelectorAll('.js-submit-cookie-consent-form');
    if (cookieConsentForm) {
        const formAction = cookieConsentForm.action ? cookieConsentForm.action : location.href;

        const cookieConsentDialog = document.querySelector('.cookie-consent-dialog');
        if (cookieConsentDialog) {
            cookieConsentDialog.showModal();

            const saveButton = cookieConsentDialog.querySelector('#cookie_consent_save');
            if (saveButton) {
                saveButton.addEventListener('click', function () {
                    cookieConsentDialog.close();
                });
            }

            cookieConsentDialog.querySelectorAll('.js-reject-all-cookies').forEach(function (rejectButton) {
                rejectButton.addEventListener('click', function () {
                    // parse form and send information about rejection to set only minimal cookies
                    fetch(formAction, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: [getCsrfToken(cookieConsentForm), encodeURIComponent(rejectButton.getAttribute('name')) + "="].join('&')
                    }).then(function (res) {
                        cookieConsentDialog.close();
                        cookieConsent.remove();
                    }).catch(function (error) {
                        console.error('Error:', error);
                    });
                });
            });
        }

        // Submit form via ajax
        cookieConsentFormBtn.forEach(function (btn) {
            btn.addEventListener('click', function (event) {
                event.preventDefault();

                document.querySelector('.js-reject-all-cookies').disabled = true;

                fetch(formAction, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: serializeForm(cookieConsentForm, event.target)
                }).then(function (res) {
                    if (res.status >= 200 && res.status < 300) {
                        if (cookieConsentDialog) {
                            cookieConsentDialog.close();
                        } else {
                            cookieConsent.remove();
                        }

                        const formSubmittedEvent = new CustomEvent('cookie-consent-form-submit-successful', {
                            detail: event.target
                        });
                        document.dispatchEvent(formSubmittedEvent);
                    }
                }).catch(function (error) {
                    console.error('Error:', error);
                });
            }, false);
        });
    }
});

function getCsrfToken(cookieConsentForm) {
    let csrfTokenField = cookieConsentForm.querySelector('#cookie_consent__token');
    return encodeURIComponent(csrfTokenField.getAttribute('name')) + '=' + encodeURIComponent(csrfTokenField.getAttribute('value'));
}

function serializeForm(form, clickedButton) {
    const serialized = [];

    Array.from(form.elements).forEach(function (field) {
        if (field.classList.contains('js-cookie-consent-form-element') && field.checked) {
            serialized.push(encodeURIComponent(field.name) + "=" + encodeURIComponent(field.value));
        }
    });

    serialized.push(encodeURIComponent(clickedButton.getAttribute('name')) + "=");
    serialized.push(getCsrfToken(form));

    return serialized.join('&');
}