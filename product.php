<?php
session_start();
require_once('config.php');
require_once('function.php');
$pagename = 'product';
$pageload = langSystem($lenguage_section, 'product', 'tab_name');

require_once('header.php');

$query = $_GET['q'];
$query_id = $_GET['id'];

if ($user['logged'] AND unique_perm('unique.product')) {
	if (!isset($query)) {
?>

<div class="page-body">
    <div class="container-xl">
		<div class=" col-12 mb-3">
			<div class="">
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

	} else {
		switch (DB_TYPE) {
			case 'MONGODB':
				$filter = ['id' => $query_id];
				$cursor = $product_db->find($filter);
				foreach ($cursor as $documents) {
					echo '<script> location.href="' . $documents['link'] . '"; </script>';
				}
				break;
			case 'MYSQL':
				$productList = $connx->prepare("SELECT * FROM `u_product` WHERE `id` = ?");
				$productList->execute([$query_id]);
				if ($documents = $productList->fetch(PDO::FETCH_ASSOC)) {
					echo '<script> location.href="' . $documents['link'] . '"; </script>';
				}
				break;
			default:
				echo "Unsupported database type.";
				return;
		}

		
		
	}
require_once 'footer.php';
}

?>