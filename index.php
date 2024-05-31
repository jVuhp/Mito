<?php
session_start();
require_once('config.php');
require_once('function.php');
$pagename = 'home';
$pageload = langSystem($lenguage_section, 'home', 'tab_name');

if (!$inconfigured) {

require_once('header.php');

if ($user['logged']) {
?>

<div class="container-xl">
	<div class="page-header mb-3">
	  <div class="row align-items-center">
		<div class="col">
		  <div class="page-pretitle">
			Overview
		  </div>
		  <h2 class="page-title">
			Your License
		  </h2>
		</div>
		<div class="col-auto ms-auto"></div>
	  </div>
	</div>
    <div class="card col-12 mb-3">
		<div class="card-body">
			<div class="row" align="left" style="margin-bottom: 15px;">
				<div class="col" align="center">
					<input type="text" placeholder="<?php echo langSystem($lenguage_section, 'filters', 'search'); ?>" class="form-control" id="search">
				</div>
				<div class="col" align="right">
					<select class="form-select" style="width: 20%;" id="total">
						<option value="20" selected>20</option>
						<option value="60">60</option>
						<option value="100">100</option>
						<option value="200">200</option>
						<option value="500">500</option>
                    </select>
					<select id="option" hidden>
						<option value="1" selected><?php echo langSystem($lenguage_section, 'filters', 'new'); ?></option>
						<option value=""><?php echo langSystem($lenguage_section, 'filters', 'old'); ?></option>
					</select>
					<input type="hidden" value="1" id="paginationID">
					<input type="hidden" value="home" id="page">
				</div>
			</div>
			<div id="load_index_result"><?php echo $table_loader; ?></div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	window.activeTab = 'license';
	licenseCall();
});
</script>
<?php

require_once('footer.php');

}
}
?>