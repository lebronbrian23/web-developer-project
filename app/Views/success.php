<?php
/**
 * Success View
 * Shown after a valid submission via PRG redirect.
 * Displays a summary of what was submitted.
 */

$countryNames = ['CA' => 'Canada', 'US' => 'United States'];
$countryLabel = $countryNames[$submission['country']] ?? htmlspecialchars($submission['country']);
$budgetLabels = ['low' => '$5 - $99', 'medium' => '$100 - $249', 'high' => '$250 - $499'];
$budgetLabel = $budgetLabels[$submission['budget']] ?? htmlspecialchars($submission['budget']);
?>

<div class="form-panel__inner">

    <!-- Success Header -->
    <header class="form-header" role="status" aria-live="polite">
        <h2 class="form-header__title">Your project has been submitted!</h2>
        <p class="form-header__subtitle">A confirmation email has been sent to our team. We'll be in touch shortly.</p>
    </header>

    <!-- Submission Summary -->
    <div class="success-summary" aria-label="Submission summary">
        <h3 class="success-summary__heading">Submission Summary</h3>

        <dl class="summary-list">
            <div class="summary-list__row">
                <dt class="summary-list__label">Project Name</dt>
                <dd class="summary-list__value"><?= htmlspecialchars($submission['title']) ?></dd>
            </div>

            <div class="summary-list__row">
                <dt class="summary-list__label">Location</dt>
                <dd class="summary-list__value">
                    <?= htmlspecialchars($submission['state_or_province']) ?>, <?= htmlspecialchars($countryLabel) ?>
                </dd>
            </div>

            <div class="summary-list__row">
                <dt class="summary-list__label">Budget</dt>
                <dd class="summary-list__value"><?= htmlspecialchars($budgetLabel) ?></dd>
            </div>

            <?php if (!empty($submission['script'])): ?>
            <div class="summary-list__row">
                <dt class="summary-list__label">Script</dt>
                <dd class="summary-list__value summary-list__value--script">
                    <?= nl2br(htmlspecialchars($submission['script'])) ?>
                </dd>
            </div>
            <?php endif; ?>

            <div class="summary-list__row">
                <dt class="summary-list__label">Reference File</dt>
                <dd class="summary-list__value">
                    <?= !empty($submission['reference_file_path']) ? 'Uploaded ✓' : 'None provided' ?>
                </dd>
            </div>

            <div class="summary-list__row">
                <dt class="summary-list__label">Submitted At</dt>
                <dd class="summary-list__value">
                    <?= htmlspecialchars($submission['created_at']) ?>
                </dd>
            </div>
        </dl>
    </div>

    <!-- Submit Another Project Button -->
    <div class="form-actions">
        <a href="index.php" class="btn btn--primary">Submit Another Project</a>
    </div>

</div><!-- /.form-panel__inner -->
