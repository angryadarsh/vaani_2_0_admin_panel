<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use common\models\User;
use common\models\VaaniSession;
use common\models\VaaniClientMaster;
use common\models\VaaniMenu;
use mdm\admin\components\MenuHelper;
use yii\helpers\ArrayHelper;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel='icon' type='image/x-icon' href="<?= Yii::$app->request->baseUrl . '/images/favicon.png' ?>">
	<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.1.min.js"></script> -->
	<script src="../../js/jquery.js"></script>

	
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<?php
$user = Yii::$app->user->identity;

$client_list = ArrayHelper::map(VaaniClientMaster::clientsList(), 'client_id', 'client_name');
if(!isset($_SESSION['client_connection']) || !$_SESSION['client_connection'])
{
	$_SESSION['client_connection'] = key($client_list);
}

if($user->userRole && strtoUpper($user->userRole->role_name) != 'SUPERADMIN'){
	$user_client = ($user->userAccess ? $user->userAccess[0]->client : null);
	if($user_client){
		$db_name = User::decrypt_data($user_client->db);
		$db_host = User::decrypt_data($user_client->server);
		$db_username = User::decrypt_data($user_client->username);
		$db_password = User::decrypt_data($user_client->password);

		\Yii::$app->db->close(); // make sure it clean
		\Yii::$app->db->dsn = 'mysql:host='.$db_host.';dbname='.$db_name;
		\Yii::$app->db->username = $db_username;
		\Yii::$app->db->password = $db_password;
	}
}else if(isset($_SESSION['client_connection']) && $_SESSION['client_connection']){
	$client = VaaniClientMaster::find()->where(['client_id' => $_SESSION['client_connection']])->one();

	$db_name = User::decrypt_data($client->db);
	$db_host = User::decrypt_data($client->server);
	$db_username = User::decrypt_data($client->username);
	$db_password = User::decrypt_data($client->password);

	\Yii::$app->db->close(); // make sure it clean
	\Yii::$app->db->dsn = 'mysql:host='.$db_host.';dbname='.$db_name;
	\Yii::$app->db->username = $db_username;
	\Yii::$app->db->password = $db_password;
}

// get the comman acces menu for the user
$url = Yii::$app->requestedRoute;
$create_url = Yii::$app->controller->id . '/create';
$index_url = Yii::$app->controller->id . '/index';

$access_menus = User::userMenus('', [$url, $create_url, $index_url]);
// echo "<pre>";print_r($access_menus);exit;
if(empty($access_menus['access'])){
	$_SESSION['logout_message'] = 'User has no access!';
	User::autoLogout();
}

$logo = $access_menus['access']['logo'] ? $access_menus['access']['logo'] : Yii::$app->request->baseUrl . '/images/client.png';

$urlManager = Yii::$app->urlManager;

