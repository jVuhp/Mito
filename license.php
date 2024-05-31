<?php
session_start();
require_once('config.php');
require_once('function.php');
require_once(__DIR__ . '/execute/page.php');
$pagename = 'license';
$pageload = langSystem($lenguage_section, 'license', 'tab_name');

require_once('header.php');

$query = $_GET['q'];
$query_id = $_GET['id'];

if ($user['logged'] AND unique_perm('unique.license')) {
	if (!isset($query)) {
?>

<div class="container-xl">
	<div class="page-header mb-3">
	  <div class="row align-items-center">
		<div class="col">
		  <div class="page-pretitle">
			Overview
		  </div>
		  <h2 class="page-title">
			License
		  </h2>
		</div>
		<div class="col-auto ms-auto">
		  <div class="btn-list">
			<a href="<?php echo URI; ?>/license/new" class="btn btn-primary d-none d-sm-inline-block">
			  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
			  Create new License
			</a>
			<a href="<?php echo URI; ?>/license/new" class="btn btn-primary d-sm-none btn-icon">
			  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
			</a>
		  </div>
		</div>
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
	} else if ($query == 'new') {
?>


<div class="container-xl">
	<div class="page-header mb-3">
	  <div class="row align-items-center">
		<div class="col">
		  <div class="page-pretitle">
			Create new
		  </div>
		  <h2 class="page-title">
			License
		  </h2>
		</div>
		<div class="col-auto ms-auto">
		  <div class="btn-list">
			<a href="<?php echo URI; ?>/license/new/confirm" class="btn btn-azure d-none d-sm-inline-block confirm_creating">
				<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
				Confirm
			</a>
			<a href="<?php echo URI; ?>/license/new/confirm" class="btn btn-azure d-sm-none btn-icon confirm_creating">
				<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
			</a>
			<a href="<?php echo URI; ?>/license" class="btn btn-ghost-secondary d-none d-sm-inline-block">
				<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-back-up" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14l-4 -4l4 -4" /><path d="M5 10h11a4 4 0 1 1 0 8h-1" /></svg>
				Cancel
			</a>
			<a href="<?php echo URI; ?>/license" class="btn btn-ghost-secondary d-sm-none btn-icon">
				<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-back-up" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14l-4 -4l4 -4" /><path d="M5 10h11a4 4 0 1 1 0 8h-1" /></svg>
			</a>
		  </div>
		</div>
	  </div>
	</div>
    <div class="card col-12 mb-3">
		<form id="generating_new_license" method="POST" class="card-body">
                  <div class="mb-3">
                    <label class="form-label required" for="license_key">License Key</label>
                    <div>
						<div class="row g-2">
							<div class="col input-icon mb-0">
								<span class="input-icon-addon">
									<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-key" style="margin-left: 10px;" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1 -4.069 0l-.301 -.301l-6.558 6.558a2 2 0 0 1 -1.239 .578l-.175 .008h-1.172a1 1 0 0 1 -.993 -.883l-.007 -.117v-1.172a2 2 0 0 1 .467 -1.284l.119 -.13l.414 -.414h2v-2h2v-2l2.144 -2.144l-.301 -.301a2.877 2.877 0 0 1 0 -4.069l2.643 -2.643a2.877 2.877 0 0 1 4.069 0z" /><path d="M15 9h.01" /></svg>
								</span>
								<?php
								
								$chars = (!isset($_COOKIE['refill_key_chars'])) ? '0123456789asdfghjklqwertyuiopzxcvbnm0123456789ASDFGHJKLQWERTYUIOPZXCVBNM0123456789asdfghjklqwertyuiopzxcvbnm' : $_COOKIE['refill_key_chars'];
								
								$chars = (empty($_COOKIE['refill_key_chars'])) ? '0123456789asdfghjklqwertyuiopzxcvbnm0123456789ASDFGHJKLQWERTYUIOPZXCVBNM0123456789asdfghjklqwertyuiopzxcvbnm' : $_COOKIE['refill_key_chars'];
								
								$line_1 = customChar(8, $chars);
								$line_2 = customChar(4, $chars);
								$line_3 = customChar(4, $chars);
								$line_4 = customChar(4, $chars);
								$line_5 = customChar(8, $chars);
								
								$separator = '-';
								
								$refill_key = $line_1 . $separator . $line_2 . $separator . $line_3 . $separator . $line_4 . $separator . $line_5;
								?>
								<input type="text" value="<?php echo $refill_key; ?>" class="form-control" placeholder="Key" name="license_key" id="license_key">
							</div>
                            <div class="col-auto">
								<label class="form-selectgroup-item refill_key">
									<span class="form-selectgroup-label">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-rotate-360" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 16h4v4" /><path d="M19.458 11.042c.86 -2.366 .722 -4.58 -.6 -5.9c-2.272 -2.274 -7.185 -1.045 -10.973 2.743c-3.788 3.788 -5.017 8.701 -2.744 10.974c2.227 2.226 6.987 1.093 10.74 -2.515" /></svg>
									</span>
								</label>
							</div>
                            <div class="col-auto">
								<label class="form-selectgroup-item" data-bs-toggle="offcanvas" href="#settings_key" role="button" aria-controls="settings_key">
									<span class="form-selectgroup-label">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
									</span>
								</label>
							</div>
						</div>
						<small class="form-hint pt-0">This key will be used to verify your articles.</small>
                    </div>
                  </div>
				  
                  <div class="mb-3">
                    <label class="form-label required" for="client_id">Client</label>
                    <div>
						<div class="input-icon mb-0">
							<span class="input-icon-addon">
								<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h1.5" /><path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M20.2 20.2l1.8 1.8" /></svg>
							</span>
							<input type="text" value="" class="form-control" placeholder="Client" name="client_id" id="client_id">
						</div>
						<small class="form-hint pt-0">Enter the user ID. If there is a platform to synchronize the user that synchronizes their account with the same ID, they will be able to see the key automatically.</small>
                    </div>
                  </div>
				  
                  <div class="mb-3">
                    <label class="form-label required" for="plataform_id">Plataform</label>
                    <div>
						<div class="input-icon mb-0">
							<span class="input-icon-addon">
								<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circles-relation" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.183 6.117a6 6 0 1 0 4.511 3.986" /><path d="M14.813 17.883a6 6 0 1 0 -4.496 -3.954" /></svg>
							</span>
							<select class="form-control form-select" name="plataform_id" id="plataform_id">
								<option value="-1">User Account</option>
								<option value="0">Discord</option>
								<?php echo generatePlataformOptions('<option value="name">name</option>'); ?>
							</select>
						</div>
						<small class="form-hint pt-0">For the platform to work automatically you will need to place. This will sync the user's account with the customer ID on this key.</small>
                    </div>
                  </div>
				  
                  <div class="mb-3">
                    <label class="form-label required" for="product">Product</label>
                    <div>
						<div class="row g-2">
							<div class="col input-icon">
								<span class="input-icon-addon">
									<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-box-seam" style="margin-left: 10px;" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5v9l-8 4.5l-8 -4.5v-9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M8.2 9.8l7.6 -4.6" /><path d="M12 12v9" /><path d="M12 12l-8 -4.5" /></svg>
								</span>
								<select class="form-control form-select" name="product" id="product">
									<?php echo generateProductOptions('<option value="name">name</option>'); ?>
								</select>
                            </div>
                            <div class="col-auto">
								<label class="form-selectgroup-item">
                                <input type="checkbox" name="bound" value="1" class="form-selectgroup-input">
                                <span class="form-selectgroup-label">Must usage</span>
								</label>
							</div>
						</div>
						<small class="form-hint pt-0"></small>
                    </div>
                  </div>
				  
                  <div class="mb-3">
                    <label class="form-label required" for="expiration">Expiration</label>
                    <div>
						<div class="row g-2">
							<div class="col input-icon">
								<span class="input-icon-addon">
									<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-stats" style="margin-left: 10px;" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4" /><path d="M18 14v4h4" /><path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M15 3v4" /><path d="M7 3v4" /><path d="M3 11h16" /></svg>
								</span>
								<input type="number" value="" class="form-control" min="1" placeholder="30" name="expire" id="expire">
                            </div>
                            <div class="col-auto">
								<select class="form-select" name="expiration" id="expiration">
									<option value="Seconds">Seconds</option>
									<option value="Minutes">Minutes</option>
									<option value="Hours">Hours</option>
									<option value="Days" selected>Days</option>
									<option value="Months">Months</option>
									<option value="Years">Years</option>
									<option value="Never">Never Expire</option>
								</select>
							</div>
						</div>
						<small class="form-hint pt-0">Time during which the key will stop working, and will enter a frozen state until renewed or modified by staff.</small>
                    </div>
                  </div>
				  
                  <div class="mb-3">
                    <label class="form-label required" for="ip_cap">IP Cap max</label>
                    <div>
						<div class="input-icon mb-0">
							<span class="input-icon-addon">
								<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pins" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.828 9.828a4 4 0 1 0 -5.656 0l2.828 2.829l2.828 -2.829z" /><path d="M8 7l0 .01" /><path d="M18.828 17.828a4 4 0 1 0 -5.656 0l2.828 2.829l2.828 -2.829z" /><path d="M16 15l0 .01" /></svg>
							</span>
							<input type="number" value="5" class="form-control" min="1" placeholder="5" name="ip_cap" id="ip_cap">
						</div>
						<small class="form-hint pt-0">Number of IP's in the license, if it is 0 it will be unlimited.</small>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label" for="note">Note</label>
                    <div>
						<div class="input-icon mb-0">
							<span class="input-icon-addon">
								<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-note" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 20l7 -7" /><path d="M13 20v-6a1 1 0 0 1 1 -1h6v-7a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7" /></svg>
							</span>
							<textarea type="text" class="form-control" placeholder="" name="note" id="note"></textarea>
						</div>
						<small class="form-hint pt-0">Save one note for this license. Is optional.</small>
                    </div>
                  </div>
				  
        </form>
	</div>
</div>
<div class="offcanvas offcanvas-end" tabindex="-1" id="settings_key" aria-labelledby="offcanvasEndLabel" aria-modal="true" role="dialog">
    <div class="offcanvas-header">
        <h2 class="offcanvas-title" id="offcanvasEndLabel">
			<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-key" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1 -4.069 0l-.301 -.301l-6.558 6.558a2 2 0 0 1 -1.239 .578l-.175 .008h-1.172a1 1 0 0 1 -.993 -.883l-.007 -.117v-1.172a2 2 0 0 1 .467 -1.284l.119 -.13l.414 -.414h2v-2h2v-2l2.144 -2.144l-.301 -.301a2.877 2.877 0 0 1 0 -4.069l2.643 -2.643a2.877 2.877 0 0 1 4.069 0z" /><path d="M15 9h.01" /></svg>
			Key Custom Chars.
		</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div>
			<textarea id="settings_char_key" rows="4" placeholder="Char for the license key." class="form-control"><?php echo (!isset($_COOKIE['refill_key_chars'])) ? '0123456789asdfghjklqwertyuiopzxcvbnm0123456789ASDFGHJKLQWERTYUIOPZXCVBNM0123456789asdfghjklqwertyuiopzxcvbnm' : $_COOKIE['refill_key_chars']; ?></textarea>
        </div>
        <div class="mt-3">
            <button class="btn btn-ghost-tabler save_refill_key" type="button" data-bs-dismiss="offcanvas">
                Save Setting
            </button>
        </div>
    </div>
</div>
<?php
	} else {
		
    switch (DB_TYPE) {
        case 'MYSQL':
            $options = '';
            
            $syncSQL = $connx->prepare("SELECT * FROM `$dbb_license` WHERE `id` = ?");
            $syncSQL->execute([$query_id]);
            $results = $syncSQL->fetch(PDO::FETCH_ASSOC);
            break;
        default:
            return '';
    }
	
	$userAvatarLc = platformUser($results['client'], $results['plataform'], 'avatar');
	$userAvatarUdId = platformUser($results['client'], $results['plataform'], 'udid');
?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Overview OF</div>
                <h2 class="page-title">License</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
				
			</div>
			<div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
					<a href="<?php echo URI . '/license/' . $query . '.' . $query_id; ?>/delete" class="btn btn-ghost-danger d-none d-sm-inline-block">
						<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3" /><path d="M18 13.3l-6.3 -6.3" /></svg>
						Delete
					</a>
					<a href="<?php echo URI . '/license/' . $query . '.' . $query_id; ?>/delete" class="btn btn-ghost-danger d-sm-none btn-icon">
						<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3" /><path d="M18 13.3l-6.3 -6.3" /></svg>
					</a>
				  
					<a href="<?php echo URI . '/license/' . $query . '.' . $query_id; ?>/edit" class="btn btn-ghost-tabler d-none d-sm-inline-block">
						<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
						Edit
					</a>
					<a href="<?php echo URI . '/license/' . $query . '.' . $query_id; ?>/edit" class="btn btn-ghost-tabler d-sm-none btn-icon">
						<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
					</a>
				</div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
		<div class="card">
              <div class="card-body">
                <div class="datagrid">
                  <div class="datagrid-item">
                    <div class="datagrid-title">Client</div>
                    <div class="datagrid-content">
						<span class="avatar" style="width: 24px; height: 24px; background-image: url(https://cdn.discordapp.com/avatars/<?php echo $userAvatarUdId . '/' . $userAvatarLc . is_animated($userAvatarLc); ?>)"></span>
						<a href="<?php echo URI; ?>" class="text-pink"><?php echo platformUser($results['client'], $results['plataform'], 'name'); ?></a>
					</div>
                  </div>
                  <div class="datagrid-item">
                    <div class="datagrid-title">Test</div>
                    <div class="datagrid-content"><span class="flag flag-country-ar"></span> 232323</div>
                  </div>
                  <div class="datagrid-item">
                    <div class="datagrid-title">Staff</div>
                    <div class="datagrid-content">
						<span class="avatar" style="width: 24px; height: 24px; background-image: url(https://skins.mcstats.com/face/asd)"></span>
						<a href="<?php echo URI; ?>" class="text-pink">asdasd</a>
					</div>
                  </div>
                  <div class="datagrid-item">
                    <div class="datagrid-title">Title</div>
                    <div class="datagrid-content"><span class="status status-green">asdsad</span></div>
                  </div>
                  <div class="datagrid-item">
                    <div class="datagrid-title">Test</div>
                    <div class="datagrid-content"><span class="status status-green">Asd</span></div>
                  </div>
                  <div class="datagrid-item">
                    <div class="datagrid-title">AASD</div>
                    <div class="datagrid-content">
						N/A
						<span class="avatar" style="width: 24px; height: 24px; background-image: url(https://skins.mcstats.com/face/asd)"></span> 
						<a href="#" class="text-pink">est</a>
					</div>
                  </div>
                </div>
				<hr>
                  <div class="datagrid-item">
                    <div class="datagrid-title">asd</div>
                    <div class="datagrid-content">asddasasdsad</div>
                  </div>
              </div>
            </div>
	</div>
</div>
<?php
	}
}

require_once('footer.php');

?>