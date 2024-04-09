CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NOW(),
  `name` varchar(255) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `price` int NOT NULL,
  `category_id` int,
  `images` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NOW(),
  `status` varchar(255) NOT NULL,
  `account_id` int,
  `shipping_address_id` int,
  `attached_files` varchar(1024),
  PRIMARY KEY (`id`)
);

CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NOW(),
  `order_id` int,
  `product_id` int,
  PRIMARY KEY (`id`)
);

CREATE TABLE `accounts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NOW(),
  `first_name` varchar(255),
  `last_name` varchar(255),
  `email_address` varchar(255) NOT NULL,
  `contact_number` varchar(255),
  `password` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT "CUSTOMER",
  PRIMARY KEY (`id`)
);

CREATE TABLE `shipping_addresses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NOW(),
  `region` varchar(255),
  `province` varchar(255),
  `city` varchar(255),
  `barangay` varchar(255),
  `unit` varchar(255),
  `notes` varchar(255),
  `account_id` int,
  PRIMARY KEY (`id`)
);

CREATE TABLE `product_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NOW(),
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `access_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `account_id` int,
  PRIMARY KEY (`id`)
);

CREATE TABLE `files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NOW(),
  `original_file_name` varchar(255),
  `file_name` varchar(255),
  `account_id` int,
  PRIMARY KEY (`id`)
);

ALTER TABLE `files` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

ALTER TABLE `products` ADD FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`);

ALTER TABLE `orders` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

ALTER TABLE `orders` ADD FOREIGN KEY (`shipping_address_id`) REFERENCES `shipping_addresses` (`id`);

ALTER TABLE `order_items` ADD FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

ALTER TABLE `order_items` ADD FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

ALTER TABLE `shipping_addresses` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

ALTER TABLE `access_tokens` ADD FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);