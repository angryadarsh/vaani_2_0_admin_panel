<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\VaaniMenu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VaaniMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customize Menus';
// $this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile(Yii::$app->request->baseUrl . '/css/master_menu.css');
$this->registerCssFile(Yii::$app->request->baseUrl . '/css/bootnavbar.css');

$urlManager = Yii::$app->urlManager;
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="customdiv">
            <div class="row justify-content-center">
                <div class="col-6">
                    <div class="list">
                        <div class="root clearfix">
                            <div class="inline_div chk_div">
                                <label class="container-checkbox">
                                    <input type="radio" class="menu_select check" name="radio1" value="0" data-level="0">
                                    <span class="checkmark"></span>
                                    <div class="Main_menu">
                                        <span class="category_name root_css">Main Menu</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- list of menus with sub-menus -->
                        <div class="list_div_inner">
                            <ul class="navbar-nav mr-auto sortable ui-sortable">
                                <?php foreach($dataProvider->query->all() as $key => $value){
                                    // $menus[$value->menu_id] = $value->menu_name; ?>
                                    <li id=<?= $value->menu_id ?>>
                                        <div class="chk_li_div">
                                            <a href="javascript:void(0)" class="inline_div chk_div ui-sortable-handle">
                                                <label class="container-checkbox">
                                                    <input type="radio" class="menu_select check" name="radio1" data-level="<?= $value->level ?>" data-route="<?= $value->route ?>" data-icon="<?= $value->icon ?>" data-seq="<?= $value->sequence ?>" data-parent="<?= $value->parent_id ?>" data-name="<?= $value->menu_name ?>" value="<?= $value->menu_id ?>">
                                                    <span class="checkmark"></span>
                                                    <span class="category_name lvl_1"><?= $value->menu_name ?></span>
                                                </label>
                                            </a>
                                        </div>
                                        <!-- display sub-menus -->
                                        <?php if($value->subMenus){
                                            // echo "<pre>"; print_r($value->subMenus);exit; ?>
                                        <?php VaaniMenu::displaySubMenus($value, $value->subMenus); ?>
                                        <?php } ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="btn_div_outer" style="position: sticky;bottom: 0">
        <div class="divinline btn_div">
            <button type="button" id="" class="btn btn-outline-primary btn_add"><i class="fa fa-plus"></i> ADD</button>
            <button type="button" id="" class="btn btn-outline-warning btn_edit"><i class="fa fa-pencil"></i> EDIT</button>
            <button type="button" id="" class="btn btn-outline-danger btn_delete"><i class="fa fa-trash"></i> DELETE</button>
        </div>
    </div>
</section>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal_header">Add Menu</p>
                <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
            </div>
            <?php $form = ActiveForm::begin(['action' => ['menu/add'], 'method' => 'post', 'options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
                $('#LoadingBox').show(); 
            } "]]); ?>
            <div class="modal-body">
                <div class="col-md-12">
                    <?= $form->field($model, 'menu_id', ['template' => '{input}{label}{error}{hint}'])->hiddenInput(['class' => 'form-control modal-input'])->label(false) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'parent_id', ['template' => '{input}{label}{error}{hint}'])->dropdownList($menus, ['placeholder' => 'Main Menu', 'prompt' => 'Select ...', 'class' => 'form-control modal-input'])->label('Parent Menu', ['class' => 'form-label']) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'level', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Level', 'class' => 'form-control modal-input'])->label('Level', ['class' => 'form-label']) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'menu_name', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Menu Name', 'class' => 'form-control modal-input'])->label('Menu Name', ['class' => 'form-label']) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'route', ['template' => '{input}{label}{error}{hint}'])->widget(Select2::classname(), [
                        'data' => $routes,
                        'options' => ['prompt' => '', 'class' => 'form-control modal-input', 'id' => 'route-select2'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label('Route', ['class' => 'form-label']); ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'icon', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Menu Icon', 'class' => 'form-control modal-input','value'=>'fas fa-table'])->label('Menu Icon', ['class' => 'form-label']) ?>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-lg-12 form-group text-center">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'id' => 'submit_btn']) ?>
                    <?= Html::resetButton('Cancel', ['class' => 'btn btn-secondary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$this->registerCss("
    .modal-input{ width: 100%!important; }
");

$this->registerJs("
    $( '.sortable' ).nestedSortable({
        forcePlaceholderSize: true,
        items: 'li',
        handle: 'a',
        placeholder: '',
        listType: 'ul',
        maxLevels: 7,
        opacity: .6,
        update: function (event, ui) {
            var item_list   = {};
            var retval = {};
            var i   = 1;
            var seq = 1;

            $('.sortable').children('li').each(function(ind, el) {
                var label = $(this).attr('id');        
                item_list[label+'~'+seq]=i;
                
                if($(this).children('ul')) 
                {
                    var obj = $(this).children('ul');
                    retval = getMachine('parent',obj,i);
                    
                    item_list[label+'~'+seq+'~sub'] = retval;  
                }
                seq++;
            });
            Swal.fire({
                title: 'Do you want to change the sequence of menu?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Update',
                denyButtonText: 'Don\"t Change',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type:'POST',
                        url:'".$urlManager->baseUrl . '/index.php/menu/change-sequence' ."',
                        data:{item_list : item_list},
                        success: function(result)
                        {
                            if(result==2) {
                                Swal.fire('Menu Updated!', '', 'success');
                                location.reload();
                            } else {
                                Swal.fire('Changes are not saved, please try after some time', '', 'info');
                                location.reload();
                            }
                        }
                    })
                }
                else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                    location.reload();
                }
            });
        }
    });

    function getMachine(chk,obj,lv) {
        var seq_tmp   = 1;
        var j         = lv+1;
        var tmpArr    = {};
        if(chk == 'parent')
        {
            $(obj).children('li').each(function(ind, el) {
                var label = $(this).attr('id');
                tmpArr[label+'~'+seq_tmp]=j;
                if($(this).children('ul')) {
                    var obj_tmp = $(this).children('ul');
                    retval = getMachine('parent',obj_tmp,j);
                    tmpArr[label+'~'+seq_tmp+'~sub'] = retval;
                }
                seq_tmp++;
            });
        }
        else{}
        return tmpArr;
    }

    // add new menu under selected menu
    $('.btn_add').on('click', function(){
        var menu_id = $('input[name=\"radio1\"]:checked').val();
        if(menu_id){
            var level = $('input[name=\"radio1\"]:checked').data('level');

            $('#vaanimenu-parent_id').val(menu_id);
            $('#vaanimenu-level').val(level + 1);
            $('#vaanimenu-menu_id').val('');
            $('#vaanimenu-menu_name').val('');
            $('#vaanimenu-route').val('');
            $('#vaanimenu-icon').val('fas fa-table');
            $('.modal_header').html('Add Menu');
            $('#submit_btn').text('Add');
            $('#myModal').modal('toggle');
            $('#view_resources').attr('name','add');
        }else{
            Swal.fire('You have not selected any parent menu!', '', 'info');
        }
    });

    // edit the selected menu
    $('.btn_edit').on('click', function(){
        var menu_id = $('input[name=\"radio1\"]:checked').val();
        if(menu_id){
            var level = $('input[name=\"radio1\"]:checked').data('level');
            var parent_id = $('input[name=\"radio1\"]:checked').data('parent');
            var route = $('input[name=\"radio1\"]:checked').data('route');
            var icon = $('input[name=\"radio1\"]:checked').data('icon');
            var name = $('input[name=\"radio1\"]:checked').data('name');

            $('#vaanimenu-menu_id').val(menu_id);
            $('#vaanimenu-menu_name').val(name);
            $('#vaanimenu-parent_id').val(parent_id);
            $('#vaanimenu-level').val(level);
            $('#route-select2').val(route).trigger('change');
            $('#vaanimenu-icon').val(icon);
            $('.modal_header').html('Edit Menu');
            $('#submit_btn').text('Edit');
            $('#myModal').modal('toggle');
            $('#view_resources').attr('name','edit');
        }else{
            Swal.fire('You have not selected any menu!', '', 'info');
        }
    });

    // delete the selected menu
    $('.btn_delete').on('click', function(){
        var menu_id = $('input[name=\"radio1\"]:checked').val();
        if(menu_id){
            if(confirm('Are you sure you want to delete the selected menu?')){
                $.ajax({
                    method: 'POST',
                    url: '".$urlManager->baseUrl . '/index.php/menu/delete' ."',
                    data: {menu_id : menu_id}
                });
            }
        }else{
            Swal.fire('You have not selected any menu!', '', 'info');
        }
    });
");
?>