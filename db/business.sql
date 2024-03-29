CREATE TABLE IF NOT EXISTS `st_business`(
    `ID` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `ID_post` BIGINT(20) UNSIGNED NOT NULL,
    `ID_user` BIGINT(20) UNSIGNED NOT NULL,
    `name` VARCHAR (50) NOT '',
    `description` TEXT(300) DEFAULT '',
    `slogan` VARCHAR (50) DEFAULT '',
    `ID_category` BIGINT(20) UNSIGNED NOT NULL,
    `latitude` DECIMAL DEFAULT NULL,
    `longitude` DECIMAL DEFAULT NULL,
    `content` TEXT DEFAULT NULL,
    FOREIGN KEY (`ID_post`) REFERENCES `wp_posts`(`ID`),
    FOREIGN KEY (`ID_user`) REFERENCES `wp_users`(`ID`),
    FOREIGN KEY (`ID_category`) REFERENCES `st_categories`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `st_links`(
    `ID` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `ID_post` BIGINT(20) UNSIGNED NOT NULL,
    `title` VARCHAR(30) DEFAULT '',
    `link` VARCHAR(30) NOT NULL,
    `type` VARCHAR(30) DEFAULT '',
    FOREIGN KEY (`ID_post`) REFERENCES `wp_posts`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `st_analystics_likes` (
    `ID` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `ID_post` BIGINT(20) UNSIGNED NOT NULL,
    `ID_user` BIGINT(20) UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `time` TIME NOT NULL,
    FOREIGN KEY (`ID_post`) REFERENCES `wp_posts`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (`ID_user`) REFERENCES `wp_users`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `st_analystics_clickLinks` (
    `ID` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `ID_link` BIGINT(20) UNSIGNED NOT NULL,
    `ID_user` BIGINT(20) UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `time` TIME NOT NULL,
    `type` VARCHAR(30) DEFAULT '',
    FOREIGN KEY (`ID_link`) REFERENCES `st_links` (`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (`ID_user`) REFERENCES `wp_users` (`ID`) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `st_analystics_business_views` (
    `ID` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `ID_post` BIGINT(20) UNSIGNED NOT NULL,
    `ID_user` BIGINT(20) UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `time` TIME NOT NULL,
    `duration` INT DEFAULT '0',
    FOREIGN KEY (`ID_post`) REFERENCES `wp_posts`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (`ID_user`) REFERENCES `wp_users`(`ID`) ON UPDATE CASCADE ON DELETE CASCADE
);