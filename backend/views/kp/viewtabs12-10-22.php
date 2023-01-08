<script src="https://officetohtml.js.org/pages/include/jquery/jquery-1.12.4.min.js"></script>

<!--PDF--> 
<link rel="stylesheet" href="https://officetohtml.js.org/pages/include/pdf/pdf.viewer.css"> 
<script src="https://officetohtml.js.org/pages/include/pdf/pdf.js"></script> 
<!-- doc file  -->
  <script src="https://officetohtml.js.org/pages/include/docx/jszip-utils.js"></script>
  <script src="https://officetohtml.js.org/pages/include/docx/mammoth.browser.min.js"></script>
  <!-- spreadexcel sheet  -->
  <link rel="stylesheet" href="https://officetohtml.js.org/pages/include/SheetJS/handsontable.full.min.css">
  <script src="https://officetohtml.js.org/pages/include/SheetJS/handsontable.full.min.js"></script>
  <script src="https://officetohtml.js.org/pages/include/SheetJS/xlsx.full.min.js"></script>
  <!--officeToHtml-->
  <script src="../../js/ms_office/officetohtml/officeToHtml.js"></script>
  <link rel="stylesheet" href="https://officetohtml.js.org/officeToHtml.css">
  <!-- Jquery datatable cdn -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<?php

use yii\helpers\Html;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use fedemotta\datatables\DataTables;

/* @var $this yii\web\View */
/* @var $model common\models\vaani_kp_templete */

$this->title = 'Preview Tabs';
// $this->params['breadcrumbs'][] = ['label' => 'Vaani Kp Templetes', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;

$urlManager = Yii::$app->urlManager;


//echo "<pre>";print_r($value['id']);exit;

// echo"<pre>"; print_r($allsuggestions);exit;

?>


<div class="container-fluid">
  <div class="card mt-3">
      <div class="card-body">
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

                  <?= Html::a('<span class="btn btn-outline-primary btn-sm float-right ml-3"><i class="fas fa-chevron-left"></i></span>', ['kp/index']); ?>
              </div>
          </div>
          <div class="row">
              <div class="col-3">
                  <div class="nav flex-column nav-pills" role="tablist"  aria-orientation="vertical">
                      <?php 
                      $count = 0;

                      foreach ($getTabList as $key =>  $value){

                          $ext = pathinfo( $value['file'], PATHINFO_EXTENSION);

                          if( $count == 0){
                          ?>
          
                         <a class="nav-link kp_tabs active <?php echo ($ext=='xlsx' ? 'isexcel' : 'notexcel');?>" data-id="<?php echo $value['file'] ?>" id="<?= $value['id']?>" count="<?= $count?>" data-toggle="pill" data-tabfile="<?= $value['file']?>" href="#" role="tab" aria-controls="v-pills-home" ><?= $value['tab_name']?></a>
               
                      <?php
                          }else{
                              ?>
              
                             <a class="nav-link kp_tabs <?php echo ($ext=='xlsx' ? 'isexcel' : 'notexcel');?>" data-id="<?php echo $value['file'] ?>" id="<?= $value['id']?>" count="<?= $count?>" data-toggle="pill" data-tabfile="<?= $value['file']?>" href="#" role="tab" aria-controls="v-pills-home" ><?= $value['tab_name']?></a>
           
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
      </div>
  </div>
</div>
<?php
$this->registerCss("
// .handsontable{height: 500px;}
#resolte-contaniner{width: 100%; height:550px; overflow: auto;}
");
?>

<script>

(function ($) {

    //on click functionality

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
          
          $("#a_file").html($(this).data("file")).attr("href", file_path);
          $("#a_file").show();
          $("#file_p").show();


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
              setLangFilesPath: "https://officetohtml.js.org/pages/include/pdf/lang/locale",
              width: true,
              height:true,
              /*"include/pdf/lang/locale" - relative to app path*/
              },
              //   docxSetting: {
              //     styleMap : null,
              //     includeEmbeddedStyleMap: true,
              //     includeDefaultStyleMap: true,
              //     convertImage: null,
              //     ignoreEmptyParagraphs: false,
              //     idPrefix: "",
              //     isRtl : "auto"
              //   },
              sheetSetting: {
              jqueryui : false,
              activeHeaderClassName: "",
              allowEmpty: true,
              autoColumnSize: true,
              autoRowSize: false,
              columns: false,
              columnSorting: true,
              contextMenu: true,
              copyable: false,
              customBorders: false,
              fixedColumnsLeft: 0,
              fixedRowsTop: 0,
              language:'en-US',
              search: true,
              selectionMode: 'single',
              sortIndicator: true,
              readOnly: true,
              startRows: 1,
              startCols: 1,
              rowHeaders: true,
              colHeaders: true,
              width: false,
              height:false
              },
          });
      }
  });

  //universal searching on keypress

    $('#universal-search').on( 'keypress', function (e){


        var table = $('#excel_table').DataTable();
        table.search(this.value).draw();
        $("#excel_table_filter input").val('');
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
          $("#excel_table_filter input").val('');
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

            refer_id = $('.nav-link.kp_tabs[data-count="1"]').attr("id");

            if(refer_id){

                var e = 0;
                $('.isexcel').each(function(){
                $(this).attr('data-count' ,+e++);
                //$('.kp_tabs').removeClass('active');
                if($(this).attr('data-count') == 1)
                {
                $('.nav-link.kp_tabs[data-count="1"]').addClass('active');
                $('.nav-link.kp_tabs[data-count="0"]').removeClass('active');
                $('#resolte-contaniner').html(excel_file[refer_id]);
                var table = $('#excel_table').DataTable();
                $("#excel_table_filter input").val('');
                //click event trigger
                $( ".kp_tabs.isexcel" ).trigger( "click" );
                }

                }); 

            }                  
    });

    //universal search keydown

