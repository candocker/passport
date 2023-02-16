DELETE FROM `wp_auth_role_permission` WHERE `role_code` = 'super';
REPLACE INTO `wp_auth_role_permission`(`role_code`, `permission_code`, `created_at`) SELECT 'super', `code`, `created_at` FROM `wp_auth_permission` WHERE 1 ;
INSERT INTO `wp_auth_role_permission`(`role_code`, `permission_code`) SELECT 'editor', `code` FROM `wp_auth_permission` WHERE `app` IN ('merchant') ;


-------------------旧数据-------------
INSERT INTO `wp_auth_permission` (`code`, `resource_code`, `parent_code`, `name`, `app`, `controller`, `action`, `orderlist`, `display`, `extparam`) SELECT `code`, `elem_code`, `parent_code`, `name`, `module`, `controller`, `method`, `orderlist`, `display`, `extparam` FROM `bak_passport`.`wp_auth_permission0808` WHERE `module` = 'merchant' ;
UPDATE `wp_auth_permission` SET `method` = 'get' WHERE `app` IN ('culture') AND `method` = '' AND `action` IN ('listinfo', 'view');
UPDATE `wp_auth_permission` SET `method` = 'post' WHERE `app` IN ('culture') AND `method` = '' AND `action` IN ('add');
UPDATE `wp_auth_permission` SET `method` = 'put' WHERE `app` IN ('culture') AND `method` = '' AND `action` IN ('update');
UPDATE `wp_auth_permission` SET `method` = 'delete' WHERE `app` IN ('culture') AND `method` = '' AND `action` IN ('delete');
UPDATE `wp_auth_permission` SET `method` = 'get' WHERE `app` IN ('culture') AND `method` = '' AND `action` != '';



------ TRUNCATE `wp_auth_role_permission`;
