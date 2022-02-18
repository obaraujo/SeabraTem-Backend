CREATE TABLE IF NOT EXISTS `st_products` (
    `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `ID_category` BIGINT(20) UNSIGNED NOT NULL,
    `ID_business` BIGINT(20) UNSIGNED NOT NULL,
    `ID_user` BIGINT(20) UNSIGNED NOT NULL,
    `ID_product_parent` BIGINT(20) UNSIGNED DEFAULT NULL,
    `name` varchar(100) NOT NULL DEFAULT '',
    `description` text(300) NOT NULL DEFAULT '',
    `original_price` DECIMAL(7, 2) NOT NULL,
    `discount_percent` DECIMAL(3, 2) DEFAULT NULL,
    `stock` BIGINT(20) UNSIGNED DEFAULT NULL,
    `is_variation` BOOLEAN,
    `is_active` BOOLEAN,
    PRIMARY KEY(`ID`),
    FOREIGN KEY(`ID_category`) REFERENCES `st_categories`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(`ID_business`) REFERENCES `wp_business`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(`ID_user`) REFERENCES `wp_users`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `st_demands` (
    `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `ID_user_buyer` BIGINT(20) UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `time` TIME NOT NULL,
    `confirmed` BOOLEAN,
    `status` VARCHAR(11) DEFAULT '',
    `final_price` DECIMAL(7, 2),
    `ID_business` BIGINT(20) UNSIGNED NOT NULL,
    PRIMARY KEY(`ID`),
    FOREIGN KEY(`ID_user_buyer`) REFERENCES `wp_users`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(`ID_business`) REFERENCES `wp_posts`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `st_analytics_product_views` (
    `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `ID_post` BIGINT(20) UNSIGNED NOT NULL,
    `ID_user` BIGINT(20) UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `time` TIME NOT NULL,
    `duration` BIGINT(20) UNSIGNED NOT NULL,
    PRIMARY KEY(`ID`),
    FOREIGN KEY(`ID_post`) REFERENCES `st_products`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(`ID_user`) REFERENCES `wp_users`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `st_demands_relationship` (
    `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `ID_product` BIGINT(20) UNSIGNED NOT NULL,
    `ID_demand` BIGINT(20) UNSIGNED NOT NULL,
    `price_unity` DECIMAL(7, 2),
    `final_price` DECIMAL(7, 2),
    `amount` BIGINT(20) UNSIGNED NOT NULL,
    `discount` BIGINT(20) UNSIGNED DEFAULT NULL,
    `product_name` VARCHAR(50) NOT NULL DEFAULT '',
    `variation` VARCHAR(50) DEFAULT NULL DEFAULT '',
    PRIMARY KEY(`ID`),
    FOREIGN KEY(`ID_demand`) REFERENCES `st_demands`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(`ID_product`) REFERENCES `st_products`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
);