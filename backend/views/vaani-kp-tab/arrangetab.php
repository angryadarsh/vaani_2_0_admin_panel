<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\VaaniKpTab;
use common\models\vaani_kp_templete;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VaaniMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Arrange Tabs';
// $this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile(Yii::$app->request->baseUrl . '/css/master_menu.css');
$this->registerCssFile(Yii::$app->request->baseUrl . '/css/bootnavbar.css');

$urlManager = Yii::$app->urlManager;
?>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="clearfix mb-3 top-heading">
                    <h4 class="m-0 float-left"><?= Html::encode($this->title) ?></h4>
                    <div class="float-right">
                        <?= Html::a('<span class="btn btn-outline-primary btn-sm"><i class="fas fa-chevron-left"></i></span>', ['index', 'id' =>User::encrypt_data($id)]); ?>
                    </div>
                </div>
    <div class="container" style="margin-top: 50px;">
        <div class="row justify-content-center">
            <div class="col-md-4">
                    <table class="table table-stripped table-hovered table-border connected-sortable">
                        <thead>
                            <tr>
                                <th><?= $temp_name['template_name']?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tabs as $key =>  $value){ ?>
                                <tr>
                                    <td class="tab-item" id="<?php echo $key?>" sequence=""><?= $value ?></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

<?php 
$this->registerCss("
    table tbody{cursor:grab}
    .ui-sortable-handle{box-shadow: 0 0 5px 0 #ababab;}
    .ui-sortable-helper{box-shadow: 0 0 10px 0 #ababab; cursor: grabbing;}
    ");
?>

<?php
$this->registerJs("

    $( document ).ready(function() {
        $( 'table tbody' ).sortable({

            connectWith: '.connected-sortable',
            stack: '.connected-sortable ul',
            placeholder: 'placeholder',
            helper: 'clone',
            tolerance: 'tolerance',
            update: function(event, ui) {
                var sequence =[];

                $(this).children().each(function (index){
                    if($(this).attr('sequence') != (index+1)){
                        $(this).attr('sequence',(index+1)).addClass('updated');
                    }
                    
                    $('.updated').each(function (){
                        sequence.push({id: $(this).children().attr('id'), seq: $(this).attr('sequence')});
                    });
                });
                var sequences = [];
                $.each(sequence, function (i, e) {
                    var matchingItems = $.grep(sequences, function (item) {
                        return item.id === e.id && item.seq === e.seq;
                    });
                    if (matchingItems.length === 0){
                        sequences.push(e);
                    }
                });
                    $.ajax({
                        method: 'POST',
                        url: '". $urlManager->baseUrl . '/index.php/vaani-kp-tab/update-arrange-tab' ."',
                        data: {sequences : sequences}
                    }).done(function(data){
                        
                        });
            },
        });
    });
");
?>