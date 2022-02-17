create table if not exists st_products (
    ID BIGINT NOT NULL AUTO_INCREMENT,
    ID_category BIGINT NOT NULL,
    ID_business BIGINT NOT NULL,
    ID_user BIGINT NOT NULL,
    ID_product_parent BIGINT DEFAULT NULL,
    `name` varchar(100) NOT NULL,
    `description` text(300) NOT NULL,
    original_price DECIMAL(7,2) NOT NULL,
    discount_percent DECIMAL(3, 2) DEFAULT NULL,
    stock BIGINT DEFAULT NULL,
    is_variation BOOLEAN, 

    PRIMARY KEY(ID),
    FOREIGN KEY(ID_category) REFERENCES st_categories(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(ID_business) REFERENCES st_business(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(ID_user) REFERENCES st_users(ID) ON UPDATE CASCADE ON DELETE CASCADE
);

create table if not exists st_demands (
    ID BIGINT NOT NULL AUTO_INCREMENT,
    ID_user_buyer BIGINT NOT NULL,
    `date` DATE NOT NULL,
    `time` TIME NOT NULL,
    `confirmed` BOOLEAN,
    `status` VARCHAR(11),
    final_price DECIMAL(7,2),
    ID_business BIGINT NOT NULL,

    PRIMARY KEY(ID),
    FOREIGN KEY(ID_user_buyer) REFERENCES st_users(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(ID_business) REFERENCES st_business(ID) ON UPDATE CASCADE ON DELETE CASCADE
);

create table if not exists st_analytics_product_views (
    ID BIGINT NOT NULL AUTO_INCREMENT,
    ID_post BIGINT NOT NULL,
    ID_user BIGINT NOT NULL,
    `date` DATE NOT NULL,
    `time` TIME NOT NULL,
    duration BIGINT NOT NULL,

    PRIMARY KEY(ID),
    FOREIGN KEY(ID_post) REFERENCES st_products(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(ID_user) REFERENCES st_users(ID) ON UPDATE CASCADE ON DELETE CASCADE
);

create table if not exists st_demands_relationship (
    ID BIGINT NOT NULL AUTO_INCREMENT,
    ID_product BIGINT NOT NULL,
    ID_demand BIGINT NOT NULL,
    price_unity DECIMAL(7,2),
    final_price DECIMAL(7,2),
    `amount` BIGINT NOT NULL,
    discount BIGINT DEFAULT NULL,
    product_name VARCHAR(50) NOT NULL,
    `variation` VARCHAR(50) DEFAULT NULL,

    PRIMARY KEY(ID),
    FOREIGN KEY(ID_demand) REFERENCES st_demands(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(ID_product) REFERENCES st_products(ID) ON UPDATE CASCADE ON DELETE CASCADE
);