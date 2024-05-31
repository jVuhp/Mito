<?php
session_start();
require_once('config.php');
require_once('function.php');
$pagename = 'profile';
$pageload = langSystem($lenguage_section, 'license', 'tab_name');

require_once('header.php');

$uri = $_GET['q'];
$page = explode('/', $uri);

if ($user['logged'] AND unique_perm('unique.license')) {
	if (!$uri) {
		echo $page_empty;
	} else {
?>
<div class="container-xl">
    <div class="card">
              <div class="row g-0">
                <div class="col-12 col-md-3 border-end">
                  <div class="card-body">
                    <h4 class="subheader">Account settings</h4>
                    <div class="list-group list-group-transparent">
                      <a id="myAccountLink" href="<?php echo URI; ?>/profile/<?php echo $page[0]; ?>" class="list-group-item list-group-item-action d-flex align-items-center <?php echo (!$page[1]) ? 'active"' : '';?>">My Account</a>
                      <a id="connectedAppsLink" href="<?php echo URI; ?>/profile/<?php echo $page[0]; ?>/connected-apps" class="list-group-item list-group-item-action d-flex align-items-center <?php echo ($page[1] == 'connected-apps') ? 'active"' : '';?>">Connected Apps</a>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-9 d-flex flex-column" <?php echo (!$page[1]) ? 'style="display: block !important;"' : 'style="display: none !important;"';?> id="settings">
                  <div class="card-body">
                    <h2 class="mb-4">My Account</h2>
                    <h3 class="card-title">Profile Details</h3>
                    <div class="row align-items-center">
                      <div class="col-auto"><span class="avatar avatar-xl" style="background-image: url(https://cdn.discordapp.com/avatars/<?php echo $page[0] . '/' . userInfo($page[0], 'avatar') . is_animated(userInfo($page[0], 'avatar')); ?>); width: 64px; height: 64px;"></span></div>
                      <div class="col-auto">
						<div><?php echo userInfo($page[0], 'name'); ?></div>
						<div class="mt-1 small text-muted"><?php echo rank('name'); ?></div>
					  </div>
                    </div>
                    <h3 class="card-title mt-4">Account Information</h3>
                    <div class="row g-3">
                      <div class="col-md">
                        <div class="form-label">User ID</div>
                        <input type="text" class="form-control" value="<?php echo userInfo($page[0], 'id'); ?>">
                      </div>
                      <div class="col-md">
                        <div class="form-label">User Secret</div>
                        <input type="text" class="form-control" value="<?php echo userInfo($page[0], 'secret'); ?>">
                      </div>
                      <div class="col-md">
                        <div class="form-label">Country</div>
                        <input type="text" class="form-control" value="Peimei, China">
                      </div>
                    </div>
                    <h3 class="card-title mt-4">Email</h3>
                    <p class="card-subtitle">You can not change your email address - please get in touch if you have any issues.</p>
                    <div>
						<div class="row g-2">
							<div class="col-auto">
								<input type="text" class="form-control w-auto" value="<?php echo userInfo($page[0], 'email'); ?>">
							</div>
						</div>
					</div>
                    <h3 class="card-title mt-4">Password</h3>
                    <p class="card-subtitle">You can set a permanent password if you don't want to use temporary login codes.</p>
                    <div id="set_new_password_container">
                      <a href="#" class="btn" id="set_new_password_btn">
                        Set new password
                      </a>
                    </div>
                    <div id="set_new_password" style="display: none;" class="mb-3">
						<div class="row justify-content-between align-items-center mb-5">
						  <div class="col-12 col-md-9 col-xl-7">
							<h2 class="mb-2">Change your password</h2>
							<p class="text-body-secondary mb-xl-0">We will email you a confirmation when changing your password, so please expect that email after submitting.</p>
						  </div>
						  <div class="col-12 col-xl-auto">
							<button class="btn btn-white">Forgot your password?</button>
						  </div>
						</div>
						<div class="row">
						  <div class="col-12 col-md-6 order-md-2">

							<div class="card border ms-md-4">
							  <div class="card-body">
								<p class="mb-2">
								  Password requirements
								</p>
								<p class="small text-body-secondary mb-2">
								  To create a new password, you have to meet all of the following requirements:
								</p>
								<ul class="small text-body-secondary ps-4 mb-0">
								  <li>
									Minimum 8 character
								  </li>
								  <li>
									At least one special character
								  </li>
								  <li>
									At least one number
								  </li>
								  <li>
									Can’t be the same as a previous password
								  </li>
								</ul>
							  </div>
							</div>

						  </div>
						  <div class="col-12 col-md-6">
							<form>
							  <div class="form-group mb-2">
								<label class="form-label">
								  Current password
								</label>
								<input type="password" class="form-control">

							  </div>
							  <div class="form-group mb-2">
								<label class="form-label">
								  New password
								</label>
								<input type="password" class="form-control">
							  </div>
							  <div class="form-group mb-2">
								<label class="form-label">
								  Confirm new password
								</label>
								<input type="password" class="form-control">
							  </div>
							  <button class="btn w-80 btn-primary lift" type="submit">
								Update password
							  </button>
							  <button class="btn w-20 btn-ghost-danger lift" type="button" id="cancel_new_password_btn">
								Cancel
							  </button>
							</form>
						  </div>
						</div>
                    </div>
					
                    <h3 class="card-title mt-4">Force password usage</h3>
                    <p class="card-subtitle">Force to enter the account password when logging into my account.</p>
                    <div id="set_new_password_container"><button class="btn btn-primary">Enable</button></div>
					
                    <h3 class="card-title mt-4">Your style for the page</h3>
                    <p class="card-subtitle">Use your favorite theme for the page!</p>
                    <div id="set_new_password_container">
						<div class="row g-2">
							<div class="col-auto">
							  <label class="form-colorinput" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom" onclick="event.preventDefault(); location.href='?theme=dark';">
								<input name="color" type="radio" value="dark" class="form-colorinput-input" <?php echo ($themeToggler == 'dark') ? 'checked=""' : ''; ?>>
								<span class="form-colorinput-color bg-dark"></span>
							  </label>
							</div>
							<div class="col-auto">
							  <label class="form-colorinput form-colorinput-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom" onclick="event.preventDefault(); location.href='?theme=light';">
								<input name="color" type="radio" value="white" class="form-colorinput-input" <?php echo ($themeToggler == 'light') ? 'checked=""' : ''; ?>>
								<span class="form-colorinput-color bg-white"></span>
							  </label>
							</div>
						</div>
					</div>
					
                  </div>
                </div>
				
                <div class="col-12 col-md-9 d-flex flex-column" <?php echo ($page[1] == 'connected-apps') ? 'style="display: block !important;"' : 'style="display: none !important;"';?> id="connected-apps">
					<div class="card-body">
						<h2 class="mb-4">Apps sync to your Account</h2>
						<div class="list-group list-group-flush my-n3">
						
							<div class="list-group-item">
								<div class="row align-items-center">
								  <div class="col">
									<h4 class="mb-1">
										Discord app
									</h4>
									<small class="text-body-secondary">
										Synced account from a Discord account.
									</small>
								  </div>
								  <div class="col-auto"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M9 12l2 2l4 -4" /></svg></div>
								</div>
							</div>
						
							<?php echo generatePlataformSync($_SESSION['dbb_user']['id']); ?>
							
						</div>
					</div>
<div class="offcanvas offcanvas-end" tabindex="-1" id="open_sync_modal" aria-labelledby="offcanvasEndLabel">
    <div class="offcanvas-header">
        <h2 class="offcanvas-title" id="offcanvasEndLabel">
			<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button> <span class="ml-5">Sync</span>
		</h2>
        <span class="badge bg-blue-lt" id="plataform_name"></span>
    </div>
    <div class="offcanvas-body">
        <div>
			<div class="mb-3">
				<input type="hidden" class="form-control" id="plataform_id" name="plataform_id" value="">
				<input type="text" class="form-control" id="id_user_sync" name="id_user_sync" placeholder="vuhp.385091">
				<p class="text-muted" id="plataform_example">Put your account identification (username or ID) of your account in the box above.</p>
            </div>
        </div>
        <div class="row g-2">
			<div class="col-auto">
				<button class="btn btn-tabler license_overview" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock-play" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 7v5l2 2" /><path d="M17 22l5 -3l-5 -3z" /><path d="M13.017 20.943a9 9 0 1 1 7.831 -7.292" /></svg>
					Send to Review
				</button>
			</div>
        </div>
    </div>
</div>
              </div>
		</div>
	</div>
</div>
<script>
    var btnNewPassword = document.getElementById('set_new_password_btn');
    var btnCancelPassword = document.getElementById('cancel_new_password_btn');
	
    var myAccountLink = document.getElementById('myAccountLink');
    var connectedAppsLink = document.getElementById('connectedAppsLink');
    var myAccountContent = document.getElementById('settings');
    var connectedAppsContent = document.getElementById('connected-apps');

    btnNewPassword.addEventListener('click', function(event) {
		event.preventDefault();
		$('#set_new_password').removeAttr('style');
		$('#set_new_password_container').attr('style', 'display: none !important');
    });

    btnCancelPassword.addEventListener('click', function(event) {
		event.preventDefault();
		$('#set_new_password_container').removeAttr('style');
		$('#set_new_password').attr('style', 'display: none !important');
    });

    myAccountLink.addEventListener('click', function(event) {
        event.preventDefault();
        myAccountLink.classList.add('active');
        connectedAppsLink.classList.remove('active');
		$('#settings').removeAttr('style');
		$('#connected-apps').attr('style', 'display: none !important');
		update('/profile/<?php echo $page[0]; ?>');
    });

    connectedAppsLink.addEventListener('click', function(event) {
        event.preventDefault();
        myAccountLink.classList.remove('active'); 
        connectedAppsLink.classList.add('active'); 
		$('#connected-apps').removeAttr('style');
		$('#settings').attr('style', 'display: none !important');
		update('/profile/<?php echo $page[0]; ?>/connected-apps');
    });
</script>
<?php
	}
}

require_once('footer.php');

?>