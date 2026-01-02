/**
 * Phyzioline Clinic System - Tooltips System
 * Provides contextual help tooltips throughout the application
 */

(function() {
    'use strict';

    // Initialize tooltips when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeTooltips();
    });

    /**
     * Initialize all tooltips
     */
    function initializeTooltips() {
        // Bootstrap tooltips (if available)
        if (typeof $ !== 'undefined' && $.fn.tooltip) {
            $('[data-toggle="tooltip"]').tooltip();
        }

        // Custom tooltip system
        const tooltipElements = document.querySelectorAll('[data-help-tooltip]');
        tooltipElements.forEach(function(element) {
            setupCustomTooltip(element);
        });
    }

    /**
     * Setup custom tooltip for an element
     */
    function setupCustomTooltip(element) {
        const tooltipText = element.getAttribute('data-help-tooltip');
        const tooltipPosition = element.getAttribute('data-tooltip-position') || 'top';

        // Create tooltip icon
        const tooltipIcon = document.createElement('i');
        tooltipIcon.className = 'las la-question-circle text-muted ml-1';
        tooltipIcon.style.cursor = 'help';
        tooltipIcon.style.fontSize = '0.9em';

        // Create tooltip container
        const tooltipContainer = document.createElement('span');
        tooltipContainer.className = 'help-tooltip-container position-relative d-inline-block';
        tooltipContainer.appendChild(tooltipIcon);

        // Create tooltip bubble
        const tooltipBubble = document.createElement('div');
        tooltipBubble.className = 'help-tooltip-bubble';
        tooltipBubble.textContent = tooltipText;
        tooltipBubble.style.display = 'none';
        tooltipContainer.appendChild(tooltipBubble);

        // Insert tooltip after element
        element.parentNode.insertBefore(tooltipContainer, element.nextSibling);

        // Show/hide on hover
        tooltipIcon.addEventListener('mouseenter', function() {
            tooltipBubble.style.display = 'block';
            positionTooltip(tooltipBubble, tooltipIcon, tooltipPosition);
        });

        tooltipIcon.addEventListener('mouseleave', function() {
            tooltipBubble.style.display = 'none';
        });
    }

    /**
     * Position tooltip bubble
     */
    function positionTooltip(tooltip, trigger, position) {
        const rect = trigger.getBoundingClientRect();
        const tooltipRect = tooltip.getBoundingClientRect();

        switch(position) {
            case 'top':
                tooltip.style.bottom = '100%';
                tooltip.style.left = '50%';
                tooltip.style.transform = 'translateX(-50%)';
                tooltip.style.marginBottom = '5px';
                break;
            case 'bottom':
                tooltip.style.top = '100%';
                tooltip.style.left = '50%';
                tooltip.style.transform = 'translateX(-50%)';
                tooltip.style.marginTop = '5px';
                break;
            case 'left':
                tooltip.style.right = '100%';
                tooltip.style.top = '50%';
                tooltip.style.transform = 'translateY(-50%)';
                tooltip.style.marginRight = '5px';
                break;
            case 'right':
                tooltip.style.left = '100%';
                tooltip.style.top = '50%';
                tooltip.style.transform = 'translateY(-50%)';
                tooltip.style.marginLeft = '5px';
                break;
        }
    }

    /**
     * Add tooltip to element programmatically
     */
    window.addTooltip = function(element, text, position) {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }
        if (element) {
            element.setAttribute('data-help-tooltip', text);
            if (position) {
                element.setAttribute('data-tooltip-position', position);
            }
            setupCustomTooltip(element);
        }
    };
})();

