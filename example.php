<?php
session_start();
require_once('config.php');
require_once('function.php');
$pageload = 'Examples';

require_once('header.php');

if ($_SESSION['u_user']['logged'] AND unique_perm('unique.examples')) {
?>
<div class="container">
	<div class="bg-image" style="background-image: url('<?php echo BACKGROUND; ?>');min-height: 20vh;border-radius: 0px 0px 10px 10px;">
	  <div class="mask">
		<div class="d-flex justify-content-center align-items-center" style="bottom: 4vh;">
		  <img src="<?php echo IMAGE_LOGO; ?>" width="128">
		  <h5 class="text-white mb-3" style="text-transform: uppercase; font-size: 40px; " align="center">
		  <b style="letter-spacing: .2rem;"><?php echo SITE_NAME; ?></b>
		  <p class="text-white" style="text-transform: uppercase; font-size: 20px; margin-top: 1px; font-family: courier;">Examples for the API</p>
		  </h5>
		</div>
	  </div>
	</div>
    <div class="col-12" style="margin-top: 15px;">
		<hr>
		<h3>Find the information to fill out when making an external request.</h3><br>
		<h5><b class="text-primary">Site Link:</b> <?php echo $redirect_uri; ?></h5>
		<h5><b class="text-primary">CKAP Key:</b> Open (config.php) and search the CKAP_KEY in define.</h5><br><br>
		<h5><b class="text-primary">Example for use:</b> <?php echo $redirect_uri; ?>/request.php?v1=API/VIEW&v2=MY_CKAP_KEY&v3=ONE_LICENSE_KEY</h5><br>
		<hr>
		<h3>Create License with API:</h3>
		<p>
		To generate a license with a request from another place, you need to have the system security key, which is CKAP_KEY and you can find it in the system configuration. Do not leave any field blank or unspecified.
		<br>
		v6 | Expire: one bigint example (<?php echo strtotime('+1 Seconds'); ?> call function in php with "strtotime('+1 Seconds')") or (-1) for never expire<br>
		v7 | Max Ips: Accept INT (numbers).<br>
		v8 | STATUS require: 1 for active or 2 for inactive.<br>
		v9 | Bound require: 1 for require product name or 2 for not require product especified on v5.<br>
		v10 | Plataform require: It is advisable to see the platform table and see which ones exist or one that does not exist could cause a problem.<br>
		v11 | Creator require: You can enter the name of your site or platform that makes the requests or enter your name or some specification to realize where it is generated.<br>
		</p>
		<textarea type="text" class="form-control">request.php?v1=API/CREATE&v2=CKAP_KEY&v3=LICENSE_KEY&v4=CLIENT_ID_DISCORD&v5=PRODUCT_NAME&v6=EXPIRE_TIME&v7=MAX_IPS&v8=STATUS&v9=BOUND_PRODUCT&v10=PLATAFORM_NAME&v11=OWNER_NAME</textarea>
		<hr>
		<h3>Delete License with API:</h3>
		<p>
		Do not leave any field blank or unspecified. Delete a license only by placing the key in 'v3' for the system to verify and perform the action. Everything is returned in a JSON, therefore you can send information when obtaining the json.
		</p>
		<textarea type="text" class="form-control">request.php?v1=API/DELETE&v2=CKAP_KEY&v3=LICENSE_KEY</textarea>
		<hr>
		<h3>View table list with API:</h3>
		<p>
		By making this request you will get the entire table with all the records in a JSON. Do not leave any field blank or unspecified.
		<br>
		v3 options (license, user, perms, product, plataform, group, server)
		</p>
		<textarea type="text" class="form-control">request.php?v1=API/TABLE&v2=CKAP_KEY&v3=TABLE_NAME</textarea>
		<hr>
		<h3>View 1 result on license table with API:</h3>
		<p>
		By making this request you will get the entire table with one the records in a JSON. Do not leave any field blank or unspecified.
		<br>
		v3 options (LICENSE_KEY)
		</p>
		<textarea type="text" class="form-control">request.php?v1=API/VIEW&v2=CKAP_KEY&v3=LICENSE_KEY</textarea>
		<hr>
		<h3>Use request API for verify license:</h3>
		<p>
		You can make the verification request to the system and the system will send a response if it is correct or not. We leave you an example JAVA code to use in a Minecraft plugin. Do not leave any field blank or unspecified.
		</p>
		<textarea type="text" class="form-control">request.php?v1=VERIFY&v2=CKAP_KEY&v3=LICENSE_KEY&v4=PRODUCT_NAME</textarea>
		<hr>
		<h3>Java code Example:</h3>
<p> Call on main: if(!new Util("PUT_LICENSE_KEY", "https://example.com/request", this).register()) return; </p>
<textarea class="form-control" rows="10">
import me.vuhp.vanity.Crate;
import org.bukkit.Bukkit;
import org.bukkit.ChatColor;
import org.bukkit.plugin.Plugin;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.UUID;

public class Util {

    private String licenseKey;
    private Plugin plugin;
    private String validationServer;
    private String securityKey = "YOUR_CKAP_KEY";
    private Util.LogType logType = Util.LogType.NORMAL;
    public Util(String licenseKey, String validationServer, Plugin plugin) {
        this.licenseKey = licenseKey;
        this.plugin = plugin;
        this.validationServer = validationServer;
    }
    public boolean register() {
        log(1,"OTHERS MESSAGES");
        ValidationType vt = isValid();
        if(vt == ValidationType.VALID){
            log(1,"CORRECT VERIFICATION");
        } else {
            log(1,"ERROR TO LICENSE VERIFY");

        }
        return false;
    }
    private String requestServer(String v1, String v2) throws IOException {
        URL url = new URL(validationServer + "?v1=VERIFYv2=" + v1 + "&v3=" + v2 + "&v4=" + plugin.getName());
        HttpURLConnection con = (HttpURLConnection) url.openConnection();
        con.setRequestMethod("GET");
        con.setRequestProperty("User-Agent", "Mozilla/5.0");

        try (BufferedReader in = new BufferedReader(new InputStreamReader(con.getInputStream()))) {
            String inputLine;
            StringBuilder response = new StringBuilder();

            while ((inputLine = in.readLine()) != null) {
                response.append(inputLine);
            }

            return response.toString();
        }
    }
    public ValidationType isValid() {
        String sKey = securityKey;
        String key = licenseKey;

        try {
            String response = requestServer(sKey, key);

            if (response.startsWith("<")) {
                log(1, "The License-Server returned an invalid response!");
                log(1, "In most cases this is caused by:");
                log(1, "1) Your Web-Host injects JS into the page (often caused by free hosts)");
                log(1, "2) Your ValidationServer-URL is wrong");
                log(1,
                        "SERVER-RESPONSE: " + (response.length() < 150 ? response : response.substring(0, 150) + "..."));
                return ValidationType.PAGE_ERROR;
            }

            try {
                return ValidationType.valueOf(response);
            } catch (IllegalArgumentException exc) {
                String respRand = xor(xor(response, key), sKey);
                if (rand.substring(0, respRand.length()).equals(respRand))
                    return ValidationType.VALID;
                else
                    return ValidationType.WRONG_RESPONSE;
            }
        } catch (IOException e) {
            return ValidationType.PAGE_ERROR;
        }
    }

    //
    // Cryptographic
    //

    private static String xor(String s1, String s2) {
        StringBuilder result = new StringBuilder();
        for (int i = 0; i < (Math.min(s1.length(), s2.length())); i++)
            result.append(Byte.parseByte("" + s1.charAt(i)) ^ Byte.parseByte(s2.charAt(i) + ""));
        return result.toString();
    }

    //
    // Enums
    //

    public enum LogType {
        NORMAL, LOW, NONE;
    }

    public enum ValidationType {
        WRONG_RESPONSE, PAGE_ERROR, URL_ERROR, KEY_OUTDATED, KEY_NOT_FOUND, NOT_VALID_IP, INVALID_PLUGIN, VALID;
    }

    //
    // Binary methods
    //

    private String toBinary(String s) {
        byte[] bytes = s.getBytes();
        StringBuilder binary = new StringBuilder();
        for (byte b : bytes) {
            int val = b;
            for (int i = 0; i < 8; i++) {
                binary.append((val & 128) == 0 ? 0 : 1);
                val <<= 1;
            }
        }
        return binary.toString();
    }

    //
    // Console-Log
    //

    private void log(int type, String message) {
        if (logType == LogType.NONE || (logType == LogType.LOW && type == 0))
            return;
        //System.out.println(translate(message));
        Bukkit.getConsoleSender().sendMessage(translate(prefix(message)));
    }

    public static String translate(String text) {
        return ChatColor.translateAlternateColorCodes('&', text);
    }
    public static String prefix(String input) {
        return translate(input)
                .replace("<prefix>", Crate.get().getMainMessage().getString("PREFIX"))
                .replace("<arrow_right>", "»")
                .replace("<arrow_left>", "«")
                .replace("<square_left>", "◀")
                .replace("<square_right>", "▶")
                .replace("<alert>", "⚠")
                .replace("<star>", "★")
                .replace("<heart>", "❤")
                .replace("<steack>", "➥")
                .replace("<bar>", "\u2503");
    }

}
</textarea>
<hr>
		<h3>Python code Example:</h3>
<textarea class="form-control" rows="10">
import requests

# URL de la API PHP
api_url = "http://example.com/request.php?v1=VERIFYv2=PUT_CKAP_KEY&v3=LICENSE_KEY&v4=PRODUCT_NAME"

# Parámetros que quieres enviar a la API (si es necesario)
params = {
    'param1': 'valor1',
    'param2': 'valor2'
}

try:
    # Realizar la solicitud GET (o POST, según la API)
    response = requests.get(api_url, params=params)

    # Verificar si la solicitud fue exitosa (código de respuesta 200)
    if response.status_code == 200:
        # Imprimir la respuesta de la API
        print("Respuesta de la API:", response.text)
    else:
        # Imprimir el código de estado en caso de error
        print(f"Error en la solicitud. Código de estado: {response.status_code}")

except requests.RequestException as e:
    # Manejar errores de solicitud
    print("Error en la solicitud:", e)
</textarea>
<hr>
		<h3>PHP code Example:</h3>
<textarea class="form-control" rows="10">

	$url = 'https://example.com/request.php?v1=VERIFY&v2=PUT_CKAP_KEY&v3=PUT_LICENSE_KEY&v4=PUT_PRODUCT_NAME';

	$response = file_get_contents($url);
	if ($response === false) {
		echo 'Error.. Please contact the administrator of Vanity Proyect.';
	} else {
		$data = json_decode($response, true);

		if ($data === null) {
			echo 'Error... Please contact the administrator of Vanity Proyect.';
		} else {
			$exito = $data['exito'];
			$mensaje = $data['mensaje'];
			if (!$exito) {
				echo $mensaje;
				exit();
			}
		}
	}
</textarea>
		<hr>
	</div>
</div>
<?php

require_once 'footer.php';
}

?>