$('#universal-search').on( 'keydown', function (e){
        
    var id = $('.nav-link.kp_tabs[data-count="0"]').attr("id");
    var excel_file = <?php echo json_encode($tblData) ?>;
    $('.isexcel').css('display','block');
    $('#resolte-contaniner').html(excel_file[id]);
    $("#excel_table_filter input").val('');
    $('.nav-link.kp_tabs[data-count="0"]').addClass('active');
    $('.nav-link.kp_tabs[data-count="1"]').removeClass('active');
    $('.isexcel').removeAttr('data-count');
    $('.isexcel').removeAttr('data-seq');
    var table = $('#excel_table').DataTable();
    $('.notexcel').css('display','block');
    table.search(this.value).draw();
    var currentVal = $("#universal-search").val();
    table.search(currentVal).draw();
    $("#excel_table_filter input").val('');
    if (e.which == 8) {
        $('#autosuggestion').html('');
    }
  });


  //remove search on click

  $('#remove_search').on( 'click', function (e){

          var id = $('.nav-link.kp_tabs[data-count="0"]').attr("id");
          var excel_file = <?php echo json_encode($tblData) ?>;
          $('.isexcel').css('display','block');
          $('#resolte-contaniner').html(excel_file[id]);
          $("#excel_table_filter input").val('');
          $('#universal-search').val("");
          var table = $('#excel_table').DataTable();
          var currentVal = $("#universal-search").val();
          table.search(currentVal).draw();
          $('.notexcel').css('display','block');
          $("#excel_table_filter input").val('');
          $('#autosuggestion').html('');
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
      // msoffice();    
    //   var e = 0;
    //     $('.isexcel').each(function(){
    //         $(this).attr('data-count' ,+e++);
    //         $('.kp_tabs').removeClass('active');
    //         $('.nav-link.kp_tabs[data-count="0"]').addClass('active');
    //     }); 

      var tab_file =  $('.kp_tabs').attr('data-tabfile');
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
           $('#resolte-contaniner').html(excel_file[id]);
           var table = $('#excel_table').DataTable();
           $("#excel_table_filter input").val('');
            }

      }
      else{
      
          $("#a_file").html($(this).data("file")).attr("href", file_path);

          $("#a_file").show();
          $("#file_p").show();

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
              setLangFilesPath: "https://officetohtml.js.org/pages/include/pdf/lang/locale",
              width: true,
              height:true,
              /*"include/pdf/lang/locale" - relative to app path*/
              },
              //display docx 
              //   docxSetting: {
              //     styleMap : null,
              //     includeEmbeddedStyleMap: true,
              //     includeDefaultStyleMap: true,
              //     convertImage: null,
              //     ignoreEmptyParagraphs: false,
              //     idPrefix: "",
              //     isRtl : "auto"
              //   },
              //display excel in different way 
              sheetSetting: {
              jqueryui : false,
              activeHeaderClassName: "",
              allowEmpty: true,
              autoColumnSize: true,
              autoRowSize: false,
              columns: false,
              columnSorting: true,
              contextMenu: true,
              copyable: false,
              customBorders: false,
              fixedColumnsLeft: 0,
              fixedRowsTop: 0,
              language:'en-US',
              search: true,
              selectionMode: 'single',
              sortIndicator: true,
              readOnly: true,
              startRows: 1,
              startCols: 1,
              rowHeaders: true,
              colHeaders: true,
              width: false,
              height:false
              },
          });
      }
  });


$( document ).ready(function() {

    $("#universal-search").on( 'keyup', function (e){

        var currentVal = $('#universal-search').val();

        if(currentVal !== null){

                var currentVal = $('#universal-search').val();

            $.ajax({
                        method: 'POST',
                        url: '<?php echo $urlManager->baseUrl ?>/index.php/kp/suggestion',
                        data: {currentVal : currentVal}
                    }).done(function(data){
                        console.log(data);  
                        if($.isEmptyObject(JSON.parse(data))){
                                
                            if(e.key === "Enter"){
                                $.ajax({
                                    method: 'POST',
                                    url: '<?php echo $urlManager->baseUrl ?>/index.php/kp/suggestion',
                                    data: {newVal : currentVal}
                                }).done(function(data){
                                });
                            }
                        }else{
                            var listdata = '';
                            $.each(JSON.parse(data), function( index, value ) {
                            listdata += '<a href="#" class="list list-group-item list-group-item-action">'+value+'</a>'; 
                            });
                            $('#autosuggestion').html(listdata);
                            $('.list').on('click',function(){
                            var value =$(this).text(); 
                            //alert(value);
                            $('#universal-search').val(value);
                            });
                        }     
            });
        }             
    });
    
});


}(jQuery));   
           
</script>


