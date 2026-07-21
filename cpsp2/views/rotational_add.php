<?php
declare(strict_types=1);

if (!defined('Isra_FROM_DASHBOARD')) {
    header('Location: ../dashboard.php?tab=rotational&sub=add');
    exit;
}

require_once __DIR__ . '/../includes/csrf.php';

$old = $formOld ?? [];
$formErrors = $formErrors ?? [];

?>
<div class="vd_content-section elog-content-section clearfix">
    <?php if ($formErrors !== []): ?>
        <div class="alert alert-danger elog-alert-errors" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="elog-error-list">
                <?php foreach ($formErrors as $err): ?>
                    <li><?= htmlspecialchars((string) $err, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form class="form-horizontal elog-form" action="rotational_save.php" method="post" name="mform2" id="mform2" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="isSubmitted" value="Y">

        <div class="row">
            <div class="col-md-12">
                <div class="panel widget elog-panel elog-panel--form">
                    <div class="panel-heading vd_bg-grey elog-panel__head">
                        <h3 class="panel-title"> <span class="menu-icon"> <i class="fa fa-bar-chart-o"></i> </span> Entry Details </h3>
                    </div>
                    <div class="panel-body">
                        
                        <!-- New form fields will be inserted here -->
                        <div class="row elog-form__row elog-form__row--1">
                            <div class="col-md-12">
                                <p style="color: #666; font-style: italic;">Rotational form fields will be added here...</p>
                            </div>
                        </div>

                        <div class="form-actions-condensed row elog-form__row elog-form__row--1 mgbt-xs-0">
                            <div class="col-sm-12">
                                <button class="btn btn--submit" type="submit" name="submit2" id="mform_submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
