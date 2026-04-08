<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Post your voice project requirements with Voices.">
    <title>Post a Job — Voices</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<a href="#main-content" class="skip-link">Skip to main content</a>

<div class="page-wrapper">

    <!-- ── Left Panel ── -->
    <aside class="hero-panel" aria-hidden="true">
        <div class="hero-panel__inner">
            <div class="hero-content">
                <h1 class="hero-title">Let us know your<br>project requirements</h1>
            </div>
        </div>
    </aside>

    <!-- ── Right Panel ── -->
    <main id="main-content" class="form-panel">
        
        <?php if (!empty($successId) && !empty($submission)) : ?>
            <!-- ── Success State ── -->
            <?php require __DIR__ . '/success.php'; ?>

        <?php else : ?>
            <!-- ── Form State ── -->
            <?php require __DIR__ . '/form.php'; ?>

        <?php endif; ?>

    </main>

</div><!-- /.page-wrapper -->

<script src="js/script.js"></script>
</body>
</html>