/**
 * Phyzioline Clinic System - Form UX Enhancements
 * Provides: Auto-focus, inline validation, Save & Continue, better navigation
 */

(function() {
    'use strict';

    /**
     * Auto-focus on first input field
     */
    function autoFocusFirstInput() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            // Skip if form already has focus or is hidden
            if (form.offsetParent === null) return;
            
            const firstInput = form.querySelector('input[type="text"], input[type="email"], input[type="tel"], input[type="number"], input[type="date"], textarea, select');
            if (firstInput && !firstInput.disabled && !firstInput.readOnly) {
                // Small delay to ensure form is visible
                setTimeout(() => {
                    firstInput.focus();
                }, 100);
            }
        });
    }

    /**
     * Inline validation - real-time feedback
     */
    function setupInlineValidation() {
        const forms = document.querySelectorAll('form[data-inline-validation="true"]');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, textarea, select');
            
            inputs.forEach(input => {
                // Skip hidden inputs
                if (input.type === 'hidden') return;
                
                // Validate on blur
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                // Clear errors on input (for better UX)
                input.addEventListener('input', function() {
                    clearFieldError(this);
                });
            });
        });
    }

    /**
     * Validate a single field
     */
    function validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        const fieldType = field.type;
        const isRequired = field.hasAttribute('required');
        
        // Clear previous errors
        clearFieldError(field);
        
        // Required field validation
        if (isRequired && !value) {
            showFieldError(field, 'This field is required');
            return false;
        }
        
        // Email validation
        if (fieldType === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                showFieldError(field, 'Please enter a valid email address');
                return false;
            }
        }
        
        // Phone validation (Egyptian format)
        if (fieldName === 'phone' && value) {
            const phoneRegex = /^(01)[0-9]{9}$/;
            if (!phoneRegex.test(value.replace(/\s+/g, ''))) {
                showFieldError(field, 'Please enter a valid Egyptian phone number (01xxxxxxxxx)');
                return false;
            }
        }
        
        // Number validation
        if (fieldType === 'number' && value) {
            const numValue = parseFloat(value);
            const min = parseFloat(field.getAttribute('min'));
            const max = parseFloat(field.getAttribute('max'));
            
            if (isNaN(numValue)) {
                showFieldError(field, 'Please enter a valid number');
                return false;
            }
            
            if (min !== null && numValue < min) {
                showFieldError(field, `Value must be at least ${min}`);
                return false;
            }
            
            if (max !== null && numValue > max) {
                showFieldError(field, `Value must be at most ${max}`);
                return false;
            }
        }
        
        // Date validation
        if (fieldType === 'date' && value) {
            const date = new Date(value);
            const maxDate = field.getAttribute('max');
            
            if (isNaN(date.getTime())) {
                showFieldError(field, 'Please enter a valid date');
                return false;
            }
            
            if (maxDate && new Date(value) > new Date(maxDate)) {
                showFieldError(field, `Date must be before ${maxDate}`);
                return false;
            }
        }
        
        // Show success state
        showFieldSuccess(field);
        return true;
    }

    /**
     * Show field error
     */
    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');
        
        // Remove existing error message
        const existingError = field.parentElement.querySelector('.invalid-feedback-live');
        if (existingError) {
            existingError.remove();
        }
        
        // Add error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback-live';
        errorDiv.textContent = message;
        field.parentElement.appendChild(errorDiv);
    }

    /**
     * Show field success
     */
    function showFieldSuccess(field) {
        if (field.value.trim()) {
            field.classList.add('is-valid');
            field.classList.remove('is-invalid');
        }
    }

    /**
     * Clear field error
     */
    function clearFieldError(field) {
        field.classList.remove('is-invalid');
        const errorDiv = field.parentElement.querySelector('.invalid-feedback-live');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    /**
     * Save & Continue functionality
     */
    function setupSaveAndContinue() {
        const forms = document.querySelectorAll('form[data-save-continue="true"]');
        
        forms.forEach(form => {
            const saveContinueBtn = form.querySelector('.btn-save-continue');
            if (!saveContinueBtn) return;
            
            saveContinueBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Validate form
                if (!validateForm(form)) {
                    return;
                }
                
                // Add hidden input to indicate save & continue
                let hiddenInput = form.querySelector('input[name="_save_continue"]');
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = '_save_continue';
                    form.appendChild(hiddenInput);
                }
                hiddenInput.value = '1';
                
                // Submit form
                form.submit();
            });
        });
    }

    /**
     * Validate entire form
     */
    function validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
        
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
        
        return isValid;
    }

    /**
     * Keyboard navigation improvements
     */
    function setupKeyboardNavigation() {
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + Enter to submit form
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                const form = document.activeElement.closest('form');
                if (form) {
                    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        e.preventDefault();
                        submitBtn.click();
                    }
                }
            }
            
            // Escape to clear form (with confirmation)
            if (e.key === 'Escape') {
                const form = document.activeElement.closest('form');
                if (form && form.querySelector('input[value], textarea[value]')) {
                    if (confirm('Clear all form data?')) {
                        form.reset();
                        autoFocusFirstInput();
                    }
                }
            }
        });
    }

    /**
     * Form progress indicator
     */
    function setupFormProgress() {
        const forms = document.querySelectorAll('form[data-show-progress="true"]');
        
        forms.forEach(form => {
            const requiredFields = form.querySelectorAll('input[required], textarea[required], select[required]');
            const totalFields = requiredFields.length;
            
            if (totalFields === 0) return;
            
            // Create progress bar
            const progressBar = document.createElement('div');
            progressBar.className = 'form-progress mb-3';
            progressBar.innerHTML = `
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
                <small class="text-muted"><span class="progress-text">0</span> of ${totalFields} required fields completed</small>
            `;
            form.insertBefore(progressBar, form.firstChild);
            
            // Update progress
            function updateProgress() {
                let completed = 0;
                requiredFields.forEach(field => {
                    if (field.value.trim()) {
                        completed++;
                    }
                });
                
                const percentage = (completed / totalFields) * 100;
                progressBar.querySelector('.progress-bar').style.width = percentage + '%';
                progressBar.querySelector('.progress-text').textContent = completed;
            }
            
            // Listen to all required fields
            requiredFields.forEach(field => {
                field.addEventListener('input', updateProgress);
                field.addEventListener('change', updateProgress);
            });
            
            // Initial update
            updateProgress();
        });
    }

    /**
     * Initialize all enhancements
     */
    function init() {
        // Auto-focus
        autoFocusFirstInput();
        
        // Inline validation
        setupInlineValidation();
        
        // Save & Continue
        setupSaveAndContinue();
        
        // Keyboard navigation
        setupKeyboardNavigation();
        
        // Form progress
        setupFormProgress();
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

