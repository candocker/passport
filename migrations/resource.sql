INSERT INTO `wp_auth_resource` (`app`, `code`, `name`, `controller`, `request`, `model`, `service`, `repository`, `resource`, `collection`) VALUES 
('culture', 'book-record', '书籍阅读记录', '1', '1', '1', '', '1', '1', '1'),
('culture', 'calligrapher', '书法家', '1', '1', '1', '', '1', '1', '1'),
('culture', 'chapter-record', '章节阅读记录', '1', '1', '1', '', '1', '1', '1'),
('culture', 'rubbing', '碑帖', '1', '1', '1', '', '1', '1', '1'),
('culture', 'rubbing-page', '碑帖页', '1', '1', '1', '', '1', '1', '1'),

('bigdata', '', '', '1', '1', '1', '', '1', '1', '1'),
('bigdata', '', '', '1', '1', '1', '', '1', '1', '1'),
('bigdata', '', '', '1', '1', '1', '', '1', '1', '1'),

('bigdata', 'source-order', '源订单', '', '', '1', '', '', '', ''),
('bigdata', 'data-sync', '源数据同步', '1', '1', '1', '', '1', '1', '1'),

('passport', 'manager', '管理员', '1', '1', '1', '1', '1', '1', '1', ''),
('passport', 'entrance', '登录/注册', '1', '1', '', '', '', '', '', ''),
('passport', 'user-permission', '用户权限', '', '', '', '1', '', '', '', ''),
('passport', 'easysms', '短信服务', '', '', '', 'Swoolecan\\Baseapp\\Services\\EasysmsService', '', '', '', ''),


INSERT INTO `wp_auth_permission` ( `code`, `resource_code`, `parent_code`, `name`, `app`, `controller`, `action`, `method`, `orderlist`, `display`, `icon`, `extparam`) VALUES

( 'infocms', '', '', 'CMS系统', 'infocms', '', '', '', 800, 1, 'nested', ''),
( 'infocms_goodsbase', '', 'infocms', '商品基本信息', 'infocms', '', '', '', 900, 1, 'nested', ''),
( 'infocms_goods', '', 'infocms', '商品', 'infocms', '', '', '', 900, 1, 'nested', ''),

( 'infocms_category_add', 'category', 'infocms_goodsbase', '添加分类', 'infocms', 'category', 'add', 'post', 0, 4, '', ''),
( 'infocms_category_delete', 'category', 'infocms_goodsbase', '删除', 'infocms', 'category', 'delete', 'delete', 0, 5, '', ''),
( 'infocms_category_update', 'category', 'infocms_goodsbase', '编辑', 'infocms', 'category', 'update', 'post', 0, 5, '', ''),
( 'infocms_category_view', 'goods', 'infocms_goodsbase', '查看', 'infocms', 'category', 'view', 'get', 0, 5, '', ''),
( 'infocms_category_listinfo', 'category', 'infocms_goodsbase', '分类管理', 'infocms', 'category', 'listinfo', 'get', 99, 3, '', ''),
( 'infocms_category_listtree', 'category', 'infocms_goodsbase', '商品分类（树状)', 'infocms', 'category', 'listinfo-tree', 'get', 99, 3, '', ''),
