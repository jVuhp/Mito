<?php
if (VERIFY_AND_UPDATE) {
try {
    $sqlLicense = $connx->prepare("DESCRIBE `u_license`");
    $sqlLicense->execute();

} catch (PDOException $e) {
    $u_license = "CREATE TABLE `u_license` (
  `id` int(11) NOT NULL,
  `udid` varchar(32) NOT NULL,
  `license` varchar(512) NOT NULL,
  `product` text NOT NULL,
  `boundProduct` int(11) NOT NULL DEFAULT '1',
  `expire` bigint(20) NOT NULL,
  `maxIps` int(11) NOT NULL DEFAULT '3',
  `ips` text,
  `time` text,
  `status` varchar(12) NOT NULL DEFAULT '1',
  `use` int(11) NOT NULL DEFAULT '1',
  `resetips` varchar(12) NOT NULL DEFAULT '5',
  `by` text NOT NULL,
  `plataform` varchar(256) DEFAULT NULL,
  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8";

    $connx->exec($u_license);
    $connx->exec("ALTER TABLE `u_license` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

}

try {
    $sqlLicense = $connx->prepare("DESCRIBE `u_product`");
    $sqlLicense->execute();

} catch (PDOException $e) {
    $u_product = "CREATE TABLE `u_product` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `direction` text NOT NULL,
  `priority` varchar(12) NOT NULL,
  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8";

    $connx->exec($u_product);
    $connx->exec("ALTER TABLE `u_product` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

    echo "Table 'u_product' created successfully.";
}

try {
    $sqlLicense = $connx->prepare("DESCRIBE `u_server`");
    $sqlLicense->execute();

} catch (PDOException $e) {
    $u_server = "CREATE TABLE `u_server` (
  `id` int(11) NOT NULL,
  `license` text NOT NULL,
  `ip` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'process',
  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8";

    $connx->exec($u_server);
    $connx->exec("ALTER TABLE `u_server` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

    echo "Table 'u_server' created successfully.";
}


try {
    $sqlLicense = $connx->prepare("DESCRIBE `u_user`");
    $sqlLicense->execute();

} catch (PDOException $e) {
    $u_user = "CREATE TABLE `u_user` (
  `id` int(11) NOT NULL,
  `udid` varchar(32) NOT NULL,
  `name` text,
  `avatar` text,
  `rank` varchar(12) NOT NULL DEFAULT 'user',
  `theme` varchar(8) NOT NULL DEFAULT 'false',
  `ips` text,
  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8";

    $connx->exec($u_user);
    $connx->exec("ALTER TABLE `u_user` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

    echo "Table 'u_user' created successfully.";
}
try {
    $sqlLicense = $connx->prepare("DESCRIBE `u_user_permissions`");
    $sqlLicense->execute();

} catch (PDOException $e) {
    $u_user_permissions = "CREATE TABLE `u_user_permissions` (
  `id` int(11) NOT NULL,
  `udid` text NOT NULL,
  `permission` text NOT NULL,
  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8";

    $connx->exec($u_user_permissions);
    $connx->exec("ALTER TABLE `u_user_permissions` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

    echo "Table 'u_user_permissions' created successfully.";
}

try {
    $sqlLicense = $connx->prepare("DESCRIBE `u_plataform`");
    $sqlLicense->execute();

} catch (PDOException $e) {
    $u_plataform = "CREATE TABLE `u_plataform` (
  `id` int NOT NULL,
  `name` varchar(128) NOT NULL,
  `link` text NOT NULL,
  `extension` varchar(16) NOT NULL DEFAULT 'https://',
  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8";

    $connx->exec($u_plataform);
    $connx->exec("ALTER TABLE `u_plataform` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    $connx->exec("INSERT INTO `u_plataform`(`id`, `name`, `link`, `extension`, `since`) VALUES (NULL, 'Discord', 'discord.com', 'https://', CURRENT_TIMESTAMP);");

    echo "Table 'u_plataform' created successfully.";
}

try {
    $sqlLicense = $connx->prepare("DESCRIBE `u_groups`");
    $sqlLicense->execute();

} catch (PDOException $e) {
    $u_groups = "CREATE TABLE `u_groups` (
  `id` int NOT NULL,
  `name` varchar(64) NOT NULL,
  `color` varchar(32) NOT NULL DEFAULT 'success',
  `default` int NOT NULL DEFAULT '0',
  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8";

    $connx->exec($u_groups);
    $connx->exec("ALTER TABLE `u_groups` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    $connx->exec("INSERT INTO `u_groups`(`id`, `name`, `color`, `default`, `since`) VALUES (NULL, 'Member', 'common', '1', CURRENT_TIMESTAMP);");
    $connx->exec("INSERT INTO `u_groups`(`id`, `name`, `color`, `default`, `since`) VALUES (NULL, 'Owner', 'common', '0', CURRENT_TIMESTAMP);");

    echo "Table 'u_groups' created successfully.";
}
try {
    $sqlLicense = $connx->prepare("DESCRIBE `u_groups_permissions`");
    $sqlLicense->execute();

} catch (PDOException $e) {
    $u_groups_permissions = "CREATE TABLE `u_groups_permissions` (
  `id` int NOT NULL,
  `group` int NOT NULL,
  `permission` varchar(256) NOT NULL,
  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8";

    $connx->exec($u_groups_permissions);
    $connx->exec("ALTER TABLE `u_groups_permissions` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    $connx->exec("INSERT INTO `u_groups_permissions`(`id`, `group`, `permission`, `since`) VALUES (NULL,'2','unique.*', CURRENT_TIMESTAMP);");

    echo "Table 'u_groups_permissions' created successfully.";
}
try {
    $sqlLicense = $connx->prepare("DESCRIBE `u_groups_user`");
    $sqlLicense->execute();

} catch (PDOException $e) {
    $u_groups_user = "CREATE TABLE `u_groups_user` (
  `id` int NOT NULL,
  `group` int NOT NULL,
  `user` int NOT NULL,
  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8";

    $connx->exec($u_groups_user);
    $connx->exec("ALTER TABLE `u_groups_user` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

    echo "Table 'u_groups_user' created successfully.";
}
}
?>