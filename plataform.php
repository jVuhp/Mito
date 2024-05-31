<?php
session_start();
require_once('config.php');
require_once('function.php');
$pagename = 'plataform';
$pageload = langSystem($lenguage_section, 'plataform', 'tab_name');

require_once('header.php');

$uri = $_GET['q'];
$page = explode('/', $uri);

if ($user['logged'] AND unique_perm('unique.plataform')) {
?>
<div class="container-xl">

	<div class="col-12 mb-3">
        <div class="card">
            <div class="card-body">
                <ul class="pagination ">
                      <li class="page-item page-prev <?php echo (!$page[0]) ? 'disabled' : '';?>" id="plataform_btn_container">
                        <a class="page-link" href="#" id="platform_btn">
                          <div class="page-item-subtitle">tab</div>
                          <div class="page-item-title">Plataform</div>
                        </a>
                      </li>
                      <li class="page-item page-next <?php echo ($page[0] == 'request') ? 'disabled' : '';?>" id="request_btn_container">
                        <a class="page-link" href="#" id="request_btn">
                          <div class="page-item-subtitle">tab</div>
                          <div class="page-item-title">Request</div>
                        </a>
                      </li>
                </ul>
            </div>
        </div>
    </div>
	
    <div class="card">
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
			<div id="load_index_result" <?php echo (!$page[0]) ? 'style="display: block !important;"' : 'style="display: none !important;"';?>><?php echo $table_loader; ?></div>
			<div id="request_container" <?php echo ($page[0] == 'request') ? 'style="display: block !important;"' : 'style="display: none !important;"';?>><?php echo $table_loader; ?></div>
		</div>
	</div>
</div>
<div class="offcanvas offcanvas-end" tabindex="-1" id="editPlataforms" aria-labelledby="offcanvasEndLabel">
    <div class="offcanvas-header">
        <h2 class="offcanvas-title" id="offcanvasEndLabel">
			<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button> <span class="ml-5">Plataform</span>
		</h2>
        <span class="badge bg-blue-lt" id="plataform_name"></span>
    </div>
    <div class="offcanvas-body">
        <div>
			<div class="mb-3">
				<input type="hidden" class="form-control" id="plataform_id" name="plataform_id" value="">
				<label for="plataform_name_input">Name</label>
				<input type="text" class="form-control" id="plataform_name_input" name="plataform_name_input" value="">
            </div>
			<div class="mb-3">
				<label for="plataform_link">Link</label>
				<input type="text" class="form-control" id="plataform_link" name="plataform_link" value="">
            </div>
			<div class="mb-3">
				<label for="plataform_link">Extension</label>
				<select class="form-select" id="plataform_extension">
					<option value="https://">https://</option>
					<option value="http://">http://</option>
				</select>
            </div>
			<div class="mb-3">
				<label for="plataform_example">Example of Link</label>
				<textarea class="form-control" id="plataform_example" name="plataform_example"></textarea>
            </div>
        </div>
        <div class="row g-2">
			<div class="col-auto">
				<button class="btn btn-tabler plataform_save" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
					Save
				</button>
			</div>
			<div class="col-auto">
				<button class="btn btn-ghost-danger plataform_delete" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
					Delete
				</button>
			</div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
	window.activeTab = '<?php echo ($page[0] == 'request') ? 'request' : 'plataform';?>';
	<?php echo ($page[0] == 'request') ? 'requestCall();' : 'plataformCall();';?>
});

    var myAccountLink = document.getElementById('platform_btn');
    var connectedAppsLink = document.getElementById('request_btn');

    myAccountLink.addEventListener('click', function(event) {
        event.preventDefault();
        $('#plataform_btn_container').addClass('disabled');
        $('#request_btn_container').removeClass('disabled');
		$('#load_index_result').removeAttr('style');
		$('#request_container').attr('style', 'display: none !important');
		update('/plataform');
		window.activeTab = 'plataform';
		plataformCall();
    });

    connectedAppsLink.addEventListener('click', function(event) {
        event.preventDefault();
        $('#request_btn_container').addClass('disabled');
        $('#plataform_btn_container').removeClass('disabled');
		$('#request_container').removeAttr('style');
		$('#load_index_result').attr('style', 'display: none !important');
		update('/plataform/request');
		window.activeTab = 'plataform';
		requestCall();
    });
</script>
<?php
}

require_once 'footer.php';


?>