if((($user->userRole && strtolower($user->userRole->role_name) != 'superadmin') && strtolower($user->role) != 'superadmin') && !($access_menus['request_access'][$url] || $access_menus['request_access'][$create_url] || $access_menus['request_access'][$index_url])) {
	$this->registerJs("
		Swal.fire('Un-authorized access!', '', 'alert');
		window.location.href='". $urlManager->baseUrl . '/index.php/campaign/index' ."';
	");
}

// fetch role action access
/* $user_campaigns = $user->campaignIds;
$user_queues = $user->queueIds;
$role_action_access = User::roleWiseMenus($user->role_id, $_SESSION['client_connection'], $user_campaigns, $user_queues);

$role_menu_ids = ArrayHelper::getColumn($role_action_access, 'menu_ids'); */
// echo "<pre>";print_r($role_action_access);exit;
?>

<!-- loader  -->
<div id="LoadingBox">
    <!-- <p>Loading...</p> -->
    <div class="logo-wrap tran">
      <img class="edas-logo img-responsive" src="<?= Yii::$app->request->baseUrl . '/images/vaani-logo.png' ?>" alt="logo">
      <svg class="tran" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
          style="background: rgba(255, 255, 255, 0); display: block;" width="100%" height="200px"
          viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
          <circle cx="50" cy="50" r="25" stroke-width="3" stroke="#fff"
              stroke-dasharray="39.269908169872416 39.269908169872416" fill="none" stroke-linecap="round">
              <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite"
                  dur="0.6666666666666666s" keyTimes="0;1" values="0 50 50;360 50 50"></animateTransform>
          </circle>
      </svg>
	  <span class="customized_loader_text h4"><span></span></span>
	  <span class="my-box"><p></p></span>
    </div>
</div>
<!-- loader  -->

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
	<ul class="navbar-nav">
		<li class="nav-item">
			<!-- Brand Logo -->
			<a href="<?= $urlManager->baseUrl . '/index.php/report/monitoring' ?>" class="brand-link">
				<img src="<?= Yii::$app->request->baseUrl . '/images/vaani-logo.png' ?>" alt="Vaani Logo" class="brand-image">
				<img src="<?= Yii::$app->request->baseUrl . '/images/vaani-initial-logo.png' ?>" alt="Vaani Logo" class="brand-logo elevation-3" style="opacity: .8">
				<span class="brand-text font-weight-light">&nbsp;</span>
			</a>
		</li>
		<li class="nav-item">
			<div class="user-panel d-flex">
				<!-- <div class="image">
					<img src="<?= Yii::$app->request->baseUrl . '/images/vaani-initial-logo.png' ?>" class="elevation-2" alt="User Image">
				</div> -->
				<div class="info">
					<div>Welcome</div> <span><?= ucwords(Yii::$app->user->identity->user_name) ?></span>
				</div>
			</div>
		</li>
	</ul>
  	<!-- Right navbar links -->
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<!-- Left navbar links -->
		<ul class="navbar-nav menu-list m-auto">
			<li class="nav-item d-none">
				<!-- <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a> -->
				<a class="nav-link barIcon" href="#" role="button">
					<!-- <i class="fas fa-bars"></i> -->
					<span class="icon-bar top-bar"></span>
					<span class="icon-bar middle-bar"></span>
					<span class="icon-bar bottom-bar"></span>
				</a>
			</li>

			<!-- MENU LIST -->
			<?php $check_url = $url;
			foreach (VaaniMenu::$default_menus as $menu_name => $sub_menus) { ?>
				<li class="nav-item dropdown <?= (in_array($check_url, $sub_menus) ? 'active' : '') ?>">
					<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
					<?= $menu_name ?>
					</a>
					<div class="dropdown-menu">
						<?php foreach ($sub_menus as $submenu_name => $submenu_route) { ?>
							<?= Html::a($submenu_name, ($submenu_route ? [$submenu_route] : null), ['class' => 'dropdown-item ' .  (($check_url == $submenu_route) ? 'active' : '')]) ?>
						<?php } ?>
					</div>
				</li>
			<?php } ?>
			
		</ul>
		<ul class="navbar-nav my-2 my-lg-0 mr-3">
			<li class="nav-item">
				<?php 
					$no_client_list = ['client/index', 'client/create', 'client/update'];
					if($user->userRole && strtoUpper($user->userRole->role_name) == 'SUPERADMIN' && !in_array($url, $no_client_list)){ ?>
					<span data-toggle="tooltip" data-placement="top" title="Client">
						<a class="nav-link" href="#" data-toggle="modal" data-target="#Modal"><i class="far fa-handshake"></i></a>
					</span>
				<?php }?>
			</li>
			<li class="p-10 d-none">
				<span id="real-date-section"></span>
				<span id="real-time-section"></span>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle profile-icon" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
					<i class="fas fa-user"></i>
				</a>
				<div class="dropdown-menu">
					<?= Html::a('<i class="fas fa-key"></i> Change Password', ['site/change-password'], ['class' => 'dropdown-item', 'title' => 'Change Password']) ?>
					<?= Html::a('<i class="fas fa-power-off"></i> Logout', ['site/logout'], ['class' => 'dropdown-item logout-btn', 'data-method' => 'POST']) ?>
				</div>
			</li>
		</ul>
 	 </div>
</nav>

<main role="main" class="flex-shrink-0">

    <!-- dynamic menu -->
    
    <!-- end of dynamic menu -->

    <div class="content-wrapper">
        <?= Breadcrumbs::widget([
			'homeLink' => [
				'label' => 'Home',
				'url' => ['report/monitoring'],
			],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
		
		<script>

			// var msg ='<?php //echo Alert::widget();?>';
			// $('+msg').animate({opacity: 1.0}, 3000).fadeOut("slow");

		</script>
        <?= Alert::widget(); ?>
        <?= $content ?>
    </div>

    <footer class="footer main-footer text-muted">
      <div class="clearfix">
        <p class="float-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-right">Powered by <?= Html::a('eDAS', 'https://www.edas.tech/', ['target' => '_blank']) ?></p>
      </div>
    </footer>
</main>

<!-- Modal -->
<div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<div class="modal-header">
				<h5 class="modal-title" id="ModalLabel">Client</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
      		</div>
      		<div class="modal-body">
	  		<?php 
				$no_client_list = ['client/index', 'client/create', 'client/update'];

				if($user->userRole && strtoUpper($user->userRole->role_name) == 'SUPERADMIN' && !in_array($url, $no_client_list)){ ?>
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group row">
							<div class=" col-lg-3 pr-0 text-center">
								<!-- <label class="label-control mt-1" for="client_connection" style="background:none;">Client</label> -->
							</div>
							<div class="col-lg-6">
								<select name="client_connection" id="client_connection" class="form-control">
									<!-- <option value="">All</option> -->
									<?php 
									foreach ($client_list as $client_id => $client_name) {
										$checked = '';
										if(isset($_SESSION['client_connection']) && $_SESSION['client_connection'] && $client_id == $_SESSION['client_connection']){
											$checked = 'selected';
										} ?>
										<option value="<?= $client_id ?>" <?= $checked ?>><?= $client_name ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
			


<?php
// echo "<pre>";print_r($_SESSION);exit;
if(isset($_SESSION['timestamp']) && !empty($_SESSION['user_id']) && (time() - $_SESSION['timestamp'] > 2700)) {
	//subtract new timestamp from the old one
	// User::autoLogout(false, true);
} else if(isset($_SESSION['timestamp']) && !empty($_SESSION['user_id'])) 
{ 
	// elseif(isset($_SESSION['timestamp']) && !empty($_SESSION['user_id']) && (time() - $_SESSION['timestamp'] < 1800)) // 30 minute between 
	// update session table last_action_epoch.
	VaaniSession::updateEpochTime($_SESSION['sid'], $_SESSION['user_id']);
} else {
	$_SESSION['timestamp'] = time(); //set new timestamp
}

$this->registerJs("
	
	if($('.nav-treeview .nav-link').hasClass('active')){
		$('.nav-treeview .nav-link.active').parent().parent().parent().addClass('menu-is-opening menu-open');
		$('.nav-treeview .nav-link.active').parent().parent().css('display', 'block');
		$('.nav-treeview .nav-link.active').parent().prev().children().addClass('prev-hover-border-radius');
		$('.nav-treeview .nav-link.active').parent().next().children().addClass('next-hover-border-radius');
	}
	
	// HANDLE MENU CUSTOM TOGGLE EVENTS
	$('.nav-sidebar .nav-link').on('click', function(){
		menu_flag = false;
		if($(this).parent().hasClass('menu-open')){
			menu_flag = true;
		}else{
		}

		$(this).parent().parent().find('.nav-treeview').hide();
		$(this).parent().parent().find('.nav-item').removeClass('menu-is-opening menu-open');
		
		if(menu_flag){
			$(this).next().slideDown();
			$(this).parent().addClass('menu-is-opening menu-open');
		}else{
			$(this).next().slideUp();
			$(this).parent().removeClass('menu-is-opening menu-open');
		}

	});

// darkmode set 06-apr-2022 +++++++++++++++++++++++++++++++++++++++.
function setCookie(cname, cvalue, exdays) {
  const d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  let expires = 'expires='+d.toUTCString();
  document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
}

	$('#dark-mode-checkbox').click( function(){
		$('body').toggleClass('dark-mode');
		if($('body').hasClass('dark-mode'))
		{
			setCookie('darkmode','true');
		}
		else
		{
			setCookie('darkmode','false');
		}
		
	});
	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++.
");

// show real-time date & time
$this->registerJs("
	$(document).ready(function() {
		function getCookie(cname) {
			console.log('getCookie');
	  let name = cname + '=';
	  let ca = document.cookie.split(';');
	  for(let i = 0; i < ca.length; i++) {
	    let c = ca[i];
	    while (c.charAt(0) == ' ') {
	      c = c.substring(1);
	    }
	    if (c.indexOf(name) == 0) {
	      return c.substring(name.length, c.length);
	    }
	  }
	  return '';
	}

	

		var interval = setInterval(function() {
			var momentNow = moment();
			$('#real-date-section').html(momentNow.format('dddd') + ', '
			+ momentNow.format('MMMM D, YYYY'));
			$('#real-time-section').html(momentNow.format('hh:mm:ss A'));
		}, 100);

		// set body color mode 06-apr-2022 +++++++++++++++++++++++++++++++++++++++.
		let bodymode = getCookie('darkmode');	
		if(bodymode == 'true')
		{		
			$('#dark-mode-checkbox').prop('checked', true);	
			$('body').addClass('dark-mode');
		}
		else
		{
			console.log('remove mode');
			$('body').removeClass('dark-mode');
		}
		// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	});

	
");

$this->registerJs("

	function connect_client_connection(client_id)
	{
		$.ajax({
            method: 'POST',
            url: '".$urlManager->baseUrl . '/index.php/site/set-client-connection'."',
            data: {client_id: client_id}
        }).done(function(){
			window.location.reload();
		});
	}

	$(document).ready(function() {
		var client_connection = $('#client_connection').val();

		if(client_connection){
			// connect_client_connection(client_connection);
		}
	});

	$('#client_connection').change( function(){
		client_connection = $(this).val();
		connect_client_connection(client_connection);
	});

	// ON CLICK OF OUTSIDE, HIDE THE OPENED MENU
	$('.content-wrapper').click(function(){
		$('.main-sidebar').slideUp();
		$('.barIcon').removeClass('opened');
	});

	// ON CLICK OF MENU ICON, HIDE/SHOW THE MENUS
	$('.barIcon').click(function(){
		$('.main-sidebar').slideToggle();
		$('.barIcon').toggleClass('opened');
	});

	// ON CLICK OF MENU, HIGHLIGHT THE MENU AND REMOVE OTHER ACTIVE CLASSES
	$('.dropdown-item').on('click', function(){
		$('.dropdown-item').removeClass('active');
		$('.nav-item .dropdown').removeClass('active');
		$(this).addClass('active');
		$(this).parent().parent().addClass('active');
	});
	
	$( document ).ready(function() {
		$('#w2-success-0').fadeOut(7000);

	});
");

?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
