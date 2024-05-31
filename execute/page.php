<?php

function license_overview() {
	global $lenguage_section;
	ob_start();
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
					<input type="hidden" value="license" id="page">
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
	$pageContent = ob_get_clean();
	return $pageContent;
}

function product_overview() {
	global $lenguage_section;
	ob_start();
?>

<div class="page-body">
    <div class="container-xl">
		<div class="card col-12 mb-3">
			<div class="card-body">
				<div class="row" align="left" style="margin-bottom: 15px;">
					<div class="col" align="center">
						<input type="text" placeholder="<?php echo langSystem($lenguage_section, 'filters', 'search'); ?>" class="form-control" id="search">
					</div>
					<div class="col" align="right">
						<select id="option">
							<option value="id" selected><?php echo langSystem($lenguage_section, 'filters', 'new'); ?></option>
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
			</div>
		</div>
        <div class="row row-cards" id="load_index_result">
			<?php echo $product_loader; ?>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
	window.activeTab = 'product';
	productCall();
});
</script>

<?php
	$pageContent = ob_get_clean();
	return $pageContent;
}

?>