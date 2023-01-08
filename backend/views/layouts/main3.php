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
  	<!-- Left navbar links -->
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
			<!-- <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a> -->
			<a class="nav-link barIcon" href="#" role="button">
				<!-- <i class="fas fa-bars"></i> -->
				<span class="icon-bar top-bar"></span>
				<span class="icon-bar middle-bar"></span>
				<span class="icon-bar bottom-bar"></span>
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
		<!-- <li class="nav-item d-none d-sm-inline-block">
			<a href="index3.html" class="nav-link">Home</a>
		</li>
		<li class="nav-item d-none d-sm-inline-block">
			<a href="#" class="nav-link">Contact</a>
		</li> -->
	</ul>

	<!-- client connection for the superadmin -->
	<?php 
	$no_client_list = ['client/index', 'client/create', 'client/update'];

	if($user->userRole && strtoUpper($user->userRole->role_name) == 'SUPERADMIN' && !in_array($url, $no_client_list)){ ?>
		<ul class="navbar-nav ml-auto client_connection_section">
			<li>
				<div class="row">
				<div class="col-lg-12">
					<div class="form-group row">
						<div class=" col-lg-4 pr-0 text-right">
							<label class="label-control mt-1" for="client_connection" style="background:none;">Client</label>
						</div>
						<div class="col-lg-8">
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
			</li>
		</ul>
	<?php } ?>

  	<!-- Right navbar links -->
  	<ul class="navbar-nav ml-auto">
		<li class="p-10">
			<span id="real-date-section"></span>
			<span id="real-time-section"></span>
		</li>
		  <!-- <li>
			<div title="Dark Mode Switch" class="dark-mode-toggle">
				<input type="checkbox" id="dark-mode-checkbox">
				<label for="dark-mode-checkbox">
					<div class="toggle-button tran">
						<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="moon" class="svg-inline--fa fa-moon fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
							<path fill="currentColor" d="M283.211 512c78.962 0 151.079-35.925 198.857-94.792 7.068-8.708-.639-21.43-11.562-19.35-124.203 23.654-238.262-71.576-238.262-196.954 0-72.222 38.662-138.635 101.498-174.394 9.686-5.512 7.25-20.197-3.756-22.23A258.156 258.156 0 0 0 283.211 0c-141.309 0-256 114.511-256 256 0 141.309 114.511 256 256 256z"></path>
						</svg>
						<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sun" class="svg-inline--fa fa-sun fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
							<path fill="currentColor" d="M256 160c-52.9 0-96 43.1-96 96s43.1 96 96 96 96-43.1 96-96-43.1-96-96-96zm246.4 80.5l-94.7-47.3 33.5-100.4c4.5-13.6-8.4-26.5-21.9-21.9l-100.4 33.5-47.4-94.8c-6.4-12.8-24.6-12.8-31 0l-47.3 94.7L92.7 70.8c-13.6-4.5-26.5 8.4-21.9 21.9l33.5 100.4-94.7 47.4c-12.8 6.4-12.8 24.6 0 31l94.7 47.3-33.5 100.5c-4.5 13.6 8.4 26.5 21.9 21.9l100.4-33.5 47.3 94.7c6.4 12.8 24.6 12.8 31 0l47.3-94.7 100.4 33.5c13.6 4.5 26.5-8.4 21.9-21.9l-33.5-100.4 94.7-47.3c13-6.5 13-24.7.2-31.1zm-155.9 106c-49.9 49.9-131.1 49.9-181 0-49.9-49.9-49.9-131.1 0-181 49.9-49.9 131.1-49.9 181 0 49.9 49.9 49.9 131.1 0 181z"></path>
						</svg>
					</div>
				</label>
			</div>
		  </li> -->

		<li class="">
			<?= Html::a('<i class="fas fa-power-off"></i>', ['site/logout'], ['class' => 'nav-link', 'data-method' => 'POST']) ?>
		</li>
		<li class="">
			<?= Html::a('<i class="fas fa-key"></i>', ['site/change-password'], ['class' => 'nav-link', 'title' => 'Change Password']) ?>
		</li>
	</ul>
</nav>

