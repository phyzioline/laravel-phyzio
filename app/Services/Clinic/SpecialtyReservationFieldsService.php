<?php

namespace App\Services\Clinic;

class SpecialtyReservationFieldsService
{
    /**
     * Get specialty-specific reservation fields schema
     * 
     * @param string $specialty
     * @return array
     */
    public function getFieldsSchema(string $specialty): array
    {
        return match($specialty) {
            'orthopedic' => $this->getOrthopedicFields(),
            'pediatric' => $this->getPediatricFields(),
            'neurological' => $this->getNeurologicalFields(),
            'sports' => $this->getSportsFields(),
            'geriatric' => $this->getGeriatricFields(),
            'womens_health' => $this->getWomensHealthFields(),
            'cardiorespiratory' => $this->getCardiorespiratoryFields(),
            'home_care' => $this->getHomeCareFields(),
            default => []
        };
    }

    /**
     * Orthopedic Physical Therapy fields
     */
    protected function getOrthopedicFields(): array
    {
        return [
            'body_region' => [
                'type' => 'select',
                'label' => 'Body Region',
                'required' => true,
                'options' => [
                    'knee' => 'Knee',
                    'shoulder' => 'Shoulder',
                    'spine' => 'Spine',
                    'ankle' => 'Ankle',
                    'hip' => 'Hip',
                    'elbow' => 'Elbow',
                    'wrist' => 'Wrist',
                    'neck' => 'Neck',
                    'other' => 'Other'
                ]
            ],
            'diagnosis' => [
                'type' => 'text',
                'label' => 'Diagnosis / Post-Op Status',
                'required' => false,
                'placeholder' => 'e.g., Post-ACL reconstruction, Rotator cuff tear'
            ],
            'pain_level' => [
                'type' => 'number',
                'label' => 'Pain Level (VAS 0-10)',
                'required' => false,
                'min' => 0,
                'max' => 10,
                'help' => 'Visual Analog Scale'
            ],
            'equipment' => [
                'type' => 'checkbox',
                'label' => 'Required Equipment',
                'required' => false,
                'options' => [
                    'shockwave' => 'Shockwave',
                    'ultrasound' => 'Ultrasound',
                    'tens' => 'TENS',
                    'laser' => 'Laser',
                    'biofeedback' => 'Biofeedback',
                    'none' => 'None'
                ]
            ],
            'session_intensity' => [
                'type' => 'select',
                'label' => 'Session Intensity Level',
                'required' => false,
                'options' => [
                    'low' => 'Low',
                    'moderate' => 'Moderate',
                    'high' => 'High'
                ]
            ],
            'session_type' => [
                'type' => 'select',
                'label' => 'Session Type',
                'required' => false,
                'options' => [
                    'manual_therapy' => 'Manual Therapy',
                    'exercise' => 'Exercise',
                    'modality' => 'Modality',
                    'combined' => 'Combined'
                ]
            ]
        ];
    }

