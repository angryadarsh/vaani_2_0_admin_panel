<?php
include '_pdfexcel_js.php';
use yii\helpers\Html;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use fedemotta\datatables\DataTables;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\vaani_kp_templete */

$this->title = 'Preview Tabs';
// $this->params['breadcrumbs'][] = ['label' => 'Vaani Kp Templetes', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;

$urlManager = Yii::$app->urlManager;
//echo "<pre>";print_r($getTabList);

$id = trim($_GET['id']);
$id = User::decrypt_data($id);
?>

<div class="clearfix mb-3 top-heading">
              <h4 class="m-0 float-left"><?= Html::encode($this->title) ?></h4>
              <div class="float-right">
                  <div class="float-left">
                      <div class="form-group mb-0">
                        <!-- universal search -->
                          <input class="form-control rounded-0 py-2" type="search" value="" id="universal-search" placeholder="Search" autocomplete="off">
                          <div id="autosuggestion" class="list-group universal-search-list"></div>
                          <!-- remove search icon  -->
                          <a href="#" class="close" id="remove_search"><i class="fas fa-times"></i></a>
                      </div>
                  </div>
                  <?php
    echo "<pre>";


    $flag =  explode("_", $id);
    // print_r($flag);

       //var_dump($flag[0] === $id);

                  if(isset($flag) && $flag){  
                    if(isset($flag[1]) && ($flag[1] === "1")){  

                      echo Html::a('<span class="btn btn-outline-primary btn-sm float-right ml-3"><i class="fas fa-chevron-left"></i></span>', ['/vaani-kp-tab/index','id' =>User::encrypt_data($id)]);
                      
                    }elseif(isset($flag[0]) && ($flag[0] === $id)){

                      echo Html::a('<span class="btn btn-outline-primary btn-sm float-right ml-3"><i class="fas fa-chevron-left"></i></span>', ['kp/index','id' =>User::encrypt_data($id)]);
                    
                    }else{

                    }
                  }

                  ?> 

              </div>
          </div>
          <div class="row">
              <div class="col-3">
                  <div class="nav flex-column nav-pills previewTab" role="tablist"  aria-orientation="vertical">
                      <?php 
                      $count = 0;
                      foreach ($getTabList as $key =>  $value){
                          $ext = pathinfo( $value['file'], PATHINFO_EXTENSION);
                          if( $count == 0){
                          ?>
                         <a class="nav-link kp_tabs active <?php echo ($ext=='xlsx' ? 'isexcel' : 'notexcel');?>"  id="<?= $value['id']?>" count="<?= $count?>" data-toggle="pill" data-tabfile="<?= $value['file']?>" href="#" role="tab" aria-controls="v-pills-home" ><?= $value['tab_name']?></a>
                      <?php
                          }else{
                              ?>
                             <a class="nav-link kp_tabs <?php echo ($ext=='xlsx' ? 'isexcel' : 'notexcel');?>"  id="<?= $value['id']?>" count="<?= $count?>" data-toggle="pill" data-tabfile="<?= $value['file']?>" href="#" role="tab" aria-controls="v-pills-home" ><?= $value['tab_name']?></a>
                              <?php 
                          }
                          $count++;
                       }
                      ?>
                  </div>
              </div>
              <div class="col-9">
                          <div class="tab-content" id="myTabContent">
                             
                          <div class="tab-pane" style="display:block;" role="tabpanel"  aria-labelledby="" >
                          <div id="resolte-contaniner" >
                          </div>
                      </div>
                  </div>
              </div>
          </div>
