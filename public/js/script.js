/**
 * This script enhances the job posting form with interactive features and client-side validation.
 */
'use strict';


document.addEventListener('DOMContentLoaded', () => {

    //  Constants and Utilities
    const WORD_LIMIT = 1000;
    const WARNING_THRESHOLD = 900;
    const getWordCount = (text) => text.trim() === '' ? 0 : text.trim().split(/\s+/).length;

    //  Word Counter
    const scriptTextarea = document.getElementById('script');
    const wordCountEl = document.getElementById('word-count');
    const wordWarningEl = document.getElementById('word-warning');

    if (scriptTextarea && wordCountEl) {

        const updateWordCount = () => {
            const words = getWordCount(scriptTextarea.value);
            const isWarning = words > WARNING_THRESHOLD;
            const isExceeded = words > WORD_LIMIT;

            // Update word count display
            wordCountEl.textContent = `${words} word${words !== 1 ? 's' : ''}`;
            wordCountEl.classList.toggle('form-group__counter--warning', isWarning);

            // Update warning message
            if (wordWarningEl) {
                if (isExceeded) {
                    wordWarningEl.textContent = `Script exceeds 1000 word limit (${words} words). Please remove ${words - WORD_LIMIT} word${words - WORD_LIMIT !== 1 ? 's' : ''}.`;
                    wordWarningEl.classList.remove('word-warning--near');
                    wordWarningEl.classList.add('word-warning--exceeded');
                    wordWarningEl.style.display = 'block';
                } else if (isWarning) {
                    wordWarningEl.textContent = `Approaching limit: ${WORD_LIMIT - words} word${WORD_LIMIT - words !== 1 ? 's' : ''} remaining`;
                    wordWarningEl.classList.remove('word-warning--exceeded');
                    wordWarningEl.classList.add('word-warning--near');
                    wordWarningEl.style.display = 'block';
                } else {
                    wordWarningEl.style.display = 'none';
                    wordWarningEl.classList.remove('word-warning--near', 'word-warning--exceeded');
                }
            }

            // Update aria label for screen readers
            const status = isExceeded ? 'exceeds limit' : isWarning ? 'approaching limit' : '';
            wordCountEl.setAttribute('aria-label', `Script: ${words} word${words !== 1 ? 's' : ''}${status ? ` (${status})` : ''}`);
        };

        scriptTextarea.addEventListener('input', updateWordCount);
        updateWordCount(); // Run on load for browser autofill/old values
    }


    //  Dynamic Province / State

    const countrySelect   = document.getElementById('country');
    const provinceSelect  = document.getElementById('state_or_province');

    if (countrySelect && provinceSelect && window.PROVINCES) {

        const populateProvinces = (countryCode, selectedValue = '') => {
            // Clear existing options
            provinceSelect.innerHTML = '';

            // Default placeholder
            const placeholder = new Option('Select your state/province', '', true, false);
            placeholder.disabled = true;
            provinceSelect.appendChild(placeholder);

            if (!countryCode || !window.PROVINCES[countryCode]) {
                provinceSelect.disabled = true;
                return;
            }

            provinceSelect.disabled = false;

            const regions = window.PROVINCES[countryCode];
            Object.entries(regions).forEach(([code, name]) => {
                const opt      = new Option(name, code);
                opt.selected   = (code === selectedValue);
                provinceSelect.appendChild(opt);
            });
        };

        // Populate on country change
        countrySelect.addEventListener('change', () => {
            populateProvinces(countrySelect.value);

            // let the screen readers know that the province list updated
            provinceSelect.setAttribute('aria-label',
                `State or province for ${countrySelect.options[countrySelect.selectedIndex].text}`
            );
        });

        // On page load: if a country is pre-selected
        // populate provinces and re-select the saved state
        if (countrySelect.value) {
            const savedProvince = provinceSelect.dataset.selected || '';
            populateProvinces(countrySelect.value, savedProvince);
        } else {
            provinceSelect.disabled = true;
        }
    }



    //  Client-Side Validation

    const form = document.getElementById('job-form');

    if (form) {
        // Define button references early so they're available for all handlers
        const submitBtn = form.querySelector('button[type="submit"]');
        const resetBtn = form.querySelector('button[type="reset"]');
        const originalSubmitText = submitBtn?.textContent || 'Submit';

        // Cache form field references to avoid repeated querying
        const jobTitle = document.getElementById('title');
        const country = document.getElementById('country');
        const province = document.getElementById('state_or_province');
        const budgetRadios = form.querySelectorAll('input[name="budget"]');

        // Track validation state to prevent double-submit handler from running on validation errors
        let isValidationPassed = false;

        // Helper function to check if all required fields are filled
        const checkFormValidity = () => {
            const budgetSelected = form.querySelector('input[name="budget"]:checked');

            const isValid =
                jobTitle && jobTitle.value.trim() !== '' &&
                country && country.value !== '' &&
                province && province.value !== '' &&
                budgetSelected;

            // Disable/enable submit button based on validity
            if (submitBtn) {
                if (isValid && !submitBtn.classList.contains('btn--loading')) {
                    submitBtn.disabled = false;
                } else if (!isValid) {
                    submitBtn.disabled = true;
                }
            }
        };

        // Check form validity on page load
        checkFormValidity();

        if (jobTitle) jobTitle.addEventListener('input', checkFormValidity);
        if (country) country.addEventListener('change', checkFormValidity);
        if (province) province.addEventListener('change', checkFormValidity);
        budgetRadios.forEach(radio => radio.addEventListener('change', checkFormValidity));

        form.addEventListener('submit', (e) => {
            // Reset validation flag at start of submit
            isValidationPassed = false;
            const errors = [];

            const jobTitle = document.getElementById('title');
            if (jobTitle && jobTitle.value.trim() === '') {
                errors.push({ field: jobTitle, message: 'Job title is required.' });
            }

            const country = document.getElementById('country');
            if (country && country.value === '') {
                errors.push({ field: country, message: 'Please select a country.' });
            }

            const province = document.getElementById('state_or_province');
            if (province && province.value === '') {
                errors.push({ field: province, message: 'Please select a state or province.' });
            }

            const budgetSelected = form.querySelector('input[name="budget"]:checked');
            if (!budgetSelected) {
                const budgetGroup = form.querySelector('.budget-options');
                errors.push({ field: budgetGroup, message: 'Please select a budget range.' });
            }

            // Script/textarea word count validation (optional field but if provided must not exceed 1000 words)
            const scriptTextarea = document.getElementById('script');
            if (scriptTextarea && scriptTextarea.value.trim() !== '') {
                const words = getWordCount(scriptTextarea.value);
                if (words > WORD_LIMIT) {
                    alert(`Script exceeds 1000 word limit (${words} words). Please shorten your script.`);
                    errors.push({ field: scriptTextarea, message: `Script exceeds 1000 word limit (${words} words).` });
                }
            }

            // File size validation (optional field but if provided must be within size limit)
            const fileInput = document.getElementById('reference_file_path');
            if (fileInput && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const MAX_SIZE = 20 * 1024 * 1024; // 20MB

                if (file.size > MAX_SIZE) {
                    const formatBytes = (bytes) => {
                        if (bytes === 0) return '0 B';
                        const k = 1024;
                        const sizes = ['B', 'KB', 'MB'];
                        const i = Math.floor(Math.log(bytes) / Math.log(k));
                        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                    };
                    errors.push({ field: fileInput, message: `File too large (${formatBytes(file.size)}). Maximum is 20 MB.` });
                }
            }

            if (errors.length > 0) {
                e.preventDefault();

                // Clear previous inline errors added by JS
                form.querySelectorAll('.js-error').forEach(el => el.remove());

                errors.forEach(({ field, message }) => {
                    // Special handling for textarea (script field)
                    if (field.id === 'script') {
                        const wordCountEl = document.getElementById('word-count');
                        if (wordCountEl) {
                            const errorEl = document.createElement('div');
                            errorEl.className = 'error js-error';
                            errorEl.setAttribute('role', 'alert');
                            errorEl.textContent = message;
                            wordCountEl.parentElement.appendChild(errorEl);
                        }
                    } else {
                        // For other fields, find closest container
                        const container = field.closest('.form-group') ||
                                       field.closest('fieldset') ||
                                       field.closest('.budget-options') ||
                                       field.parentElement;

                        if (container) {
                            const errorEl = document.createElement('div');
                            errorEl.className = 'error js-error';
                            errorEl.setAttribute('role', 'alert');
                            errorEl.textContent = message;
                            container.appendChild(errorEl);
                            container.classList.add('field--error');
                        }
                    }
                });

                // Focus the first errored field
                errors[0].field.focus?.();

                // Reset submit button if there are validation errors
                if (submitBtn) {
                    submitBtn.classList.remove('btn--loading');
                    submitBtn.removeAttribute('aria-disabled');
                    submitBtn.textContent = originalSubmitText;
                    // Re-check form validity to determine if button should be enabled
                    checkFormValidity();
                }
            } else {
                // No validation errors - mark validation as passed
                isValidationPassed = true;
            }
        });

        // Prevent Double Submit
        // Disable the submit button after first click to prevent
        // accidental duplicate submissions while the server responds.

        if (submitBtn) {
            form.addEventListener('submit', () => {
                // Only add loading state if validation passed
                if (isValidationPassed && !submitBtn.disabled) {
                    // Small delay so client-side validation runs first
                    setTimeout(() => {
                        submitBtn.classList.add('btn--loading');
                        submitBtn.setAttribute('aria-disabled', 'true');
                        submitBtn.textContent = 'Submitting';
                        submitBtn.disabled = true;
                    }, 10);
                }
            });
        }

        // ── Reset Handler
        // Clear JS-added errors and loading state when reset is clicked

        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                // Reset validation flag
                isValidationPassed = false;

                // Clear all JS-added error messages
                form.querySelectorAll('.js-error').forEach(el => el.remove());

                // Remove error styling from all fields
                form.querySelectorAll('.field--error').forEach(el => {
                    el.classList.remove('field--error');
                });

                // Clear submit button loading state
                if (submitBtn) {
                    submitBtn.classList.remove('btn--loading');
                    submitBtn.removeAttribute('aria-disabled');
                    submitBtn.textContent = originalSubmitText;
                }

                // Re-check form validity after reset (fields will be empty)
                setTimeout(() => {
                    checkFormValidity();
                }, 0);
            });
        }
    }

});