    /**
     * Pediatric Physical Therapy fields
     */
    protected function getPediatricFields(): array
    {
        return [
            'child_age_months' => [
                'type' => 'number',
                'label' => 'Child Age (Months)',
                'required' => true,
                'min' => 0,
                'max' => 216 // 18 years
            ],
            'guardian_attending' => [
                'type' => 'select',
                'label' => 'Guardian Attending',
                'required' => false,
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No'
                ]
            ],
            'guardian_name' => [
                'type' => 'text',
                'label' => 'Guardian Name',
                'required' => false,
                'show_when' => ['guardian_attending' => 'yes']
            ],
            'behavioral_considerations' => [
                'type' => 'textarea',
                'label' => 'Behavioral Considerations',
                'required' => false,
                'placeholder' => 'Any behavioral notes or concerns...'
            ],
            'session_tolerance_level' => [
                'type' => 'select',
                'label' => 'Session Tolerance Level',
                'required' => false,
                'options' => [
                    'low' => 'Low (15-30 min)',
                    'moderate' => 'Moderate (30-45 min)',
                    'high' => 'High (45-60 min)'
                ]
            ],
            'school_report_attached' => [
                'type' => 'checkbox',
                'label' => 'School or Developmental Report Attached',
                'required' => false,
                'options' => [
                    'yes' => 'Yes'
                ]
            ],
            'play_based_focus' => [
                'type' => 'checkbox',
                'label' => 'Play-Based Therapy Focus',
                'required' => false,
                'options' => [
                    'yes' => 'Yes'
                ]
            ]
        ];
    }

    /**
     * Neurological Rehabilitation fields
     */
    protected function getNeurologicalFields(): array
    {
        return [
            'diagnosis' => [
                'type' => 'select',
                'label' => 'Diagnosis',
                'required' => true,
                'options' => [
                    'stroke' => 'Stroke',
                    'spinal_cord_injury' => 'Spinal Cord Injury',
                    'multiple_sclerosis' => 'Multiple Sclerosis',
                    'parkinsons' => "Parkinson's Disease",
                    'traumatic_brain_injury' => 'Traumatic Brain Injury',
                    'other' => 'Other'
                ]
            ],
            'affected_side' => [
                'type' => 'select',
                'label' => 'Affected Side',
                'required' => false,
                'options' => [
                    'left' => 'Left',
                    'right' => 'Right',
                    'bilateral' => 'Bilateral'
                ]
            ],
            'mobility_level' => [
                'type' => 'select',
                'label' => 'Mobility Level',
                'required' => false,
                'options' => [
                    'bedbound' => 'Bedbound',
                    'wheelchair' => 'Wheelchair',
                    'ambulatory' => 'Ambulatory',
                    'ambulatory_assistive' => 'Ambulatory with Assistive Device'
                ]
            ],
            'cognitive_status' => [
                'type' => 'select',
                'label' => 'Cognitive Status',
                'required' => false,
                'options' => [
                    'alert' => 'Alert',
                    'confused' => 'Confused',
                    'drowsy' => 'Drowsy',
                    'oriented' => 'Oriented'
                ]
            ],
            'caregiver_present' => [
                'type' => 'select',
                'label' => 'Caregiver Present',
                'required' => false,
                'options' => [
                    'yes' => 'Yes',
                    'no' => 'No'
                ]
            ],
            'rehabilitation_phase' => [
                'type' => 'select',
                'label' => 'Phase of Rehabilitation',
                'required' => false,
                'options' => [
                    'acute' => 'Acute',
                    'subacute' => 'Subacute',
                    'chronic' => 'Chronic'
                ]
            ]
        ];
    }

    /**
     * Sports Physical Therapy fields
     */
    protected function getSportsFields(): array
    {
        return [
            'sport_type' => [
                'type' => 'select',
                'label' => 'Sport Type',
                'required' => true,
                'options' => [
                    'football' => 'Football',
                    'basketball' => 'Basketball',
                    'running' => 'Running',
                    'swimming' => 'Swimming',
                    'tennis' => 'Tennis',
                    'golf' => 'Golf',
                    'other' => 'Other'
                ]
            ],
            'position' => [
                'type' => 'text',
                'label' => 'Position (if applicable)',
                'required' => false,
                'placeholder' => 'e.g., Goalkeeper, Point Guard'
            ],
            'injury_phase' => [
                'type' => 'select',
                'label' => 'Injury Phase',
                'required' => false,
                'options' => [
                    'acute' => 'Acute',
                    'subacute' => 'Subacute',
                    'return_to_play' => 'Return to Play'
                ]
            ],
            'competition_date' => [
                'type' => 'date',
                'label' => 'Competition Date (if applicable)',
                'required' => false
            ],
            'training_load' => [
                'type' => 'number',
                'label' => 'Training Load (%)',
                'required' => false,
                'min' => 0,
                'max' => 100,
                'help' => 'Current training load percentage'
            ],
            'clearance_level' => [
                'type' => 'select',
                'label' => 'Clearance Level',
                'required' => false,
                'options' => [
                    'not_cleared' => 'Not Cleared',
                    'partial' => 'Partial Clearance',
                    'full' => 'Full Clearance'
                ]
            ]
        ];
    }

    /**
     * Geriatric Physical Therapy fields
     */
    protected function getGeriatricFields(): array
    {
        return [
            'fall_risk_level' => [
                'type' => 'select',
                'label' => 'Fall Risk Level',
                'required' => false,
                'options' => [
                    'low' => 'Low',
                    'moderate' => 'Moderate',
                    'high' => 'High'
                ]
            ],
            'assistive_device' => [
                'type' => 'select',
                'label' => 'Assistive Device',
                'required' => false,
                'options' => [
                    'none' => 'None',
                    'cane' => 'Cane',
                    'walker' => 'Walker',
                    'wheelchair' => 'Wheelchair',
                    'crutches' => 'Crutches'
                ]
            ],
            'chronic_conditions' => [
                'type' => 'textarea',
                'label' => 'Chronic Conditions / Comorbidities',
                'required' => false,
                'placeholder' => 'List any chronic conditions...'
            ],
            'family_contact' => [
                'type' => 'text',
                'label' => 'Family Contact (for reporting)',
                'required' => false,
                'placeholder' => 'Name and phone number'
            ],
            'cognitive_screening_score' => [
                'type' => 'number',
                'label' => 'Cognitive Screening Score (if applicable)',
                'required' => false,
                'min' => 0,
                'max' => 30
            ]
        ];
    }

    /**
     * Women's Health / Pelvic Floor fields
     */
    protected function getWomensHealthFields(): array
    {
        return [
            'pregnancy_status' => [
                'type' => 'select',
                'label' => 'Pregnancy / Postpartum Status',
                'required' => true,
                'options' => [
                    'pregnant' => 'Pregnant',
                    'postpartum' => 'Postpartum',
                    'neither' => 'Neither'
                ]
            ],
            'trimester' => [
                'type' => 'select',
                'label' => 'Trimester (if pregnant)',
                'required' => false,
                'options' => [
                    'first' => 'First Trimester',
                    'second' => 'Second Trimester',
                    'third' => 'Third Trimester'
                ],
                'show_when' => ['pregnancy_status' => 'pregnant']
            ],
            'weeks_postpartum' => [
                'type' => 'number',
                'label' => 'Weeks Postpartum',
                'required' => false,
                'min' => 0,
                'max' => 52,
                'show_when' => ['pregnancy_status' => 'postpartum']
            ],
            'pain_sensitivity_level' => [
                'type' => 'select',
                'label' => 'Pain Sensitivity Level',
                'required' => false,
                'options' => [
                    'low' => 'Low',
                    'moderate' => 'Moderate',
                    'high' => 'High'
                ]
            ],
            'privacy_level' => [
                'type' => 'select',
                'label' => 'Privacy Level',
                'required' => false,
                'options' => [
                    'standard' => 'Standard',
                    'restricted' => 'Restricted Access'
                ]
            ],
            'biofeedback_session' => [
                'type' => 'checkbox',
                'label' => 'Biofeedback Session',
                'required' => false,
                'options' => [
                    'yes' => 'Yes'
                ]
            ]
        ];
    }

    /**
     * Cardiorespiratory Physical Therapy fields
     */
    protected function getCardiorespiratoryFields(): array
    {
        return [
            'diagnosis' => [
                'type' => 'select',
                'label' => 'Cardiac / Pulmonary Diagnosis',
                'required' => true,
                'options' => [
                    'cardiac_rehab' => 'Cardiac Rehabilitation',
                    'copd' => 'COPD',
                    'asthma' => 'Asthma',
                    'post_cardiac_surgery' => 'Post-Cardiac Surgery',
                    'other' => 'Other'
                ]
            ],
            'vital_signs_baseline' => [
                'type' => 'text',
                'label' => 'Vital Signs Baseline',
                'required' => false,
                'placeholder' => 'e.g., HR: 72, BP: 120/80, O2: 98%'
            ],
            'exercise_tolerance_level' => [
                'type' => 'select',
                'label' => 'Exercise Tolerance Level',
                'required' => false,
                'options' => [
                    'low' => 'Low',
                    'moderate' => 'Moderate',
                    'high' => 'High'
                ]
            ],
            'monitoring_required' => [
                'type' => 'checkbox',
                'label' => 'Monitoring Required',
                'required' => false,
                'options' => [
                    'hr' => 'Heart Rate',
                    'bp' => 'Blood Pressure',
                    'o2' => 'O2 Saturation',
                    'ecg' => 'ECG'
                ]
            ]
        ];
    }

    /**
     * Home Care / Mobile Physical Therapy fields
     */
    protected function getHomeCareFields(): array
    {
        return [
            'patient_address' => [
                'type' => 'textarea',
                'label' => 'Patient Full Address',
                'required' => true,
                'placeholder' => 'Full address with GPS coordinates if available'
            ],
            'travel_time_estimated' => [
                'type' => 'number',
                'label' => 'Estimated Travel Time (minutes)',
                'required' => false,
                'min' => 0
            ],
            'home_environment_notes' => [
                'type' => 'textarea',
                'label' => 'Home Environment Notes',
                'required' => false,
                'placeholder' => 'Notes about home setup, accessibility, etc.'
            ],
            'portable_equipment' => [
                'type' => 'checkbox',
                'label' => 'Required Portable Equipment',
                'required' => false,
                'options' => [
                    'tens' => 'TENS',
                    'ultrasound' => 'Portable Ultrasound',
                    'exercise_bands' => 'Exercise Bands',
                    'weights' => 'Portable Weights',
                    'other' => 'Other'
                ]
            ],
            'route_optimization' => [
                'type' => 'checkbox',
                'label' => 'Route Optimization Needed',
                'required' => false,
                'options' => [
                    'yes' => 'Yes'
                ]
            ]
        ];
    }

    /**
     * Validate specialty-specific data
     * 
     * @param string $specialty
     * @param array $data
     * @return array Validation errors
     */
    public function validateSpecialtyData(string $specialty, array $data): array
    {
        $schema = $this->getFieldsSchema($specialty);
        $errors = [];

        foreach ($schema as $field => $config) {
            if (isset($config['required']) && $config['required']) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    $errors[$field] = $config['label'] . ' is required.';
                }
            }

            // Type-specific validation
            if (isset($data[$field])) {
                switch ($config['type']) {
                    case 'number':
                        if (!is_numeric($data[$field])) {
                            $errors[$field] = $config['label'] . ' must be a number.';
                        } elseif (isset($config['min']) && $data[$field] < $config['min']) {
                            $errors[$field] = $config['label'] . ' must be at least ' . $config['min'] . '.';
                        } elseif (isset($config['max']) && $data[$field] > $config['max']) {
                            $errors[$field] = $config['label'] . ' must be at most ' . $config['max'] . '.';
                        }
                        break;
                }
            }
        }

        return $errors;
    }
}

