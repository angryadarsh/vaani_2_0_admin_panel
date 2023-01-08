<?php

/* @var $this yii\web\View */
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

?>

<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
            <p class="modal_header">Upload Hold Music for Queues</p>
            <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
        </div>
        <?php $form = ActiveForm::begin([
            'options' => [
                'autocomplete' => 'off', 
                'onsubmit' => "if(!$('.help-block').text()) {
                    $('#LoadingBox').show(); 
                }",
                'enctype' => 'multipart/form-data'
            ], 
            'id' => 'music_form', 
            'action' => ['campaign/upload-hold-music']
        ]); ?>
        <div class="modal-body">
            <?php if($queues){ ?>
                <?php foreach($queues as $key => $queue){ ?>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group field-queue_name">
                                <?= $queue->queue_name ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($queue, 'hold_music_file', ['options' => ['class' => 'upload_file_input form-group']])->fileInput([
                                'class' => 'upload_hold_music_file', 
                                'onchange' => 'function(event) {
                                    console.log($(this));
                                }'
                            ])->label('<span class="music_label"><i class="fa fa-upload btn btn-default"> Upload Music </i></span> <span class="music_name"></span>') ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div> 
        <div class="modal-footer">
            <div class="col-lg-12 form-group text-center">
                <?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton('Cancel', ['class' => 'btn btn-secondary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>