<main role="main" class="flex-shrink-0">

    <!-- dynamic menu -->
    <aside class="main-sidebar sidebar-dark-primary">
        

        <!-- Sidebar -->
        <div class="sidebar">
          <!-- Sidebar user panel (optional) -->

          <!-- Sidebar Menu -->
          <nav>
			  <!-- <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false"> -->
			  <?php
			//   echo "<pre>";print_r(MenuHelper::getAssignedMenu(Yii::$app->user->id));exit;
					/* echo Nav::widget([
						'items' => MenuHelper::getAssignedMenu(Yii::$app->user->id),
						'options' => [
							'class' =>'nav nav-pills nav-sidebar flex-column',
							'data-widget' => 'treeview',
							'role' => 'menu',
							'data-accordion' => false
						],
						'encodeLabels' => false,
					]); */

					/* echo \yii\widgets\Menu::widget([
						'items' => MenuHelper::getAssignedMenu(Yii::$app->user->id),
						'options' => [
							'class' => 'nav nav-pills nav-sidebar flex-column',
							'data-widget' => 'treeview',
							'role' => 'menu',
							'data-accordion' => false
						],
						'submenuTemplate' => "\n<ul class='nav nav-treeview'>\n{items}\n</ul>\n",
						'encodeLabels' => false, //allows you to use html in labels
						// 'activateParents' => true,
					]); */ ?>
            	<!-- </ul> -->

            	<ul class="nav nav-pills nav-sidebar" data-widget="treeview" role="menu" data-accordion="false">
				<?php $check_url = '/' . $url;
				foreach ($access_menus['menu'] as $key => $menu_item){
					// while($menu_item['sub']){
						if(!empty($menu_item['sub'])){ ?>
							<li class="nav-item ">
								<?= Html::a('<i class="nav-icon '.$menu_item['icon'].'"></i> <p>'.$menu_item['menu_name'] . '</p> <i class="fas fa-angle-right right"></i> ', '#', ['class' => 'nav-link '.(($url && $check_url == $menu_item['route']) ? 'active' : '')]) ?>
							
								<ul class="nav nav-treeview">
									<?php foreach ($menu_item['sub'] as $k => $menu_child){ ?>
										<!-- <li class="nav-item">
											<?php // Html::a('<i class="nav-icon '.$menu_child['icon'].'"></i> '.$menu_child['menu_name'], [$menu_child['route']], ['class' => 'nav-link '.(($url && $check_url == $menu_child['route']) ? 'active' : '')]) ?>
										</li> -->
										
											<?php if(isset($menu_child['sub'])){ ?>

												<li class="nav-item ">
													<?= Html::a('<i class="nav-icon '.$menu_child['icon'].'"></i> <p>'.$menu_child['menu_name'] . '</p> <i class="fas fa-angle-right right"></i> ', '#', ['class' => 'nav-link '.(($url && $check_url == $menu_child['route']) ? 'active' : '')]) ?>

													<ul class="nav nav-treeview">
														<?php foreach ($menu_child['sub'] as $k2 => $menu_child2){ ?>
															<li class="nav-item">
																<?= Html::a(''.$menu_child2['menu_name'], [$menu_child2['route']], ['class' => 'nav-link '.(($url && $check_url == $menu_child2['route']) ? 'active' : '')]) ?>
															</li>
														<?php } ?>
													</ul>
												</li>
											<?php }else{ ?>
												<li class="nav-item">
													<?= Html::a(''.$menu_child['menu_name'], [$menu_child['route']], ['class' => 'nav-link '.(($url && $check_url == $menu_child['route']) ? 'active' : '')]) ?>
												</li>
											<?php } ?>
									<?php } ?>
								</ul>
							</li>
						<?php }else{ ?>
								<li class="nav-item">
									<?= Html::a('<i class="nav-icon '.$menu_item['icon'].'"></i> '.$menu_item['menu_name'], [$menu_item['route']], ['class' => 'nav-link '.(($url && $check_url == $menu_item['route']) ? 'active' : '')]) ?>
								</li>
						<?php } ?>
					<?php } ?>
				<?php //} ?>
				<?php
           			/* $chk_menu_access = false;
            		foreach($menu['menu'] as $key => $value) 
              		{
                		if($value['sub'])
						{   
							echo menu_string($value);                  
						}
						else
						{
							echo '<li class="nav-item">'.
								Html::a('<i class="nav-icon fas fa-sitemap"></i> Monitoring', ['/report/monitoring'], ['class' => 'nav-link '.(($url && $url=='report/monitoring') ? 'active' : '')])
							<a href="../components/'.$value['link'].'" class="nav-link '.active($value['link']).'">
								<i class="nav-icon '.$value['icon'].'"></i>
								<p>
								'.$value['menu_name'].'
								</p>
								</a>
							'</li>';
                		}
              		} */
            	?>
					<!-- <li class="nav-item">
						<?= Html::a('<i class="nav-icon fas fa-users"></i> Clients', ['/client/index'], ['class' => 'nav-link '.(($url && $url=='client/index') ? 'active' : '')]) ?>
					</li>
					<li class="nav-item">
						<?= Html::a('<i class="nav-icon fas fa-table"></i> DNI', ['/dni/index'], ['class' => 'nav-link '.(($url && $url=='dni/index') ? 'active' : '')]) ?>
					</li>
					<li class="nav-item">
						<?= Html::a('<i class="nav-icon fas fa-table"></i> Campaigns', ['/campaign/index'], ['class' => 'nav-link '.(($url && $url=='campaign/index') ? 'active' : '')]) ?>
					</li>
					<li class="nav-item">
						<?= Html::a('<i class="nav-icon fas fa-user"></i> Users', ['/user/index'], ['class' => 'nav-link '.(($url && $url=='user/index') ? 'active' : '')]) ?>
					</li> -->
					<!-- <li class="nav-item ">
						<?= Html::a('<i class="nav-icon fas fa-user"></i> <p>Users</p> <i class="fas fa-angle-right right"></i> ', '#', ['class' => 'nav-link ']) ?>
						
						<ul class="nav nav-treeview">
							<li class="nav-item ">
								<?= Html::a('<i class="nav-icon fas fa-user"></i> Add User', ['/user/create'], ['class' => 'nav-link '.(($url && $url=='user/create') ? 'active' : '')]) ?>
							</li>
							<li class="nav-item ">
								<?= Html::a('<i class="nav-icon fas fa-table"></i> User List', ['/user/index'], ['class' => 'nav-link '.(($url && $url=='user/index') ? 'active' : '')]) ?>
							</li>
						</ul>
					</li> -->
					<!-- <li class="nav-item ">
						<?= Html::a('<i class="nav-icon fas fa-male"></i> <p>Roles</p>', ['/role/index'], ['class' => 'nav-link '.(($url && $url=='role/index') ? 'active' : '')]) ?>
					</li>
					<li class="nav-item ">
						<?= Html::a('<i class="nav-icon fas fa-user-tag"></i> <p>Roles Access</p>', ['/role/access'], ['class' => 'nav-link '.(($url && $url=='role/access') ? 'active' : '')]) ?>
					</li> -->
					<!-- <li class="nav-item ">
						<?= Html::a('<i class="nav-icon fas fa-sitemap"></i> <p>Menus</p>', ['/admin/menu'], ['class' => 'nav-link '.(($url && $url=='admin/menu') ? 'active' : '')]) ?>
					</li> -->
					<!-- <li class="nav-item">
						<?= Html::a('<i class="nav-icon fas fa-address-card"></i> Monitoring', ['/report/monitoring'], ['class' => 'nav-link '.(($url && $url=='report/monitoring') ? 'active' : '')]) ?>
					</li>  
					<li class="nav-item">
						<?= Html::a('<i class="nav-icon fas fa-table"></i> Call Report', ['/report/agent-call-monitoring'], ['class' => 'nav-link '.(($url && $url=='report/agent-call-monitoring') ? 'active' : '')]) ?>
					</li> -->
					<!-- <li class="nav-item ">
						<a href="#" class="nav-link">
							<i class="nav-icon fas fa-table"></i>
							<p>              
							Campaign
							</p>
							<i class="fas fa-angle-right right"></i>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item">
								<a href="#" class="nav-link ">
									<i class="nav-icon fas fa-list"></i>
									<p>Inbound</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link ">
									<i class="nav-icon fas fa-table"></i>
									<p>Add Campaign</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link ">
									<i class="nav-icon "></i>
									<p>User List</p>
								</a>
							</li>
						</ul>
					</li> --> 
					<!-- <li class="nav-item">
						<a href="#" class="nav-link ">
							<i class="nav-icon fas fa-user-tag"></i>
							<p>
							Define Role
							</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="#" class="nav-link ">
							<i class="nav-icon far fa-address-card"></i>
							<p>
							Menu Master
							</p>
						</a>
					</li>  
					<li class="nav-item ">
						<a href="#" class="nav-link">
							<i class="nav-icon fas fa-table"></i>
							<p>              
							Reports
							</p>
							<i class="fas fa-angle-right right"></i>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item">
								<a href="#" class="nav-link ">
									<i class="nav-icon fas fa-table"></i>
									<p>Test</p>
								</a>
							</li>
						</ul>
					</li>  --> 
            	</ul>
          	</nav>
          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
    <!-- end of dynamic menu -->

    <div class="content-wrapper">
        <?= Breadcrumbs::widget([
			'homeLink' => [
				'label' => 'Home',
				'url' => ['report/monitoring'],
			],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>

    <footer class="footer main-footer text-muted">
      <div class="clearfix">
        <p class="float-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-right">Powered by <?= Html::a('eDAS', 'https://www.edas.tech/', ['target' => '_blank']) ?></p>
      </div>
    </footer>
</main>

<script type="text/javascript">

</script>

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

	$('#client_connection').on('change', function(){
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
");

?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
