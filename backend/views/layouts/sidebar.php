<?php
// request url
$url_array          =  explode('/', $_SERVER['REQUEST_URI']) ;
$url                =  end($url_array); 
$tmp_url            =  explode( '?', $url);
$url                = $tmp_url[0];
// get the comman acces menu for the user
$menu = $obj_com->comman_menu($_SESSION['user_role'], $_SESSION['user_id'],'', $url);

$access             =  $menu['request_access'];
// store access of the user
$_SESSION['access'] = $menu['access'];
$user_level         = $_SESSION['user_level'];
$logo               = $menu['access']['logo']?$menu['access']['logo']:'../assets/img/client.png';
$user_name          = $_SESSION['user_name'];

function active($currect_page){
  $url_array  =  explode('/', $_SERVER['REQUEST_URI']) ;
  $url        = end($url_array);
  $resp = '';    
  if($currect_page == $url){
      $resp='active'; //class name in css 
  } 
  return $resp;
}

function open($currect_page){
  $url_array  =  explode('/', $_SERVER['REQUEST_URI']) ;
  $url        = end($url_array);
  $resp = '';  
  if($currect_page == $url){
      $resp= 'menu-open'; //class name in css 
  } 
  return $resp;
}

// this function create sub-menu

function menu_string($sb_k,$lvl='')
{
  $mn ='';
  if(!empty($lvl)) //  check the level of the menu is greater than 1
  {
    //incomplete  some modification may be required.
    $mn.=" <li class='nav-item'><a href='#' class='nav-link'>".$sb_k['menu_name']."<i class='fas fa-angle-right right'></i></a>";
    $mn.= "<ul class='nav nav-treeview'>";                        
  }
  else
  {
    
    $mn.="  <li class='nav-item ".open($sb_k['link'])."'>
              <a href='#'' class='nav-link'>
              <i class='nav-icon ".$sb_k['icon']."'></i>
              <p>              
              ".$sb_k['menu_name']."
              </p>
              <i class='fas fa-angle-right right'></i>
              </a>";
    $mn.= "<ul class='nav nav-treeview'>";
  }  
    foreach ($sb_k['sub'] as $k => $val) 
    {
      if($val['sub'])
      {
        $mn.= menu_string($val,$val['level']);
      }
      else
      {
        $mn.=  "<li class='nav-item'>
                <a href='../components/".$val['link']."' class='nav-link ".active($val['link'])."'>
                  <i class='nav-icon ".$val['icon']."'></i>
                  <p>".$val['menu_name']."</p>
                </a>";
        $mn.= "</li>";
      }
    }
  $mn.= "</ul></li>";
  return $mn;
}
?>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="../assets/img/vaani-logo.png" alt="Vaani Logo" class="brand-image elevation-3" style="opacity: .8">
      <img src="../assets/img/vaani-initial-logo.png" alt="Vaani Logo" class="brand-logo elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">&nbsp;</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src=<?=$logo;?> class="elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><div>Welcome</div> <span style="color:#c77027;"><?=ucfirst($user_name);?></span></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
           <?php
           $chk_menu_access = false;
            foreach($menu['menu'] as $key => $value) 
              {
                if($value['sub'])
                {   
                  echo menu_string($value);                  
                }
                else
                {
                  echo '<li class="nav-item">
                    <a href="../components/'.$value['link'].'" class="nav-link '.active($value['link']).'">
                      <i class="nav-icon '.$value['icon'].'"></i>
                      <p>
                      '.$value['menu_name'].'
                      </p>
                    </a>
                  </li>';
                }
                
              }

            ?>  
        </ul>
      </nav>
      


      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  <div class="alert notification-pop" id="msg"></div>

  <!-- jQuery -->
  <script src="../plugins/jquery/jquery.min.js"></script>            
  <!-- notification scrept here for access all pages -->
  <script>

    // **********************************************************************************************
    // 25-Oct-2021: Krunal
    function notification(id, alertText, nClass) {
      var cs = $(`${id}`).addClass(`${nClass}`);
      $(`${id}`).fadeIn(1000).delay(1500).fadeOut(1000).cs;
      $(`${id}`).html(alertText);

      cs.delay(100).queue(function () {
        $(this).removeClass(`${nClass}`).dequeue();
      });
    }
  </script>

<!-- check user have access for this  -->
<?php
if($access == 'false')
{
  ?>
  <script>
    notification("#msg", 'Un-authorized access!', "alert-danger");
    setTimeout(function(){
							window.location.href='dashboard.php';
						  },1700);	
  </script>
  <?php  
  exit(); 
}

?>