<?php

/**
 * Form View
 * Renders the job submission form.
 */

// Extract variables passed from the controller
$csrfToken = $csrfToken ?? '';
$submission = $submission ?? null;
$provinces = $provinces ?? [];
$errors = $errors ?? [];
$old_input = $old_input ?? [];
$successId = $successId ?? null;
$budgets = [
    'low' => '$5 - $99',
    'medium' => '$100 - $249',
    'high' => '$250 - $499'
];

// Function to retain old input values after validation errors
function old($field, $default = '')
{
    return htmlspecialchars($GLOBALS['old_input'][$field] ?? $default);
}

// Function to display validation error messages
function error($field)
{
    if (isset($GLOBALS['errors'][$field])) {
        return '<div class="error" id="' . $field . '-error" role="alert">' . htmlspecialchars($GLOBALS['errors'][$field]) . '</div>';
    }
    return '';
}

// Function to check if a field has an error for styling purposes
function hasError($field)
{
    return isset($GLOBALS['errors'][$field]) ? 'input-error' : '';
}

// Helper: return aria-describedby attribute if error exists for this field
function ariaDescribedBy($field)
{
    return isset($GLOBALS['errors'][$field]) ? 'aria-describedby="' . $field . '-error"' : '';
}

// Function to generate options for the country dropdown
function countryOptions($provinces, $selected = '')
{
    $provinces = $provinces ?? [];
    $options = '<option value="">Select your country</option>';
    foreach ($provinces as $country => $states) {
        $isSelected = $country === $selected ? 'selected' : '';
        $options .= '<option value="' . htmlspecialchars($country) . '" ' . $isSelected . '>' . htmlspecialchars($country) . '</option>';
    }
    return $options;
}

// Function to generate options for the state/province dropdown based on the selected country
function stateOptions($provinces, $country, $selected = '')
{
    $provinces = $provinces ?? [];
    $options = '<option value="">Select your state/province</option>';
    if (isset($provinces[$country])) {
        foreach ($provinces[$country] as $state) {
            $isSelected = $state === $selected ? 'selected' : '';
            $options .= '<option value="' . htmlspecialchars($state) . '" ' . $isSelected . '>' . htmlspecialchars($state) . '</option>';
        }
    }
    return $options;
}

?>

<div class="form-panel__inner">

    <header class="form-header">
        <h2 class="form-header__title">Post your project</h2>
        <p class="form-header__subtitle">Fill in the details below to find your perfect voice talent.</p>
    </header>

    <form id="job-form" action="index.php" method="POST" aria-label="Job Submission Form" enctype="multipart/form-data" class="job-form" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">

        <!-- Honeypot field for bot detection -->
        <div style="display:none;">
            <label for="website">Website</label>
            <input type="text" id="website" name="website" autocomplete="off">
        </div>

        <!-- Job Title -->
        <div class="form-group">
            <label for="title">What's your project's name?</label>
            <p class="form-group__description">Provide a short, descriptive title to attract talent.</p>
            <input type="text" id="title" name="title" value="<?php echo old('title'); ?>" class="<?php echo hasError('title'); ?>" <?php echo ariaDescribedBy('title'); ?> aria-required="true" placeholder="For example, '30 Second Radio Spot' or 'Corporate Training Video'">
            <?php echo error('title'); ?>
        </div>

        <!-- Job Script -->
        <div class="form-group">
            <label for="script">
                Small Script 
                <span class="form-group__optional">Optional</span>
            </label>
            <p class="form-group__description">Include a small piece of a script you would like talent to read.</p>
            <textarea id="script" name="script" rows="5" class="<?php echo hasError('script'); ?>" <?php echo ariaDescribedBy('script'); ?> placeholder="Type or paste a sample script here."><?php echo old('script'); ?></textarea>
            <div class="form-group__footer">
                <p class="form-group__counter" id="word-count" style="text-align: right;">0 words</p>
                <div id="word-warning" class="word-warning" style="display: none;" role="status" aria-live="polite"></div>
            </div>
            <?php echo error('script'); ?>
        </div>

        <div class="form-row">
            <!-- Country -->
            <div class="form-group">
                <label for="country">Country <span aria-hidden="true">*</span></label>
                <select id="country" name="country" class="<?php echo hasError('country'); ?>" <?php echo ariaDescribedBy('country'); ?> aria-required="true">
                    <?php echo countryOptions($provinces, old('country')); ?>
                </select>
                <?php echo error('country'); ?>
            </div>

            <!-- State/Province -->
            <div class="form-group">
                <label for="state_or_province">State/Province <span aria-hidden="true">*</span></label>
                <select id="state_or_province" name="state_or_province" class="<?php echo hasError('state_or_province'); ?>" <?php echo ariaDescribedBy('state_or_province'); ?> aria-required="true">
                    <?php echo stateOptions($provinces, old('country'), old('state_or_province')); ?>
                </select>
                <?php echo error('state_or_province'); ?>
            </div>
        </div>

        <!-- Reference File Upload -->
        <div class="form-group">
            <label for="reference_file_path">Please upload your reference file here <span class="form-group__optional">Optional</span></label>
            <p class="form-group__hint" id="file-hint">Max. 20MB</p>
            <input type="file" id="reference_file_path" name="reference_file_path" class="<?php echo hasError('reference_file_path'); ?>" <?php echo ariaDescribedBy('reference_file_path'); ?> aria-describedby="file-hint"
                accept=".pdf,.doc,.docx,.txt,.mp3,.wav,.img,.jpeg,.jpg,.png,.mp4,.mpeg">
             <?php echo error('reference_file_path'); ?>
        </div>

        <!-- Budget  -->
        <fieldset class="form-group">
            <legend class="form-group__label">
                What's your budget?
                <span aria-hidden="true">*</span>
            </legend>
            <p class="form-group__hint" id="budget-hint">Select your budget range</p>

            <div class="budget-options" role="radiogroup" aria-required="true" aria-describedby="budget-hint<?php echo isset($errors['budget']) ? ' budget-error' : ''; ?>">
                <!-- Radio 1: $5 - $99 -->
                <div class="budget-card">
                    <input 
                        type="radio" 
                        name="budget" 
                        id="budgetLow" 
                        value="low"
                        <?php echo old('budget') === 'low' ? 'checked' : ''; ?>
                        aria-required="true"
                    >
                    <label for="budgetLow">
                        $5 - $99
                    </label>
                </div>
                
                <!-- Radio 2: $100 - $249 -->
                <div class="budget-card">
                    <input 
                        type="radio" 
                        name="budget" 
                        id="budgetMid" 
                        value="medium"
                        <?php echo old('budget') === 'medium' ? 'checked' : ''; ?>
                        aria-required="true"
                    >
                    <label for="budgetMid">
                        $100 - $249
                    </label>
                </div>
                
                <!-- Radio 3: $250 - $499 -->
                <div class="budget-card">
                    <input 
                        type="radio" 
                        name="budget" 
                        id="budgetHigh" 
                        value="high"
                        <?php echo old('budget') === 'high' ? 'checked' : ''; ?>
                        aria-required="true"
                    >
                    <label for="budgetHigh">
                        $250 - $499
                    </label>
                </div>
            </div>

            <?php echo error('budget'); ?>
        </fieldset>
        
        <!-- Actions -->
        <div class="form-actions">
            <button type="reset" class="btn btn--secondary">Reset</button>
            <button type="submit" class="btn btn--primary">Submit</button>
        </div>

    </form>

    <!-- Make provinces data available to JavaScript -->
    <script>
        window.PROVINCES = <?php echo json_encode($provinces ?? []); ?>;
    </script>
