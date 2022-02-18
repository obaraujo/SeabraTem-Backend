CREATE TABLE IF NOT EXISTS `st_ratings` (
  `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ID_user` BIGINT(20) UNSIGNED NOT NULL,
  `ID_post` BIGINT(20) UNSIGNED NOT NULL,
  `ID_rating_parent` BIGINT(20) UNSIGNED,
  `name_author` VARCHAR(50) NOT NULL DEFAULT '',
  `star` BIGINT(1) UNSIGNED NOT NULL,
  `title` VARCHAR(50) NOT NULL DEFAULT '',
  `description` TEXT(300) DEFAULT '',
  `likes` BIGINT(20) UNSIGNED,
  `deslikes` BIGINT(20) UNSIGNED,
  `is_response` BOOLEAN NOT NULL,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`ID_user`) REFERENCES `st_users`(ID) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`ID_post`) REFERENCES `wp_posts`(ID) ON DELETE CASCADE ON UPDATE CASCADE
);