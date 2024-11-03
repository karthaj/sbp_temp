CREATE TABLE `checkout_otp` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` int(10) unsigned NOT NULL,
  `otp_code` varchar(5) NOT NULL,
  `status` int(1) DEFAULT '0' COMMENT '0 : new | 1 : used | 2 : authenticated | 3 : expired',
  `expires_at` timestamp NULL DEFAULT NULL,
  `attempts` int(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_checkout_otps_cart_id` (`cart_id`),
  CONSTRAINT `fk_checkout_otps_cart_id` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8