<?php

use yii\helpers\Html;
use fedemotta\datatables\DataTables;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VaaniQmsTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pending Audit Feedback';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('<i class="fas fa-chevron-left"></i>', ['/report/recordings'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="recordings_report_data" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Unique ID</th>
                                        <th>Agent</th>
                                        <th>Audit Date</th>
                                        <th>Audit By</th>
                                        <th>Duration</th>
                                        <th>Disposition</th>
                                        <th>Campaign</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($models as $key => $model){ ?>
                                        <tr>
                                            <td><?= $key+1 ?></td>
                                            <td><?= $model->unique_id ?></td>
                                            <td><?= $model->user->user_id ?> - <?= $model->user->user_name ?></td>
                                            <td><?= $model->audit->date_created ?></td>
                                            <td><?= ucwords($model->audit->created_by) ?></td>
                                            <td><?= $model->audit->call_duration ?></td>
                                            <td><?= $model->audit->sub_disposition ?></td>
                                            <td><?= $model->campaign ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$this->registerCss("
    tr {
        cursor : auto !important;
    }
");

$this->registerJs("
    $(document).ready(function() {
        $('#recordings_report_data').DataTable( {
            'lengthMenu' : [[10, 20, 50, -1], [10, 20, 50, 'All']],
            'responsive' : true,
            'dom': 'Bflrtip',
            'buttons': [  
                'copy', 'excel', 'csv', 'print',
                {
                    'text' : 'PDF',
                    'extend' : 'pdfHtml5',
                    'orientation' : 'landscape',
                    'pageSize' : 'A3',
                    'exportOptions' : {
                        'columns' : ':visible',
                        'search' : 'applied',
                        'order' : 'applied'
                    },
                }
            ],
        });
    } );
");
?>