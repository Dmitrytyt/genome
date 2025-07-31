<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var LinkForm $model */

use app\core\forms\LinkForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Генерация ссылки и QR';

?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                <?= $form->field($model, 'link')
                    ->textInput([
                        'autofocus' => true,
                        'enableAjaxValidation' => true,
                        'placeholder' => 'https://example.com',
                    ]) ?>
                <div class="form-group">
                    <?= Html::submitButton('Ok', ['class' => 'btn btn-primary', 'name' => 'LinkForm[button]']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            <div id="resultQrCode"></div>
            <div id="resultLink"></div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
$(document).ready(function() {
    $('#contact-form').on('beforeSubmit', function() {
        var data = $(this).serialize();

        $.ajax({
            url: '/',
            type: 'POST',
            data: data,
            timeout: 5000,
            success: function(res) {
                if (res && res['linkform-link'] && res['linkform-link'][0] !== undefined) {
                    $('#linkform-link').addClass('is-invalid');
                    $('#linkform-link + .invalid-feedback').html(res['linkform-link'][0]);
                }

                if (res && res['shortUrl'] !== undefined) {
                    $('#resultLink').html('Короткая ссылка: <a href="' + res['shortUrl'] + '" target="_blank">' + res['shortUrl'] + '</a>');
                }

                if (res && res['qrCode'] !== undefined) {
                    $('#resultQrCode').html('<img src="' + res['qrCode'] + '" />');
                }
            },
            error: function() {
                alert('Error!');
            }
        });

        return false;
    });
});
JS;

$this->registerJs($js);
?>
