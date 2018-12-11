/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : localhost:3306
 Source Schema         : ishop

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 11/12/2018 23:48:40
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for is_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `is_auth_rule`;
CREATE TABLE `is_auth_rule`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1:管理端2：商户端3：合作商端',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态',
  `tag` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pid` smallint(5) UNSIGNED NOT NULL COMMENT '父级ID',
  `icon` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '图标',
  `sort` int(10) UNSIGNED NOT NULL COMMENT '排序',
  `is_auth` tinyint(4) NULL DEFAULT 1,
  `condition` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 623 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '规则表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of is_auth_rule
-- ----------------------------
INSERT INTO `is_auth_rule` VALUES (1, 'admin/System/default', '系统配置', 1, 1, '系统', 0, 'fa fa-gears', 96, 2, '');
INSERT INTO `is_auth_rule` VALUES (2, 'admin/System/siteConfig', '站点配置', 1, 1, '', 1, '', 9, 2, '');
INSERT INTO `is_auth_rule` VALUES (3, 'admin/System/updateSiteConfig', '修改', 1, 1, NULL, 2, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (5, 'admin/Menu/default', '菜单管理', 1, 1, '系统', 0, 'fa fa-bars', 97, 2, '');
INSERT INTO `is_auth_rule` VALUES (6, 'admin/Menu/index', '平台菜单', 1, 1, '', 5, '', 10, 2, '');
INSERT INTO `is_auth_rule` VALUES (7, 'admin/Menu/add', '添加菜单', 1, 1, NULL, 6, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (8, 'admin/Menu/save', '保存菜单', 1, 1, NULL, 6, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (9, 'admin/Menu/edit', '编辑菜单', 1, 1, NULL, 6, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (10, 'admin/Menu/update', '更新菜单', 1, 1, NULL, 6, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (11, 'admin/Menu/delete', '删除菜单', 1, 1, NULL, 6, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (16, 'admin/User/default', '管理员管理', 1, 1, '用户', 0, 'fa fa-user', 98, 1, '');
INSERT INTO `is_auth_rule` VALUES (18, 'admin/AdminUser/index', '管理员', 1, 1, NULL, 16, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (19, 'admin/AuthGroup/index', '平台角色', 1, 1, '', 5, '', 9, 2, '');
INSERT INTO `is_auth_rule` VALUES (31, 'admin/AuthGroup/add', '添加权限组', 1, 1, NULL, 19, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (32, 'admin/AuthGroup/save', '保存权限组', 1, 1, NULL, 19, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (33, 'admin/AuthGroup/edit', '编辑权限组', 1, 1, NULL, 19, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (34, 'admin/AuthGroup/update', '更新权限组', 1, 1, NULL, 19, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (35, 'admin/AuthGroup/delete', '删除权限组', 1, 1, NULL, 19, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (36, 'admin/AuthGroup/auth', '授权', 1, 1, NULL, 19, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (37, 'admin/AuthGroup/updateAuthGroupRule', '更新权限组规则', 1, 1, NULL, 19, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (49, 'admin/AdminUser/add', '添加管理员', 1, 1, NULL, 18, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (50, 'admin/AdminUser/save', '保存管理员', 1, 1, NULL, 18, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (51, 'admin/AdminUser/edit', '编辑管理员', 1, 1, NULL, 18, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (52, 'admin/AdminUser/update', '更新管理员', 1, 1, NULL, 18, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (53, 'admin/AdminUser/delete', '删除管理员', 1, 1, NULL, 18, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (77, 'admin/Wechat/default', '微信管理', 1, 1, '微信管理', 0, 'fa fa-wechat', 700, 1, '');
INSERT INTO `is_auth_rule` VALUES (78, 'admin/Tags/index', '粉丝标签', 1, 1, '客户管理', 77, '', 101, 1, '');
INSERT INTO `is_auth_rule` VALUES (79, 'admin/Fans/index', '微信用户', 1, 1, '客户管理', 77, '', 102, 1, '');
INSERT INTO `is_auth_rule` VALUES (81, 'admin/Keys/index', '关键字管理', 1, 1, NULL, 77, '', 95, 1, '');
INSERT INTO `is_auth_rule` VALUES (84, 'admin/WechatMenu/index', '微信菜单', 1, 1, NULL, 77, '', 100, 1, '');
INSERT INTO `is_auth_rule` VALUES (86, 'admin/System/config', '用户配置', 1, 1, '', 1, '', 10, 2, '');
INSERT INTO `is_auth_rule` VALUES (87, 'admin/System/add', '添加', 1, 1, NULL, 86, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (88, 'admin/System/edit', '修改', 1, 1, NULL, 86, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (89, 'admin/System/del', '删除', 1, 1, NULL, 86, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (90, 'admin/System/setConfig', '配置', 1, 1, NULL, 86, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (100, 'admin/Tags/add', '添加标签', 1, 1, NULL, 78, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (101, 'admin/Tags/edit', '编辑标签', 1, 1, NULL, 78, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (102, 'admin/Tags/del', '删除标签', 1, 1, NULL, 78, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (103, 'admin/Tags/sync', '同步标签', 1, 1, NULL, 78, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (104, 'admin/Fans/sync', '同步粉丝', 1, 1, NULL, 79, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (105, 'admin/Fans/backAdd', '加入黑名单', 1, 1, NULL, 79, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (106, 'admin/Fans/backOut', '移出黑名单', 1, 1, NULL, 79, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (107, 'admin/Fans/setTag', '打标签', 1, 1, NULL, 79, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (108, 'admin/WechatMenu/edit', '发布菜单', 1, 1, NULL, 84, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (109, 'admin/WechatMenu/cancel', '取消发布', 1, 1, NULL, 84, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (110, 'admin/material/default', '素材管理', 1, 1, '素材管理', 0, 'fa fa-folder-open', 100, 1, '');
INSERT INTO `is_auth_rule` VALUES (111, 'admin/News/index', '我的图文素材', 1, 1, '', 416, '', 96, 1, '');
INSERT INTO `is_auth_rule` VALUES (112, 'admin/Fans/moveIn', '移入分组', 1, 1, NULL, 79, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (113, 'admin/Fans/moveOut', '移出分组', 1, 1, NULL, 79, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (114, 'admin/News/add', '添加', 1, 1, NULL, 111, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (115, 'admin/News/edit', '修改', 1, 1, NULL, 111, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (116, 'admin/News/del', '删除', 1, 1, NULL, 111, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (117, 'admin/News/push', '预览', 1, 1, '', 111, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (118, 'admin/Template/index', '模板消息', 1, 1, NULL, 77, '', 97, 1, '');
INSERT INTO `is_auth_rule` VALUES (119, 'admin/Keys/formSubcribe', '关注自动回复', 1, 1, NULL, 77, '', 96, 1, '');
INSERT INTO `is_auth_rule` VALUES (120, 'admin/Template/add', '添加', 1, 1, NULL, 118, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (121, 'admin/Template/del', '删除', 1, 1, NULL, 118, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (122, 'admin/Template/testSend', '测试', 1, 1, NULL, 118, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (123, 'admin/Keys/add', '添加', 1, 1, NULL, 81, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (124, 'admin/Keys/edit', '修改', 1, 1, NULL, 81, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (125, 'admin/Keys/del', '删除', 1, 1, NULL, 81, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (126, 'admin/Image/index', '图片库', 1, 1, NULL, 110, '', 99, 1, '');
INSERT INTO `is_auth_rule` VALUES (127, 'admin/Image/addTag', '添加分组', 1, 1, NULL, 126, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (128, 'admin/Image/editTag', '修改分组', 1, 1, NULL, 126, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (129, 'admin/Image/delTag', '删除分组', 1, 1, NULL, 126, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (130, 'admin/Image/moveTag', '移动分组', 1, 1, NULL, 126, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (131, 'admin/Image/editName', '修改图片名称', 1, 1, NULL, 126, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (132, 'admin/Image/del', '删除图片', 1, 1, NULL, 126, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (133, 'admin/Voice/index', '音频库', 1, -1, NULL, 110, '', 98, 2, '');
INSERT INTO `is_auth_rule` VALUES (134, 'admin/Vedio/index', '视频库', 1, 1, NULL, 110, '', 97, 1, '');
INSERT INTO `is_auth_rule` VALUES (135, 'admin/Voice/editName', '修改名称', 1, -1, NULL, 133, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (136, 'admin/Voice/del', '删除', 1, -1, NULL, 133, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (139, 'admin/Index/index', '主页', 1, -1, '主页', 0, 'fa fa-home', 101, 1, '');
INSERT INTO `is_auth_rule` VALUES (140, 'admin/Menu/authshow', '控制', 1, 1, '', 6, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (141, 'admin/ActionLog/index', '操作日志', 1, -1, '', 1, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (142, 'admin/MerchantMenu/index', '修理厂菜单', 1, 1, '', 5, '', 6, 2, '');
INSERT INTO `is_auth_rule` VALUES (144, 'admin/Merchant/default', '合作管理', 1, 1, '商户', 0, 'fa fa-building', 107, 1, '');
INSERT INTO `is_auth_rule` VALUES (145, 'admin/Merchant/index', '修理厂列表', 1, 1, '', 144, '', 10, 1, '');
INSERT INTO `is_auth_rule` VALUES (146, 'admin/Merchant/add', '新增', 1, 1, '', 145, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (147, 'admin/Merchant/edit', '修改', 1, 1, '', 145, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (148, 'admin/Merchant/del', '删除', 1, 1, '', 145, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (295, 'admin/Keys/formFirstSubcribe', '首次关注自动回复', 1, 1, '', 77, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (296, 'mp/Index/default', '首页', 2, 1, '首页', 0, 'fa  fa-home', 10000, 1, '');
INSERT INTO `is_auth_rule` VALUES (299, 'mp/User/default', '用户管理', 2, 1, '管理员管理', 0, 'fa fa-user', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (300, 'mp/AdminUser/index', '管理员列表', 2, 1, '', 299, '', 2, 1, '');
INSERT INTO `is_auth_rule` VALUES (303, 'admin/MpAuthGroup/index', '修理厂角色', 1, 1, '', 5, '', 5, 2, '');
INSERT INTO `is_auth_rule` VALUES (304, 'mp/AdminUser/add', '添加', 2, 1, '', 300, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (305, 'mp/AdminUser/edit', '修改', 2, 1, '', 300, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (306, 'mp/AdminUser/delete', '删除', 2, 1, '', 300, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (314, 'admin/PhoneMenu/index', '移动端菜单', 1, 1, '', 142, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (315, 'admin/PhoneAuthGroup/index', '移动端角色', 1, 1, '', 303, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (342, 'admin/Vedio/addTag', '添加分组', 1, 1, '', 134, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (343, 'admin/Vedio/editTag', '修改分组', 1, 1, '', 134, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (344, 'admin/Vedio/delTag', '删除分组', 1, 1, '', 134, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (345, 'admin/Vedio/moveTag', '移动分组', 1, 1, '', 134, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (346, 'admin/Vedio/editName', '修改文件名称', 1, 1, '', 134, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (347, 'admin/Vedio/del', '删除文件', 1, 1, '', 134, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (369, 'admin/News/pushAll', '群发', 1, 1, '', 111, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (373, 'mp/Customer/default', '客户管理', 2, 1, '客户管理', 0, 'fa fa-user-circle', 80, 1, '');
INSERT INTO `is_auth_rule` VALUES (374, 'mp/Customer/index', '客户列表', 2, 1, '客户管理', 373, '', 100, 1, '');
INSERT INTO `is_auth_rule` VALUES (375, 'mp/Customer/add', '新增会员', 2, 1, '会员管理', 374, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (376, 'mp/Customer/edit', '更新会员', 2, 1, '会员管理', 374, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (392, 'mp/publics/userInfo', '个人资料', 2, 1, '', 300, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (393, 'mp/publics/updatePassword', '密码修改', 2, 1, '', 300, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (394, 'mp/AdminUserWechat/index', '微信管理员', 2, 1, '', 299, '', 1, 1, '');
INSERT INTO `is_auth_rule` VALUES (415, 'admin/News/pushRecord', '发送记录', 1, 1, '', 416, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (416, 'admin/news/default', '图文消息', 1, 1, '微信管理', 0, 'fa fa-comments', 102, 1, '');
INSERT INTO `is_auth_rule` VALUES (438, 'admin/WechatMenu/_choseKeys', '选择关键字', 1, 1, '', 84, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (440, 'mp/AdminUserWechat/delete', '删除', 2, 1, '', 394, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (442, 'mp/Index/index', '首页', 2, 1, '', 296, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (454, 'admin/customer/default', '会员管理', 1, 1, '会员管理', 0, 'fa fa-user-circle', 688, 1, '');
INSERT INTO `is_auth_rule` VALUES (455, 'admin/customer/index', '会员列表', 1, 1, '会员管理', 454, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (456, 'admin/repair/default', '工单管理', 1, 1, '工单管理', 0, 'fa fa-file-text', 667, 1, '');
INSERT INTO `is_auth_rule` VALUES (457, 'admin/repair/index', '工单列表', 1, -1, '', 456, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (458, 'mp/Repair/default', '维修工单管理', 2, 1, '维修服务', 0, 'fa fa-file-text', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (459, 'mp/Repair/index', '工单接待', 2, 1, '工单管理', 458, '', 3, 1, '');
INSERT INTO `is_auth_rule` VALUES (460, 'mp/RepairProject/default', '项目管理', 2, 0, '维修服务', 0, 'fa fa-hand-stop-o', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (461, 'mp/Repair/service', '工单维修', 2, 1, '工单管理', 458, '', 2, 1, '');
INSERT INTO `is_auth_rule` VALUES (462, 'mp/RepairProject/add', '添加项目', 2, 1, '', 461, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (463, 'mp/RepairProject/edit', '编辑项目', 2, 1, '', 461, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (464, 'mp/Parts/default', '配件管理', 2, 1, '配件管理', 0, 'fa fa-wrench', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (465, 'mp/Parts/index', '配件列表', 2, 1, '配件管理', 464, 'fa fa-gears', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (466, 'mp/Parts/add', '添加配件', 2, 1, '', 465, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (467, 'mp/Parts/edit', '编辑配件', 2, 1, '', 465, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (468, 'mp/Parts/changeStatus', '删除配件', 2, 1, '', 465, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (469, 'mp/RepairProject/changeStatus', '删除配件', 2, 1, '', 461, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (470, 'mp/Insurance/default', '保单管理', 2, 1, '客户管理', 0, 'fa fa-umbrella', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (471, 'mp/Insurance/index', '保单列表', 2, 1, '保单管理', 470, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (472, 'mp/Insurance/add', '添加保单', 2, 1, '', 471, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (473, 'mp/Insurance/edit', '编辑保单', 2, 1, '', 471, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (474, 'mp/Insurance/changeStatus', '删除保单', 2, 1, '', 471, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (475, 'mp/repair/detail', '工单详情', 2, 1, '', 459, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (476, 'admin/Insurance/default', '保单管理', 1, 1, '保单管理', 0, 'fa fa-umbrella', 666, 1, '');
INSERT INTO `is_auth_rule` VALUES (477, 'admin/Insurance/index', '保单列表', 1, 1, '保单管理', 476, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (478, 'admin/Insurance/add', '添加保单', 1, 1, '', 477, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (479, 'admin/Insurance/edit', '编辑保单', 1, 1, '', 477, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (480, 'admin/card/default', '卡券管理', 1, 1, '卡券管理', 0, 'fa fa-gift', 665, 1, '');
INSERT INTO `is_auth_rule` VALUES (481, 'admin/card/index', '卡券列表', 1, 1, '卡券管理', 480, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (482, 'admin/card/cardGet', '领取记录', 1, 1, '卡券管理', 480, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (483, 'admin/card/cardUse', '使用记录', 1, 1, '卡券管理', 480, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (484, 'admin/PushCustomer/default', '推送管理', 1, -1, '推送管理', 0, 'fa fa-credit-card', 663, 1, '');
INSERT INTO `is_auth_rule` VALUES (485, 'admin/PushCustomer/index', '客户记录', 1, 1, '推送管理', 484, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (486, 'admin/PushCustomer/add', '添加客户', 1, 1, '', 485, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (487, 'admin/PushCustomer/edit', '编辑客户', 1, 1, '', 485, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (488, 'admin/PushCustomer/getMerchant', '获取推送门店', 1, 1, '', 485, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (491, 'admin/Carbrands/default', '基础数据', 1, 1, '基础数据', 0, 'fa fa-cog', 108, 1, '');
INSERT INTO `is_auth_rule` VALUES (492, 'admin/Safe/index', '保险公司', 1, 1, '基础数据', 491, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (493, 'admin/Carbrands/index', '车辆品牌', 1, 1, '车辆品牌', 491, '', 10, 1, '');
INSERT INTO `is_auth_rule` VALUES (494, 'admin/Carseries/index', '品牌车系', 1, 1, '基础数据', 491, '', 9, 1, '');
INSERT INTO `is_auth_rule` VALUES (495, 'admin/Cartype/index', '品牌车型', 1, 1, '基础数据', 491, '', 8, 1, '');
INSERT INTO `is_auth_rule` VALUES (496, 'admin/safe/add', '新增', 1, 1, '', 492, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (497, 'admin/safe/edit', '编辑', 1, 1, '', 492, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (498, 'admin/safe/changeStatus', '删除', 1, 1, '', 492, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (499, 'admin/carinfo/index', '车辆信息', 1, -1, '', 454, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (500, 'admin/insurance/getCarbrand', '获取车辆品牌', 1, 1, '', 477, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (501, 'admin/insurance/getCarseries', '获取车系', 1, 1, '', 477, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (502, 'admin/insurance/getCartype', '获取车型', 1, 1, '', 477, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (503, 'admin/RecommendService/index', '推修单管理', 1, 1, '工单管理', 456, '', 1, 1, '');
INSERT INTO `is_auth_rule` VALUES (504, 'admin/product/index', '产品活动', 1, 1, '产品活动', 476, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (505, 'admin/product/add', '添加产品', 1, 1, '', 504, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (506, 'admin/product/edit', '编辑产品', 1, 1, '', 504, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (507, 'admin/product/changeStatus', '删除产品', 1, 1, '', 504, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (508, 'admin/AdminUserWechat/index', '微信管理员', 1, 1, '', 16, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (509, 'admin/AdminUserWechat/delete', '删除', 1, 1, '', 508, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (510, 'admin/InsuranceType/index', '险种列表', 1, 1, '险种管理', 476, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (511, 'admin/InsuranceType/add', '添加险种', 1, 1, '', 510, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (512, 'admin/InsuranceType/edit', '编辑险种', 1, 1, '', 510, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (513, 'admin/InsuranceType/changeStatus', '删除险种', 1, 1, '', 510, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (514, 'admin/partner/index', '合作方列表', 1, 1, '', 144, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (515, 'admin/partner/add', '添加', 1, 1, '', 514, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (516, 'admin/partner/edit', '编辑', 1, 1, '', 514, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (517, 'admin/partner/changeStatus', '编辑状态', 1, 1, '', 514, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (518, 'admin/PartnerMenu/index', '合作商菜单', 1, 1, '', 5, '', 8, 2, '');
INSERT INTO `is_auth_rule` VALUES (519, 'admin/PartnerAuthGroup/index', '合作商角色', 1, 1, '', 5, '', 7, 2, '');
INSERT INTO `is_auth_rule` VALUES (520, 'partner/Index/default', '首页', 3, 0, '首页', 0, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (521, 'partner/admin_user/default', '管理员管理', 3, 1, '管理员管理', 0, 'fa fa-user', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (522, 'partner/Index/index', '首页', 3, 1, '', 520, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (523, 'partner/AdminUser/index', '管理员列表', 3, 1, '', 521, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (524, 'partner/AdminUser/add', '添加', 3, 1, '', 523, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (525, 'partner/AdminUser/edit', '编辑', 3, 1, '', 523, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (526, 'partner/AdminUser/delete', '删除', 3, 1, '', 523, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (527, 'partner/publics/userInfo', '个人资料', 3, 1, '', 523, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (528, 'partner/publics/updatePassword', '密码修改', 3, 1, '', 523, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (529, 'partner/AdminUserWechat/index', '微信管理员', 3, 1, '', 521, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (530, 'partner/AdminUserWechat/delete', '删除', 3, 1, '', 529, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (531, 'partner/insurance/default', '保单管理', 3, 1, '保单管理', 0, 'fa fa-umbrella', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (532, 'partner/insurance/index', '保单列表', 3, 1, '', 531, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (533, 'partner/insurance/add', '添加保单', 3, 1, '', 532, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (534, 'partner/insurance/edit', '保单编辑', 3, 1, '', 532, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (535, 'admin/Insurance/changeStatus', '删除', 1, 1, '', 477, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (536, 'admin/Insurance/getSafe', '获取保险公司', 1, 1, '', 477, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (537, 'admin/Insurance/getProduct', '获取产品活动', 1, 1, '', 477, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (538, 'admin/Insurance/getInsuranceType', '获取险种', 1, 1, '', 477, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (539, 'admin/card/add', '卡券添加', 1, 1, '', 481, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (540, 'admin/card/edit', '卡券编辑', 1, 1, '', 481, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (541, 'admin/card/cardRule', '卡券限制', 1, 1, '', 481, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (542, 'admin/card/addSku', '编辑库存', 1, 1, '', 481, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (543, 'admin/card/_banCard', '卡券停用', 1, 1, '', 481, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (544, 'admin/card/getProduct', '产品活动', 1, 1, '', 481, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (545, 'admin/card/getPartnerList', '获取合作商', 1, 1, '', 481, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (546, 'admin/card/_getQrcode', '长链接转短链接', 1, 1, '', 481, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (547, 'partner/insurance/changeStatus', '删除', 3, 1, '', 532, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (548, 'partner/Insurance/getInsuranceType', '获取险种信息', 3, 1, '', 532, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (549, 'admin/InsuranceIntention/index', '投保管理', 1, 1, '', 476, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (550, 'admin/insurance/import', '保单导入', 1, 1, '', 477, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (551, 'admin/card/offsetCardCode', '卡券核销', 1, 1, '', 482, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (552, 'admin/AdminUser/wechantuser', '选取微信用户', 1, 1, '', 18, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (553, 'partner/Index/info', '查看', 3, 1, '', 520, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (554, 'partner/Insurance/import', '导入', 3, 1, '', 532, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (555, 'mp/carinfo/default', '车辆管理', 2, 1, '客户管理', 0, 'fa fa-automobile', 9, 1, '');
INSERT INTO `is_auth_rule` VALUES (556, 'mp/carinfo/index', '车辆列表', 2, 1, '车辆管理', 555, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (557, 'mp/RecommendService/default', '推修单管理', 2, 1, '维修服务', 0, 'fa fa-clipboard', 30, 1, '');
INSERT INTO `is_auth_rule` VALUES (558, 'mp/RecommendService/index', '推修单管理', 2, 1, '', 557, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (559, 'mp/Supplier/default', '供应商管理', 2, 1, '配件管理', 0, 'fa fa-address-book', 20, 1, '');
INSERT INTO `is_auth_rule` VALUES (560, 'mp/Supplier/index', '供应商列表', 2, 1, '', 559, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (561, 'mp/Supplier/add', '新增', 2, 1, '', 560, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (562, 'mp/Supplier/edit', '编辑', 2, 1, '', 560, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (563, 'mp/RepairType/default', '维修类型管理', 2, 1, '基础数据', 0, 'fa fa-list-ul', 15, 1, '');
INSERT INTO `is_auth_rule` VALUES (564, 'mp/RepairType/index', '维修类型', 2, 1, '', 563, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (565, 'mp/RepairType/add', '新增', 2, 1, '', 564, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (566, 'mp/RepairType/edit', '编辑', 2, 1, '', 564, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (567, 'mp/ComponentType/default', '配件品类管理', 2, 1, '基础数据', 0, 'fa fa-cubes', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (568, 'mp/ComponentType/index', '配件品类', 2, 1, '配件管理', 567, 'fa fa-gavel', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (569, 'mp/ComponentType/add', '配件品类添加', 2, 1, '', 568, '', 0, 2, '');
INSERT INTO `is_auth_rule` VALUES (570, 'mp/ComponentType/edit', '配件品类编辑', 2, 1, '', 568, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (571, 'mp/ComponentType/changeStatus', '配件品类状态变更', 2, 1, '', 568, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (572, 'mp/Repair/add', '新增', 2, 1, '', 459, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (573, 'mp/Repair/edit', '编辑', 2, 1, '', 459, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (574, 'mp/Deploy/default', '角色信息配置', 2, 1, '基础数据', 0, 'fa fa-vcard', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (575, 'mp/Deploy/index', '角色折扣配置', 2, 1, '', 574, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (576, 'mp/Deploy/rebate', '折扣配置', 2, 1, '', 575, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (577, 'partner/Publics/default', '个人中心', 3, 0, '', 0, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (578, 'partner/Publics/userinfo', '个人资料', 3, 0, '', 577, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (579, 'partner/Publics/updatepassword', '密码修改', 3, 0, '', 577, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (580, 'mp/Parts/getCarbrand', '获取品牌', 2, 1, '', 465, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (581, 'mp/Parts/getCarseries', '获取车系', 2, 1, '', 465, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (582, 'partner/AdminUser/wechantuser', '选取微信用户', 3, 0, '', 577, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (583, 'mp/Parts/getCartype', '获取车型', 2, 1, '', 465, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (584, 'mp/Parts/getMarkupRate', '获取配件最低加价率', 2, 1, '', 465, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (585, 'mp/Parts/getMarkupRate', '修改配件最低加价率', 2, 1, '', 465, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (587, 'admin/deploy/setmsg', '消息接收设置', 1, 1, '', 19, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (588, 'partner/Repair/default', '工单管理', 3, 1, '', 0, 'fa fa-file-text', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (589, 'partner/RecommendService/index', '推修单管理', 3, 1, '', 588, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (590, 'partner/RecommendService/add', '新增', 3, 1, '', 589, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (591, 'partner/RecommendService/edit', '编辑', 3, 1, '', 589, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (592, 'mp/RecommendService/contact', '联系记录', 2, 1, '', 558, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (593, 'mp/OutDepot/index', '工单出库', 2, 1, '配件管理', 464, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (594, 'mp/OutDepot/outParts', '直接出库', 2, 1, '', 612, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (595, 'admin/card/exportGet', '卡券领取数据导出', 1, 1, '', 482, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (596, 'admin/card/exportUse', '卡券使用数据导出', 1, 1, '', 483, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (597, 'admin/insurance/export', '保单数据导出', 1, 1, '', 477, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (598, 'mp/insurance/export', '保单数据导出', 3, 1, '', 532, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (599, 'admin/RecommendService/add', '新增', 1, 1, '', 503, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (600, 'admin/RecommendService/edit', '编辑', 1, 1, '', 503, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (601, 'admin/RecommendService/push', '推送', 1, 1, '', 503, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (602, 'admin/RecommendService/export', '导出', 1, 1, '', 503, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (603, 'partner/RecommendService/export', '导出', 3, 1, '', 589, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (604, 'mp/RecommendService/export', '导出', 2, 1, '', 558, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (605, 'admin/Publics/default', '个人中心', 1, -1, '', 0, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (606, 'admin/Publics/userinfo', '个人资料', 1, -1, '', 587, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (607, 'admin/Publics/updatepassword', '密码修改', 1, -1, '', 587, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (608, 'admin/AdminUser/wechantuser', '选取微信用户', 1, -1, '', 587, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (609, 'admin/insurance/exporttp', '导入模板下载', 1, 1, '', 550, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (610, 'mp/insurance/exporttp', '导入模板下载', 3, 1, '', 554, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (611, 'admin/customer/insurance', '保单', 1, 1, '', 455, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (612, 'mp/OutDepot/outList', '出库列表', 2, 1, '', 464, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (613, 'mp/parts/warehousing', '入库列表', 2, 1, '', 464, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (614, 'mp/Repair/detailService', '分配项目', 2, 1, '', 461, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (615, 'mp/parts/import', '导入配件', 2, 1, '', 613, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (616, 'mp/parts/exporttp', '模板下载', 2, 1, '', 613, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (617, 'mp/Repair/complate', '工单结算', 2, 1, '工单管理', 458, '', 1, 1, '');
INSERT INTO `is_auth_rule` VALUES (618, 'mp/OutDepot/repairOutParts', '工单出库', 2, 1, '', 593, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (619, 'mp/OutDepot/doprint', '出库打印', 2, 1, '', 612, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (620, 'mp/parts/inparts', '入库', 2, 1, '', 613, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (621, 'mp/parts/doPrint', '打印入库单据', 2, 1, '', 613, '', 0, 1, '');
INSERT INTO `is_auth_rule` VALUES (622, 'mp/Repair/receptionConfirm', '顾问确认', 2, 1, '', 617, '', 0, 1, '');

SET FOREIGN_KEY_CHECKS = 1;
