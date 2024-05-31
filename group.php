<?php
session_start();
require_once('config.php');
require_once('function.php');
$pagename = 'group';
$pageload = langSystem($lenguage_section, 'group', 'tab_name');

$get = $_GET['q'];
require_once('header.php');

if ($user['logged'] AND unique_perm('unique.group')) {
	
	if (!$get) {
?>
<div class="container-xl">
    <div class="card col-12 mb-3">
		<div class="card-body">
			<div class="row" align="left" style="margin-bottom: 15px;">
				<div class="col" align="center">
					<input type="text" placeholder="<?php echo langSystem($lenguage_section, 'filters', 'search'); ?>" class="form-control" id="search">
				</div>
				<div class="col" align="right">
					<select id="option">
						<option value="1" selected><?php echo langSystem($lenguage_section, 'filters', 'new'); ?></option>
						<option value=""><?php echo langSystem($lenguage_section, 'filters', 'old'); ?></option>
					</select>
					<select id="total">
						<option value="20" selected>20</option>
						<option value="60">60</option>
						<option value="100">100</option>
						<option value="200">200</option>
						<option value="500">500</option>
						<option value="99999"><?php echo langSystem($lenguage_section, 'filters', 'count'); ?></option>
					</select>
					<input type="hidden" value="1" id="paginationID">
				</div>
			</div>
			<div id="load_index_result"><?php echo $table_loader; ?></div>
		</div>
	</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
	window.activeTab = 'group';
	groupCall();
});
</script>
<?php
	} else {
		
	if (DB_TYPE == 'MONGODB') {
		$filter = ['id' => $get];
		$cursor = $groups_db->findOne($filter);
		if ($cursor > 0) $result = 1; else $result = 0;
	} else if (DB_TYPE == 'MYSQL') {
		$dataUser = $connx->prepare("SELECT * FROM `u_groups` WHERE `id` = ?");
		$dataUser->bindParam(1, $get);
		$dataUser->execute();
		$member = $dataUser->fetch(PDO::FETCH_ASSOC);
		if ($member > 0) $result = 1; else $result = 0;
	}
	
	if (!$result) {
		echo 'Srry, The group does not exist.';
		exit();
	}
?>

<div class="container">
	<div class="bg-image" style="background-image: url('<?php echo BACKGROUND; ?>');min-height: 20vh;border-radius: 0px 0px 10px 10px;">
	  <div class="mask">
		<div class="d-flex justify-content-center align-items-center" style="bottom: 4vh;">
		  <img src="<?php echo IMAGE_LOGO; ?>" width="128">
		  <h5 class="text-white mb-3" style="text-transform: uppercase; font-size: 40px; " align="center">
		  <b style="letter-spacing: .2rem;"><?php echo SITE_NAME; ?></b>
		  <p class="text-white" style="text-transform: uppercase; font-size: 20px; margin-top: 1px; font-family: courier;"><?php echo langSystem($lenguage_section, 'group', 'title'); ?></p>
		  </h5>
		</div>
	  </div>
	</div>
    <div class="col-12" style="margin-top: 15px;">
		<div class="row" align="left" style="margin-bottom: 15px;">
			<div class="col" align="center">
				<input type="text" placeholder="<?php echo langSystem($lenguage_section, 'filters', 'search'); ?>" class="form-control" id="searcher">
			</div>
			<div class="col" align="right">
				<select id="whereList">
					<option value="1" selected><?php echo langSystem($lenguage_section, 'filters', 'new'); ?></option>
					<option value=""><?php echo langSystem($lenguage_section, 'filters', 'old'); ?></option>
				</select>
				<select id="totalInList">
					<option value="20" selected>20</option>
					<option value="60">60</option>
					<option value="100">100</option>
					<option value="200">200</option>
					<option value="500">500</option>
					<option value="99999"><?php echo langSystem($lenguage_section, 'filters', 'count'); ?></option>
				</select>
				
				<input type="hidden" value="1" id="paginationID">
				<input type="hidden" value="<?php echo $get; ?>" id="groupID">
			</div>
		</div>
		<div id="load_index_result">
			<div style="min-height: 80vh; margin-top: 250px;" align="center">
				<span class="spinner spinner-large spinner-blue spinner-slow"></span>
			</div>
		</div>
	</div>
</div>
<script>
var e = document.getElementById('totalInList');
var w = document.getElementById('whereList');
var s = document.getElementById('searcher');

function indexLoad() {
	var langs = 'permissions-table';
	var viewingTotals = e.options[e.selectedIndex].value;
	var whereLists = w.options[w.selectedIndex].value;
	var searchs = s.value;
	var pagination = document.getElementById('paginationID').value;
	var groupID = document.getElementById('groupID').value;
    $.post( site_domain + '/execute/group.php', { apply : langs, viewingTotal : viewingTotals, where : whereLists, search : searchs, pagination : pagination, groupID : groupID }, 
       function( response ) {
		   
		document.getElementById("load_index_result").innerHTML = response;

       }
    );
}

function updatePage(id) {
	var paginationID = document.getElementById('paginationID');
	paginationID.value = id;
	indexLoad();
}



function actionToPermissionDel(id) {
	var langs = 'permissions_delete';
    $.post( site_domain + '/execute/group.php', { apply : langs, id : id }, 
       function( response ) {
                var jsonData = JSON.parse(response);
                if (jsonData.success == 1) {
					swal ( "Oops" ,  jsonData.message ,  "error" );
                } else if (jsonData.success == 3) {
					swal ( "Ooh" ,  jsonData.message ,  "warning" );
				} else {
					indexLoad();
					swal ( "Yes!" ,  jsonData.message ,  "success" );
                }
       }
    );
}

e.onchange = indexLoad;
w.onchange = indexLoad;
s.onchange = indexLoad;
s.oninput = indexLoad;
document.onload = indexLoad();
</script>
<?php
	}

require_once 'footer.php';
}

?>