<?php
$this->registerCss("
#resolte-contaniner{width: 100%; height:550px; overflow: auto;}
");
?>

<script>

(function ($) {

    $( document ).ready(function() {
    
    var tab_file =  $('.kp_tabs').attr('data-tabfile');
    console.log(tab_file);
    var tab_id =  $('.kp_tabs').attr('data-id');
    var id = $('.nav-link.kp_tabs[count="0"]').attr("id");
    var excel_file = <?php echo json_encode($tblData) ?>;
    var filepath = '<?php echo Url::base(true)?>';
    var filename = tab_file;
    var ext = filename.split('.').pop();
  
    var file_path = filepath+"/uploads/knowledge_portal/client/"+filename;
    if(ext == 'xlsx' ){
      //display excel
          if(id){
              var id = $('.nav-link.kp_tabs[count="0"]').attr("id");
              var excel_file = <?php echo json_encode($tblData) ?>;
              $('#resolte-contaniner').html(excel_file[id]);
              var table = $('#excel_table').DataTable();
              $("#excel_table_filter input").val('');
              var currentVal = $("#universal-search").val();
              var table = $('#excel_table').DataTable();
              table.search(currentVal).draw();
              $("#excel_table_filter input").val(''); 
          }
          else{
                            var tabA = $('.nav-link.kp_tabs[style*="display: block"]:first');
                        var tabArText = tabA.text();
                            if(tabA)
                            {       
                                var tabA = $('.nav-link.kp_tabs[style*="display: block"]:first');
                                var tabArText = tabA.text();
                                tabA.addClass("active");
                                $( ".kp_tabs.isexcel" ).trigger( "click" );
                                var id = tabA.attr("id");
                                
                                var excel_file = <?php echo json_encode($tblData) ?>;
                                $('#resolte-contaniner').html(excel_file[id]);
                                var currentVal = $("#universal-search").val();
                                var table = $('#excel_table').DataTable();
                                table.search(currentVal).draw();
                                $("#excel_table_filter input").val('');
                            }
                        }

        // $('#universal-search').on( 'keyup keypress', function (e){
        //     var currentVal = $("#universal-search").val();
        //     if(currentVal)
        //         { 
        //                 var tabA = $('.nav-link.kp_tabs[style*="display: block"]:first');
        //                 var tabArText = tabA.text();
        //                     if(tabA)
        //                     {       
        //                         var tabA = $('.nav-link.kp_tabs[style*="display: block"]:first');
        //                         var tabArText = tabA.text();
        //                         tabA.addClass("active");
        //                         $( ".kp_tabs.isexcel" ).trigger( "click" );
        //                         var id = tabA.attr("id");
        //                         var excel_file = <?php //echo json_encode($tblData) ?>;
        //                         $('#resolte-contaniner').html(excel_file[id]);
        //                         var currentVal = $("#universal-search").val();
        //                         var table = $('#excel_table').DataTable();
        //                         table.search(currentVal).draw();
        //                         $("#excel_table_filter input").val('');
        //                     }
        //                     else{

        //                         var tabA = $('.nav-link.kp_tabs[style*="display: block"]:first');
        //                         var tabArText = tabA.text();
        //                         tabA.addClass("active");
        //                         $( ".kp_tabs.isexcel" ).trigger( "click" );
        //                         var id = tabA.attr("id");
        //                         if(id){
        //                             var excel_file = <?php //echo json_encode($tblData) ?>;
        //                             $('#resolte-contaniner').html(excel_file[id]);
        //                             var table = $('#excel_table').DataTable();
        //                             $("#excel_table_filter input").val('');
        //                             var currentVal = $("#universal-search").val();
        //                             var table = $('#excel_table').DataTable();
        //                             table.search(currentVal).draw();
        //                             $("#excel_table_filter input").val(''); 
        //                         }
        //                     }
        //         }
        // });
        
    }
    else{
    
        // $("#a_file").html($(this).data("file")).attr("href", file_path);
        // $("#a_file").show();
        // $("#file_p").show();

        //display pdf 
        $("#resolte-contaniner").officeToHtml({
            url: file_path,
            pdfSetting: {
                setLang: "en-US",
            //thumbnailViewBtn: false,
            searchBtn: true,
            nextPreviousBtn: false,
            pageNumberTxt: true,
            totalPagesLabel: true,
            zoomBtns: false,
            copyable: false,
            scaleSelector: false, 
            presantationModeBtn: false,
            openFileBtn: false,
            printBtn: false,
            downloadBtn: false,
            bookmarkBtn: false,
            secondaryToolbarBtn: false,
            firstPageBtn: true,
            lastPageBtn: true,
            pageRotateCwBtn: true,
            pageRotateCcwBtn: true,
            cursorSelectTextToolbarBtn: true,
            cursorHandToolbarBtn: true,
            setLangFilesPath: "../../js/ms_office/officetohtml/locale",
            width: true,
            height:true,
            /*"include/pdf/lang/locale" - relative to app path*/
            },
            
        });
    }

   
});
    //on click functionality for tab

  $(".kp_tabs").on('click', function(e) {
      e.preventDefault();
      $('.tab-pane').css('display','block');
      var tab_file =  $(this).attr('data-tabfile');
      var tab_id =  $(this).attr('data-id');
      $(".sdb_holder li").removeClass("active");
      $('kp_tabs').removeClass('active');

      var id = $(this).attr("id");
      $("#head-name").html($(this).html());
      $("#description").hide();
      $("#resolte-contaniner").html("");
      $("#resolte-contaniner").show();
      $("#resolte-text").show();
      
      $("#select_file").hide();

      //encode table data which comes from controller

      var excel_file = <?php echo json_encode($tblData) ?>;
      var filepath = '<?php echo Url::base(true)?>';
      var filename = tab_file;
      var ext = filename.split('.').pop();

      var file_path = filepath+"/uploads/knowledge_portal/client/"+filename;
      if(ext == 'xlsx'){

           //display excel
                $('#resolte-contaniner').html(excel_file[id]);

                //fetching search value 
                var currentVal = $("#universal-search").val();
                var table = $('#excel_table').DataTable();
                table.search(currentVal).draw();
                $("#excel_table_filter input").val('');
                //after filtering record information
                var info = table.page.info().recordsDisplay;

                if (info==0) {
                    $('#'+id).each(function() {
                        $(this).hide();
                        $('#resolte-contaniner').html('');
                    });
                }
        }
        else{
            

            $("#resolte-contaniner").officeToHtml({
                url: file_path,
                pdfSetting: {
                    setLang: "en-US",
                searchBtn: true,
                nextPreviousBtn: false,
                pageNumberTxt: true,
                totalPagesLabel: true,
                zoomBtns: false,
                copyable: false,
                scaleSelector: false, 
                presantationModeBtn: false,
                openFileBtn: false,
                printBtn: false,
                downloadBtn: false,
                bookmarkBtn: false,
                secondaryToolbarBtn: false,
                firstPageBtn: true,
                lastPageBtn: true,
                pageRotateCwBtn: true,
                pageRotateCcwBtn: true,
                cursorSelectTextToolbarBtn: true,
                cursorHandToolbarBtn: true,
                setLangFilesPath: "../../js/ms_office/officetohtml/locale",
                width: true,
                height:true,
                /*"include/pdf/lang/locale" - relative to app path*/
                },
               
            });
        }
  });

  //universal searching on keypress

    $('#universal-search').on( 'keyup keypress', function (e){
        
        $("#autosuggestion").show();
        var table = $('#excel_table').DataTable();
        table.search(this.value).draw();
        $('.notexcel').css('display','none');

        //data-count 
        var e = 0;
        $('.isexcel').each(function(){
            $(this).attr('data-count' ,+e++);
            $('.kp_tabs').removeClass('active');
            $('.nav-link.kp_tabs[data-count="0"]').addClass('active');
        }); 
        
        var id = $('.nav-link.kp_tabs[data-count="0"]').attr("id");
        var excel_file = <?php echo json_encode($tblData) ?>;
        var filepath = '<?php echo Url::base(true)?>';
        var tab_file =  $('.kp_tabs').attr('data-tabfile');
        var filename = tab_file;
        var ext = filename.split('.').pop();
      
        $('#resolte-contaniner').html(excel_file[id]);
        var table = $('#excel_table').DataTable();
        var currentVal = $("#universal-search").val();
          var tbl = table.search(currentVal).draw();
          var info = table.page.info().recordsDisplay;
          //click event trigger
          $( ".kp_tabs.isexcel" ).trigger( "click" );

          //record display which is 0
          if(info==0){
              $('#'+id).each(function() {
                  $( ".kp_tabs.isexcel" ).trigger( "click" );
                  $(this).hide();
                  $('#resolte-contaniner').html('');
                });
            }

                                var id = $('.nav-link.kp_tabs[data-count="0"]').attr("id");
                                if(id){
                                var excel_file = <?php echo json_encode($tblData) ?>;
                                $('#resolte-contaniner').html(excel_file[id]);
                                var table = $('#excel_table').DataTable();
                                table.search(this.value).draw();
                                $("#excel_table_filter input").val('');
                                }

                   var tabA = $('.isexcel[style*="display: block"]:first');
                        var tabArText = tabA.text();
                        if(tabA)
                            {
                                tabA.addClass("active");
                                var id = tabA.attr("id");
                                var excel_file = <?php echo json_encode($tblData) ?>;
                                $('#resolte-contaniner').html(excel_file[id]);
                                var table = $('#excel_table').DataTable();
                                table.search(this.value).draw();
                                $("#excel_table_filter input").val('');
                            }
             
    });

    //universal search keydown

 $('#universal-search').on( 'keyup keydown', function (event){


    if (event.key === 'Delete' || event.key === 'Backspace')
    {
        
            $('#autosuggestion').hide();
            var id = $('.nav-link.kp_tabs[data-count="0"]').attr("id");
            var excel_file = <?php echo json_encode($tblData) ?>;
            $('.isexcel').css('display','block');
            $('#resolte-contaniner').html(excel_file[id]);
            //$("#excel_table_filter input").val('');
            $('.nav-link.kp_tabs[data-count="0"]').addClass('active');
            var table = $('#excel_table').DataTable();
            $('.notexcel').css('display','block');
            //table.search(this.value).draw();
            var currentVal = $("#universal-search").val();
            table.search(currentVal).draw();
            $("#excel_table_filter input").val('');
            $('#autosuggestion').hide();
            if (event.which == 8) {
                $('#autosuggestion').html('');
                $('.kp_tabs').removeClass('active');
                $('.nav-link.kp_tabs[count="0"]').addClass('active');
            }
            $('.kp_tabs').removeClass('active');
            $('.nav-link.kp_tabs[data-count="0"]').addClass('active');

    }

 });

  //remove search on click

  $('#remove_search').on( 'click', function (e){

          var id = $('.nav-link.kp_tabs[data-count="0"]').attr("id");
          var excel_file = <?php echo json_encode($tblData) ?>;
          $('.isexcel').css('display','block');
          $('#resolte-contaniner').html(excel_file[id]);
          //$("#excel_table_filter input").val('');
          $('#universal-search').val("");
          var table = $('#excel_table').DataTable();
          var currentVal = $("#universal-search").val();
          table.search(currentVal).draw();
          $('.notexcel').css('display','block');
          $("#excel_table_filter input").val('');
          $('#autosuggestion').html('');
          $('.kp_tabs').removeClass('active');
                $('.nav-link.kp_tabs[count="0"]').addClass('active');
            $('.kp_tabs').removeClass('active');
                $('.nav-link.kp_tabs[data-count="0"]').addClass('active');
  });

  //prevent save on keydown

  $(document).bind('keydown', function(e) {
  if(e.ctrlKey && (e.which == 83) || e.ctrlKey && (e.which == 80) ) {
      e.preventDefault();
      return false;
      }
  }); 

  //prevent copy on keydown
//   $(document).bind('keydown', function(e) {
//   if(e.ctrlKey && (e.which == 67) || e.ctrlKey && (e.which == 99) ) {
//       e.preventDefault();
//       return false;
//       }
//   }); 

//    //prevent paste on keydown
//   $(document).bind('keydown', function(e) {
//   if(e.ctrlKey && (e.which == 86) || e.ctrlKey && (e.which == 118) ) {
//       e.preventDefault();
//       return false;
//       }
//   }); 

  $(document).bind('keyup', function(e) {
  if(e.ctrlKey && (e.which == 70)) {
      return true;
      }
  });

  //document load on ready function  

  
$( document ).ready(function() {

    $("#universal-search").on( 'keyup', function (e){

        var currentVal = $('#universal-search').val();

        if(currentVal !== null  || currentVal !=''){

            $.ajax({
                        method: 'POST',
                        url: '<?php echo $urlManager->baseUrl ?>/index.php/kp/suggestion',
                        data: {currentVal : currentVal}
                    }).done(function(data){
                        var listdata = '';
                        $('#autosuggestion').html(listdata);
                        if($.isEmptyObject(JSON.parse(data))){
                         listdata += '<a href="#" class="list list-group-item list-group-item-action">No data found</a>';
                            $('#autosuggestion').html(listdata);   
                            if(e.key === "Enter"){

                                $.ajax({
                                    method: 'POST',
                                    url: '<?php echo $urlManager->baseUrl ?>/index.php/kp/suggestion',
                                    data: {newVal : currentVal}
                                }).done(function(data){
                                });
                            }
                        }else{
                           var  data = JSON.parse(data);
                            //console.log(data);
                            if(data.length > 0){
                                $.each(data, function( index, value ) {
                                listdata += '<a href="#" class="list list-group-item list-group-item-action">'+value+'</a>'; 
                                }); 
                            }
                            $('#autosuggestion').html(listdata);
                            $('.list').on('click',function(){
                                    var value = $(this).text(); 
                                    $('#universal-search').val(value);
                                    $('.list').hide();
                            });

                        }     
            });

        }             
    });
    
});


}(jQuery));   
           
</script>