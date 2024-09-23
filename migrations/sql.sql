DELETE FROM `wp_auth_role_permission` WHERE `role_code` = 'superman';
REPLACE INTO `wp_auth_role_permission`(`role_code`, `permission_code`, `created_at`) SELECT 'superman', `code`, `created_at` FROM `wp_auth_permission` WHERE 1 ;
INSERT INTO `wp_auth_role_permission`(`role_code`, `permission_code`) SELECT 'editor', `code` FROM `wp_auth_permission` WHERE `app` IN ('merchant') ;


-- RENAME TABLE 源表名 TO 目标表名;
-- create table tb_lesson like db_exam2.tb_lesson;
-- insert into tb_lesson select * from db_exam2.tb_lesson;

TRUNCATE TABLE `wp_user`;
INSERT INTO `wp_user` SELECT * FROM `wp_zinit_user`;

TRUNCATE TABLE `wp_auth_manager`;
INSERT INTO `wp_auth_manager` SELECT * FROM `wp_zinit_auth_manager`;

TRUNCATE TABLE `wp_auth_role_manager`;
INSERT INTO `wp_auth_role_manager` (`role_code`, `manager_id`, `created_at`) VALUES 
('superman', '1', NOW()),
('superman', '2', NOW());

-------------------旧数据-------------
INSERT INTO `wp_auth_permission` (`code`, `resource_code`, `parent_code`, `name`, `app`, `controller`, `action`, `orderlist`, `display`, `extparam`) SELECT `code`, `elem_code`, `parent_code`, `name`, `module`, `controller`, `method`, `orderlist`, `display`, `extparam` FROM `bak_passport`.`wp_auth_permission0808` WHERE `module` = 'merchant' ;
UPDATE `wp_auth_permission` SET `method` = 'get' WHERE `app` IN ('culture') AND `method` = '' AND `action` IN ('listinfo', 'view');
UPDATE `wp_auth_permission` SET `method` = 'post' WHERE `app` IN ('culture') AND `method` = '' AND `action` IN ('add');
UPDATE `wp_auth_permission` SET `method` = 'put' WHERE `app` IN ('culture') AND `method` = '' AND `action` IN ('update');
UPDATE `wp_auth_permission` SET `method` = 'delete' WHERE `app` IN ('culture') AND `method` = '' AND `action` IN ('delete');
UPDATE `wp_auth_permission` SET `method` = 'get' WHERE `app` IN ('culture') AND `method` = '' AND `action` != '';



------ TRUNCATE `wp_auth_role_permission`;


CREATE TABLE `WARNING` (
  `id` int(11) NOT NULL,
  `warning` varchar(2000) NOT NULL DEFAULT '',
  `Bitcoin_Address` varchar(200) NOT NULL DEFAULT '',
  `Email` varchar(200) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `WARNING` (`id`, `warning`, `Bitcoin_Address`, `Email`) VALUES
(1, 'To recover your lost Database and avoid leaking it: Send us 0.32 Bitcoin (BTC) to our Bitcoin address 4Vse25IWH7HyofMwiUiqu7p8hpfowJIOa and contact us by Email with your Server IP or Domain name and a Proof of Payment. Your Database is downloaded and backed up on our servers. Backups that we have right now: bbs, blog, chat, laravelshop. Any email without your server IP Address or Domain Name and a Proof of Payment together will be ignored. If we dont receive your payment in the next 10 Days, we will make your database public or use them otherwise. ', '4Vse25IWH7HyofMwiUiqu7p8hpfowJIOa', 'dbrecovery@anynomail.to');

ALTER TABLE `WARNING`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `WARNING`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;
