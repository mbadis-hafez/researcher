'use strict';
const formAuthentication = document.querySelector('#formAuthentication');

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    if (formAuthentication) {
      const fv = FormValidation.formValidation(formAuthentication, {
        fields: {
          username: {
            validators: {
              notEmpty: {
                message: 'Please enter your full name'
              },
              stringLength: {
                min: 2,
                message: 'Name must be at least 2 characters'
              }
            }
          },
          email: {
            validators: {
              notEmpty: {
                message: 'Please enter your email'
              },
              emailAddress: {
                message: 'Please enter a valid email address'
              }
            }
          },
          job: {
            validators: {
              notEmpty: {
                message: 'Please enter your job title'
              },
              stringLength: {
                min: 2,
                message: 'Job title must be at least 2 characters'
              }
            }
          },
          phone: {
            validators: {
              notEmpty: {
                message: 'Please enter your phone number'
              },
              stringLength: {
                min: 6,
                message: 'Phone number must be at least 6 digits'
              }
            }
          },
          business_type: {
            validators: {
              notEmpty: {
                message: 'Please select your business type'
              },
              callback: {
                message: 'Please specify your business type',
                callback: function(input) {
                  // If "Other" is selected, check if the other field is filled
                  if (input.value === 'other') {
                    const otherInput = document.getElementById('other_business_type');
                    return otherInput.value.trim() !== '';
                  }
                  return true;
                }
              }
            }
          },
          other_business_type: {
            validators: {
              callback: {
                message: 'Please specify your business type',
                callback: function(input) {
                  // Only validate if "Other" is selected
                  const businessType = document.getElementById('business_type');
                  if (businessType.value === 'other') {
                    return input.value.trim() !== '';
                  }
                  return true;
                }
              }
            }
          },
          terms: {
            validators: {
              notEmpty: {
                message: 'Please agree to terms & conditions'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.mb-6'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('plugins.message.placed', function (e) {
            if (e.element.parentElement.classList.contains('input-group')) {
              e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        }
      });
    }
  })();
});