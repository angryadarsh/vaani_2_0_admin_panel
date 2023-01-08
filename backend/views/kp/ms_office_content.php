<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MS Office Content</title>
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
    <script src="https://officetohtml.js.org/officeToHtml.js"></script>
    <link rel="stylesheet" href="https://officetohtml.js.org/officeToHtml.css">
</head>
<body>
    
<div class="container-fluid">
    <div class="card mt-3">
        <div class="card-body">
            <div class="clearfix mb-3 top-heading">
                <h4 class="m-0 float-left"><?= Html::encode($this->title) ?></h4>
                <div class="float-right">
                    <?= Html::a('<span class="btn btn-outline-primary btn-sm"><i class="fas fa-chevron-left"></i></span>', ['kp/index']); ?>
                </div>
                <?php //echo "<pre>";print_r($tab_name); ?>
                <?php //foreach ($tab_name as $key => $value) {
                       // echo"<pre>"; print_r($value);
               // } ?>
            </div>
            <div class="row">
                <div class="col-3">
                    <div class="nav flex-column nav-pills" role="tablist"  aria-orientation="vertical">
                   
                        <?php 
                        $count =0;
                        foreach ($getTabList as $key =>  $value){
                            if( $count == 0){
                            ?>
                            <a class="nav-link kp_tabs active" id="<?= $value['id']?>" count="<?= $count?>" data-toggle="pill" data-tabfile="<?= $value['file']?>" href="#" role="tab" aria-controls="v-pills-home"><?= $value['tab_name']?></a>
                        <?php
                            }else{
                                ?>
                                <a class="nav-link kp_tabs" id="<?= $value['id']?>" count="<?= $count?>" data-toggle="pill" data-tabfile="<?= $value['file']?>" href="#" role="tab" aria-controls="v-pills-home"><?= $value['tab_name']?></a>
                                <?php 
                            }
                            $count++;
                         }
                        ?>
                    </div>
                </div>
                <div class="col-9">
                    <div class="tab-content" id="myTabContent">
                   
                                        <?php //echo "<pre>"; print_r($tblData);
                    //$length = count($getTabList);
                    //echo"<pre>"; print_r($length);
                        // $count =0;
                        // for($count =0 ; $count<=$length;$count++)
                        //     if( $count == 0){
                            ?>
                            <div class="tab-content" id="myTabContent">
                            <div class="tab-pane show" style="" id="" role="tabpanel" count="<?= $count?>" aria-labelledby="">
                                <div id="resolte-contaniner" ></div>
                                <?php
                            //  }else{
                                ?>
                                <!-- <div class="tab-content" id="myTabContent">
                                 <div class="tab-pane" style="" id="" role="tabpanel" count="<?= $count?>" aria-labelledby="">
                                <div id="resolte-contaniner" ></div> -->
                                <?php 
                        //     }
                        //     $count++;
                        //  }
                        ?>
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
// $('.kp_tabs').attr('count','0').addClass('active');
(function ($) {
    $(".kp_tabs").on('click', function(e) {
              e.preventDefault();
              $('.tab-pane').css('display','block');
              var tab_file =  $(this).attr('data-tabfile');
              var tab_id =  $(this).attr('data-id');
              $(".sdb_holder li").removeClass("active");
              $(this).parent().addClass("active");
              var id = $(this).attr("id");
              $("#head-name").html($(this).html());
              $("#description").hide();
              $("#resolte-contaniner").html("");
              $("#resolte-contaniner").show();
              $("#resolte-text").show();
              if (id != "demo_input") {

                $("#select_file").hide();
                // var file_path = 'http://172.16.152.50/yii_basic/gaurav/new_ui2/edas_vaani_dev/backend/web/uploads/knowledge_portal/client/extension.xlsx';
                var filepath = '<?php echo Url::base(true)?>';
                var filename = tab_file;
                var file_path = filepath+"/uploads/knowledge_portal/client/"+filename;
                $("#a_file").html($(this).data("file")).attr("href", file_path);
                console.log(filename);
                $("#a_file").show();
                $("#file_p").show();

                $("#resolte-contaniner").officeToHtml({
                  url: file_path,
                  pdfSetting: {
                    // setLang: "en-US",
                    //thumbnailViewBtn: false,
                    searchBtn: true,
                    nextPreviousBtn: false,
                    pageNumberTxt: true,
                    totalPagesLabel: true,
                    zoomBtns: false,
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
                    width: true,
                    height:true,
                    setLangFilesPath: "https://officetohtml.js.org/pages/include/pdf/lang/locale",
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
                //   sheetSetting: {
                //     jqueryui : false,
                //     activeHeaderClassName: "",
                //     allowEmpty: true,
                //     autoColumnSize: true,
                //     autoRowSize: false,
                //     columns: false,
                //     columnSorting: true,
                //     contextMenu: false,
                //     copyable: false,
                //     customBorders: false,
                //     fixedColumnsLeft: 0,
                //     fixedRowsTop: 0,
                //     language:'en-US',
                //     search: true,
                //     selectionMode: 'single',
                //     sortIndicator: false,
                //     readOnly: true,
                //     startRows: 1,
                //     startCols: 1,
                //     rowHeaders: true,
                //     colHeaders: true,
                //     width: false,
                //     height:false

                //   },
                });
              } else {

                $("#select_file").show();
                $("#file_p").show();
                $("#a_file").hide();

                $("#resolte-contaniner").officeToHtml({
                  inputObjId: "select_file",
                  pdfSetting: {
                    setLang: "he",
                    //thumbnailViewBtn: false,
                    searchBtn: true,
                    nextPreviousBtn: false,
                    pageNumberTxt: true,
                    totalPagesLabel: true,
                    zoomBtns: false,
                    scaleSelector: false,
                    presantationModeBtn: false,
                    openFileBtn: false,
                    printBtn: false,
                    downloadBtn: false,
                    bookmarkBtn: false,
                    secondaryToolbarBtn: false,
                    firstPageBtn: true,
                    lastPageBtn: true,
                    pageRotateCwBtn: false,
                    pageRotateCcwBtn: false,
                    cursorSelectTextToolbarBtn: false,
                    cursorHandToolbarBtn: false,
                    width: false,
                    height:false /*"include/pdf/lang/locale" - relative to app path*/
                  },
                  docxSetting: {
                    styleMap : null,
                    includeEmbeddedStyleMap: true,
                    includeDefaultStyleMap: true,
                    convertImage: null,
                    ignoreEmptyParagraphs: false,
                    idPrefix: "",
                    isRtl : "auto"
                  },
                  sheetSetting: {
                    jqueryui : false,
                    activeHeaderClassName: "",
                    allowEmpty: true,
                    autoColumnSize: true,
                    autoRowSize: false,
                    columns: false,
                    columnSorting: true,
                    contextMenu: false,
                    copyable: false,
                    customBorders: false,
                    fixedColumnsLeft: 0,
                    fixedRowsTop: 0,
                    language:'en-US',
                    search: true,
                    selectionMode: 'single',
                    sortIndicator: false,
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
          }(jQuery));   
          
          
          
</script>


<?php
$this->registerJs("
     $('#excel_table').DataTable();
 ");
 ?>     
</body>
</html>