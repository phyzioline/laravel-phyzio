/**
 * Specialty-Specific Assessment Forms
 * Handles dynamic form rendering based on specialty
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeSpecialtyForms();
    });

    /**
     * Initialize specialty-specific form components
     */
    function initializeSpecialtyForms() {
        const specialty = document.querySelector('[data-specialty]')?.getAttribute('data-specialty');
        
        if (!specialty) return;

        // Initialize based on specialty
        switch(specialty) {
            case 'orthopedic':
                initOrthopedicForm();
                break;
            case 'pediatric':
                initPediatricForm();
                break;
            case 'neurological':
                initNeurologicalForm();
                break;
            case 'sports':
                initSportsForm();
                break;
            case 'geriatric':
                initGeriatricForm();
                break;
        }

        // Initialize common components
        initPainScale();
        initROMSliders();
    }

    /**
     * Initialize Pain Scale (VAS) slider
     */
    function initPainScale() {
        const painScaleInputs = document.querySelectorAll('.pain-scale-slider');
        
        painScaleInputs.forEach(function(input) {
            const slider = input;
            const display = input.parentElement.querySelector('.pain-scale-display');
            
            if (slider && display) {
                slider.addEventListener('input', function() {
                    const value = parseInt(slider.value);
                    display.textContent = value;
                    display.className = 'pain-scale-display font-weight-bold';
                    
                    // Color coding
                    if (value <= 3) {
                        display.style.color = '#4caf50'; // Green
                    } else if (value <= 6) {
                        display.style.color = '#ff9800'; // Orange
                    } else {
                        display.style.color = '#f44336'; // Red
                    }
                });
                
                // Trigger initial update
                slider.dispatchEvent(new Event('input'));
            }
        });
    }

    /**
     * Initialize ROM Sliders
     */
    function initROMSliders() {
        const romSliders = document.querySelectorAll('.rom-slider');
        
        romSliders.forEach(function(slider) {
            const display = slider.parentElement.querySelector('.rom-display');
            const normalRange = slider.getAttribute('data-normal-range');
            const normalMin = normalRange ? parseInt(normalRange.split('-')[0]) : null;
            const normalMax = normalRange ? parseInt(normalRange.split('-')[1]) : null;
            
            if (slider && display) {
                slider.addEventListener('input', function() {
                    const value = parseInt(slider.value);
                    display.textContent = value + '°';
                    
                    // Compare with normal range
                    if (normalMin !== null && normalMax !== null) {
                        if (value >= normalMin && value <= normalMax) {
                            display.style.color = '#4caf50'; // Normal range
                        } else {
                            display.style.color = '#f44336'; // Outside normal range
                        }
                    }
                });
                
                slider.dispatchEvent(new Event('input'));
            }
        });
    }

    /**
     * Initialize Orthopedic-specific form
     */
    function initOrthopedicForm() {
        // ROM measurements for different joints
        initJointROM('shoulder', ['flexion', 'extension', 'abduction', 'internal_rotation', 'external_rotation']);
        initJointROM('elbow', ['flexion', 'extension']);
        initJointROM('wrist', ['flexion', 'extension', 'radial_deviation', 'ulnar_deviation']);
        initJointROM('hip', ['flexion', 'extension', 'abduction', 'adduction', 'internal_rotation', 'external_rotation']);
        initJointROM('knee', ['flexion', 'extension']);
        initJointROM('ankle', ['dorsiflexion', 'plantarflexion', 'inversion', 'eversion']);
        
        // Muscle strength grading
        initMMT();
        
        // Postural assessment
        initPosturalAssessment();
    }

    /**
     * Initialize Pediatric-specific form
     */
    function initPediatricForm() {
        // Developmental milestones checklist
        initMilestoneChecklist();
        
        // GMFM scoring
        initGMFMScoring();
        
        // Peabody scoring
        initPeabodyScoring();
    }

    /**
     * Initialize Neurological-specific form
     */
    function initNeurologicalForm() {
        // FIM scoring
        initFIMScoring();
        
        // Berg Balance Scale
        initBergBalance();
        
        // Modified Ashworth Scale (Spasticity)
        initAshworthScale();
    }

    /**
     * Initialize Sports-specific form
     */
    function initSportsForm() {
        // Return to Play Protocol
        initReturnToPlay();
        
        // Performance metrics
        initPerformanceMetrics();
    }

    /**
     * Initialize Geriatric-specific form
     */
    function initGeriatricForm() {
        // Morse Fall Scale
        initMorseFallScale();
        
        // TUG Test
        initTUGTest();
    }

    /**
     * Initialize joint ROM measurements
     */
    function initJointROM(joint, movements) {
        movements.forEach(function(movement) {
            const container = document.querySelector(`[data-joint="${joint}"][data-movement="${movement}"]`);
            if (container) {
                const slider = container.querySelector('input[type="range"]');
                if (slider) {
                    slider.addEventListener('input', function() {
                        const value = parseInt(slider.value);
                        const display = container.querySelector('.rom-value');
                        if (display) {
                            display.textContent = value + '°';
                        }
                    });
                }
            }
        });
    }

    /**
     * Initialize Manual Muscle Testing (MMT)
     */
    function initMMT() {
        const mmtInputs = document.querySelectorAll('.mmt-grade');
        mmtInputs.forEach(function(input) {
            input.addEventListener('change', function() {
                const grade = parseInt(input.value);
                const label = input.parentElement.querySelector('.mmt-label');
                if (label) {
                    const grades = ['0', '1', '2', '3', '4', '5'];
                    const descriptions = ['No contraction', 'Trace', 'Poor', 'Fair', 'Good', 'Normal'];
                    const index = grades.indexOf(grade.toString());
                    if (index >= 0) {
                        label.textContent = descriptions[index];
                    }
                }
            });
        });
    }

    /**
     * Initialize Postural Assessment
     */
    function initPosturalAssessment() {
        // Can add visual postural assessment tools here
    }

    /**
     * Initialize Milestone Checklist (Pediatric)
     */
    function initMilestoneChecklist() {
        const checkboxes = document.querySelectorAll('.milestone-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                updateMilestoneProgress();
            });
        });
    }

    /**
     * Update milestone progress
     */
    function updateMilestoneProgress() {
        const checkboxes = document.querySelectorAll('.milestone-checkbox');
        const checked = Array.from(checkboxes).filter(cb => cb.checked).length;
        const total = checkboxes.length;
        const progress = total > 0 ? (checked / total) * 100 : 0;
        
        const progressBar = document.querySelector('.milestone-progress-bar');
        if (progressBar) {
            progressBar.style.width = progress + '%';
            progressBar.setAttribute('aria-valuenow', progress);
        }
    }

    /**
     * Initialize GMFM Scoring
     */
    function initGMFMScoring() {
        // GMFM-88 or GMFM-66 scoring logic
        const gmfmItems = document.querySelectorAll('.gmfm-item');
        gmfmItems.forEach(function(item) {
            item.addEventListener('change', function() {
                calculateGMFMScore();
            });
        });
    }

    /**
     * Calculate GMFM Score
     */
    function calculateGMFMScore() {
        const items = document.querySelectorAll('.gmfm-item');
        let totalScore = 0;
        let maxScore = 0;
        
        items.forEach(function(item) {
            const value = parseInt(item.value) || 0;
            const max = parseInt(item.getAttribute('data-max')) || 3;
            totalScore += value;
            maxScore += max;
        });
        
        const percentage = maxScore > 0 ? (totalScore / maxScore) * 100 : 0;
        const display = document.querySelector('.gmfm-score-display');
        if (display) {
            display.textContent = totalScore + ' / ' + maxScore + ' (' + percentage.toFixed(1) + '%)';
        }
    }

    /**
     * Initialize FIM Scoring
     */
    function initFIMScoring() {
        const fimItems = document.querySelectorAll('.fim-item');
        fimItems.forEach(function(item) {
            item.addEventListener('change', function() {
                calculateFIMScore();
            });
        });
    }

    /**
     * Calculate FIM Score
     */
    function calculateFIMScore() {
        const items = document.querySelectorAll('.fim-item');
        let totalScore = 0;
        let maxScore = items.length * 7; // Each item max is 7
        
        items.forEach(function(item) {
            const value = parseInt(item.value) || 0;
            totalScore += value;
        });
        
        const percentage = (totalScore / maxScore) * 100;
        const display = document.querySelector('.fim-score-display');
        if (display) {
            display.textContent = totalScore + ' / ' + maxScore + ' (' + percentage.toFixed(1) + '%)';
        }
    }

    /**
     * Initialize Berg Balance Scale
     */
    function initBergBalance() {
        const bergItems = document.querySelectorAll('.berg-item');
        bergItems.forEach(function(item) {
            item.addEventListener('change', function() {
                calculateBergScore();
            });
        });
    }

    /**
     * Calculate Berg Balance Score
     */
    function calculateBergScore() {
        const items = document.querySelectorAll('.berg-item');
        let totalScore = 0;
        
        items.forEach(function(item) {
            const value = parseInt(item.value) || 0;
            totalScore += value;
        });
        
        const display = document.querySelector('.berg-score-display');
        if (display) {
            display.textContent = totalScore + ' / 56';
            
            // Risk level
            let riskLevel = '';
            let riskColor = '';
            if (totalScore >= 41) {
                riskLevel = 'Low Risk';
                riskColor = '#4caf50';
            } else if (totalScore >= 21) {
                riskLevel = 'Moderate Risk';
                riskColor = '#ff9800';
            } else {
                riskLevel = 'High Risk';
                riskColor = '#f44336';
            }
            
            const riskDisplay = document.querySelector('.berg-risk-display');
            if (riskDisplay) {
                riskDisplay.textContent = riskLevel;
                riskDisplay.style.color = riskColor;
            }
        }
    }

    /**
     * Initialize Modified Ashworth Scale
     */
    function initAshworthScale() {
        // Spasticity grading 0-4
    }

    /**
     * Initialize Return to Play Protocol
     */
    function initReturnToPlay() {
        const rtpPhases = document.querySelectorAll('.rtp-phase');
        rtpPhases.forEach(function(phase) {
            phase.addEventListener('change', function() {
                updateRTPProgress();
            });
        });
    }

    /**
     * Update Return to Play Progress
     */
    function updateRTPProgress() {
        const phases = ['acute', 'subacute', 'sport_specific', 'return_to_play'];
        let currentPhase = 0;
        
        phases.forEach(function(phase, index) {
            const checkbox = document.querySelector(`[data-rtp-phase="${phase}"]`);
            if (checkbox && checkbox.checked) {
                currentPhase = index + 1;
            }
        });
        
        const progress = (currentPhase / phases.length) * 100;
        const display = document.querySelector('.rtp-progress-display');
        if (display) {
            display.textContent = Math.round(progress) + '%';
        }
    }

    /**
     * Initialize Performance Metrics
     */
    function initPerformanceMetrics() {
        // Vertical jump, agility time, etc.
    }

    /**
     * Initialize Morse Fall Scale
     */
    function initMorseFallScale() {
        const morseItems = document.querySelectorAll('.morse-item');
        morseItems.forEach(function(item) {
            item.addEventListener('change', function() {
                calculateMorseScore();
            });
        });
    }

    /**
     * Calculate Morse Fall Scale Score
     */
    function calculateMorseScore() {
        let totalScore = 0;
        
        // History of falling
        const historyFall = document.querySelector('[name="morse[history_of_falling]"]');
        if (historyFall && historyFall.value === 'yes') totalScore += 25;
        
        // Secondary diagnosis
        const secondaryDx = document.querySelector('[name="morse[secondary_diagnosis]"]');
        if (secondaryDx && secondaryDx.value === 'yes') totalScore += 15;
        
        // Ambulatory aid
        const ambulatoryAid = document.querySelector('[name="morse[ambulatory_aid]"]');
        if (ambulatoryAid) {
            const value = ambulatoryAid.value;
            if (value === 'crutches') totalScore += 15;
            else if (value === 'walker') totalScore += 15;
            else if (value === 'cane') totalScore += 15;
        }
        
        // IV/Heparin lock
        const ivLock = document.querySelector('[name="morse[iv_heparin_lock]"]');
        if (ivLock && ivLock.checked) totalScore += 20;
        
        // Gait
        const gait = document.querySelector('[name="morse[gait]"]');
        if (gait) {
            const value = gait.value;
            if (value === 'impaired') totalScore += 10;
            else if (value === 'bedrest') totalScore += 15;
        }
        
        // Mental status
        const mentalStatus = document.querySelector('[name="morse[mental_status]"]');
        if (mentalStatus && mentalStatus.value === 'forgets_limitations') totalScore += 15;
        
        const display = document.querySelector('.morse-score-display');
        if (display) {
            display.textContent = totalScore;
            
            let riskLevel = '';
            let riskColor = '';
            if (totalScore <= 24) {
                riskLevel = 'Low Risk';
                riskColor = '#4caf50';
            } else if (totalScore <= 44) {
                riskLevel = 'Moderate Risk';
                riskColor = '#ff9800';
            } else {
                riskLevel = 'High Risk';
                riskColor = '#f44336';
            }
            
            const riskDisplay = document.querySelector('.morse-risk-display');
            if (riskDisplay) {
                riskDisplay.textContent = riskLevel;
                riskDisplay.style.color = riskColor;
            }
        }
    }

    /**
     * Initialize TUG Test
     */
    function initTUGTest() {
        const tugTime = document.querySelector('[name="tug_time"]');
        if (tugTime) {
            tugTime.addEventListener('input', function() {
                const time = parseFloat(tugTime.value);
                const display = document.querySelector('.tug-result-display');
                if (display && time > 0) {
                    let result = '';
                    let color = '';
                    if (time <= 10) {
                        result = 'Normal';
                        color = '#4caf50';
                    } else if (time <= 20) {
                        result = 'Mild Impairment';
                        color = '#ff9800';
                    } else {
                        result = 'Significant Impairment';
                        color = '#f44336';
                    }
                    display.textContent = result;
                    display.style.color = color;
                }
            });
        }
    }

    /**
     * Initialize Peabody Scoring
     */
    function initPeabodyScoring() {
        // PDMS-2 scoring logic
    }

    // Export functions for use in forms
    window.SpecialtyAssessmentForms = {
        initPainScale: initPainScale,
        initROMSliders: initROMSliders,
        calculateGMFMScore: calculateGMFMScore,
        calculateFIMScore: calculateFIMScore,
        calculateBergScore: calculateBergScore,
        calculateMorseScore: calculateMorseScore
    };
})();

