<?php
require_once('config.php');
require_once('function.php');
$pagename = 'users';
$pageload = langSystem($lenguage_section, 'users', 'tab_name');

require_once('header.php');

if ($user['logged'] AND has('dbb.users')) {
	
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
	window.activeTab = 'user';
	userCall();
});
</script>
<?php

require_once('footer.php');
}
?>