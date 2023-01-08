<?php

use yii\helpers\Html;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;
use common\models\EdasCampaign;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\EdasCampaignSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Campaigns';
// $this->params['breadcrumbs'][] = $this->title;
$urlManager = Yii::$app->urlManager;
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left">Campaign</h1>
            <div class="float-right">
                <?= Html::a('<i class="fas fa-plus"></i>', ['add-campaign'], ['class' => 'btn btn-sm btn-outline-primary','title' => 'Add Campaign']) ?>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <?php if($dataProvider->query->all()){ ?>
            <?= DataTables::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'campaign_name',
                    [
                        'attribute' => 'campaign_type',
                        'label' => 'Type',
                        'value' => function($model){
                            return (($model->campaign_type && isset(EdasCampaign::$campaign_types[$model->campaign_type])) ? EdasCampaign::$campaign_types[$model->campaign_type] : '-');
                        }
                    ],
                    /* [
                        'attribute' => 'client_id',
                        'label' => 'Client',
                        'value' => function($model){
                            return ($model->client ? $model->client->client_name : '-');
                        }
                    ], */
                    /* [
                        'attribute' => 'campaign_dni',
                        'value' => function($model){
                            return ($model->dni ? $model->dni->DNI_name : '-');
                        }
                    ], */
                    [
                        'attribute' => 'campaign_status',
                        'label' => 'Status',
                        'value' => function($model){
                            return (($model->campaign_status && isset(EdasCampaign::$campaign_statuses[$model->campaign_status])) ? EdasCampaign::$campaign_statuses[$model->campaign_status] : '-');
                        }
                    ],
                    'created_date',
                    // 'modified_date',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        // 'template' => '{update} | {upload_hold_music} | {delete} | {clone}',
                        'template' => '{update} 
                        | {delete} | {clone}',
                        'buttons'=> [
                            'update' => function ($url, $model) {
                                /* if($model->campaign_type == EdasCampaign::TYPE_INBOUND){
                                    return Html::a('<span class="fa fa-edit"></span>', ['add-inbound', 'id' => $model->id]);
                                }else if($model->campaign_type == EdasCampaign::TYPE_OUTBOUND){ */
                                    return Html::a('<span class="fa fa-edit"></span>', ['add-campaign', 'id' => User::encrypt_data($model->id)], ['title' => 'Update Campaign']);
                                // }
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<span class="fa fa-trash"></span>', ['delete', 'id' => User::encrypt_data($model->id)], [
                                    'data-message' => 'Are you sure you want to delete this Campaign, including it\'s queues?',
                                    'data-method' => 'post',
                                    'title' => 'Delete',
                                    'class' => 'delete_campaign'
                                ]);
                            },
                            // 'upload_hold_music' => function($url, $model) {
                            //     return Html::a('<span class="fa fa-upload text-primary"></span>', null, [
                            //         'data-campaign_id' => $model->campaign_id,
                            //         'title' => 'Upload Hold Music',
                            //         'class' => 'upload_hold_music'
                            //     ]);
                            // },
                            //created by gaurav
                            'clone' => function ($url, $model) {
                                return Html::a('<span class="fa fa-copy"></span>', ['add-campaign', 'cloneid' => User::encrypt_data($model->id)],['title' => 'Copy']);
                                
			                }
                        ]
                    ],
                ],
                'clientOptions' => [
                    "lengthMenu" => [[10, 20, 50, -1], [10, 20, 50, "All"]],
                    "responsive"=>true, 
                    "dom"=> 'frtip',
                ],
            ]); ?>
        <?php }else{ ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'campaign_name',
                    'campaign_type',
                    'status',
                    'created_date',
                    ['class' => 'yii\grid\ActionColumn',]
                ]
            ]); ?>
        <?php } ?>
    </div>
</section>

<div id="queueMusicModal" class="modal fade" role="dialog">
</div>

<?php
$this->registerJs("
    $('.upload_hold_music').on('click', function() {
        var campaign_id = $(this).data('campaign_id');
        if(campaign_id){
            $.ajax({
                method: 'GET',
                url: '". $urlManager->baseUrl . "/index.php/campaign/get-queues-music" ."',
                data: { campaign_id : campaign_id }
            }).done(function (data) {
                $('#queueMusicModal').html(data);
                $('#queueMusicModal').modal('toggle');

                // on change of upload music file
                /* $('#vaanicampaignqueue-hold_music_file').on('change', function(e) {
                    console.log($(this));
                    if(e.target.files[0]){
                        var filename = e.target.files[0].name;
                        $(this).parent().find('.music_name').html(`<span class='music-text'>` + filename + `</span> <span class=''><i class='fa fa-upload'></i></span>`);
                        $(this).parent().find('.music_label .btn-default').hide();
                    }else{
                        $(this).parent().find('.music_name').html(``);
                        $(this).parent().find('.music_label .btn-default').show();
                    }
                }); */
            });
        }
    });
    
");
?>
<?php
// display loader on delete
$this->registerJs("
    $('.delete_campaign').on('click', function(){
        var content = $(this).data('message');
        if(confirm(content)){
            $('#LoadingBox').show();
        }else{
            return false;
        }
    });
");
?>