CREATE TABLE IF NOT EXISTS `st_users` (
  `ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `ID_wp_user` BIGINT(20) NOT NULL,
  `first_name` VARCHAR(255),
  `last_name` VARCHAR(255),
  `nickname` VARCHAR(255),
  `email` VARCHAR(255) NOT NULL,
  `number_phone` VARCHAR(255),
  `description` TEXT(300),
  `create_at` DATETIME NOT NULL,
  `last_access_date` DATE NOT NULL,
  `last_access_time` TIME NOT NULL,
  `have_plan` BOOLEAN,
  `ID_plan` BIGINT(20),
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`ID_wp_user`) REFERENCES wp_users(ID) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `st_analytics_sessions` (
  `ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `ID_user` BIGINT(20) NOT NULL,  
  `date` DATE NOT NULL,
  `time` TIME NOT NULL,
  `device` TEXT NOT NULL,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`ID_user`) REFERENCES `st_users`(ID) ON DELETE CASCADE ON UPDATE CASCADE,
);

CREATE TABLE IF NOT EXISTS `st_plans` (
  `ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `ID_post` BIGINT(20) NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `slug` VARCHAR(50) NOT NULL,
  `capacities` TEXT NOT NULL,
  `description` TEXT NOT NULL,
  `original_price` DECIMAL(7,2) NOT NULL,
  `descount_price` DECIMAL(3, 2) NOT NULL,
  `duration` BIGINT(20) NOT NULL,
  PRIMARY KEY (`ID`);
);

CREATE TABLE IF NOT EXISTS `st_plans_relationship` (
  `ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `ID_user` BIGINT(20) NOT NULL,
  `ID_plan` BIGINT(20) NOT NULL,
  `duration` BIGINT(20) NOT NULL,
  `buy_date` DATE NOT NULL,
  `buy_time` TIME NOT NULL,
  `expiration_date` DATE NOT NULL,
  `capacities` TEXT(300) NOT NULL,
  `is_active` BOOLEAN NOT NULL,
  PRIMARY KEY (`ID`),
  FOREIGN KEY (`ID_user`) REFERENCES `st_users`(ID) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`ID_plan`) REFERENCES `st_plans`(ID) ON DELETE CASCADE ON UPDATE CASCADE
);