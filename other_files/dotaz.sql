CREATE TABLE customer (
  id           INT AUTO_INCREMENT NOT NULL,
  name         VARCHAR(255)       NOT NULL,
  street       VARCHAR(255)       NOT NULL,
  city         VARCHAR(255)       NOT NULL,
  house_number SMALLINT           NOT NULL,
  postal_code  VARCHAR(255)       NOT NULL,
  email        VARCHAR(255)       NOT NULL,
  phone        VARCHAR(255)       NOT NULL,
  PRIMARY KEY (id)
)
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;

CREATE TABLE orderr (
  id          INT AUTO_INCREMENT NOT NULL,
  customer_id INT DEFAULT NULL,
  date        DATE               NOT NULL,
  payment     DATE               NOT NULL,
  processed   DATE               NOT NULL,
  INDEX IDX_9228CD789395C3F3 (customer_id),
  PRIMARY KEY (id)
)
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;

CREATE TABLE products_orders (
  order_id   INT NOT NULL,
  product_id INT NOT NULL,
  INDEX IDX_631C76C48D9F6D38 (order_id),
  INDEX IDX_631C76C44584665A (product_id),
  PRIMARY KEY (order_id, product_id)
)
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;

CREATE TABLE product (
  id          INT AUTO_INCREMENT NOT NULL,
  category_id INT DEFAULT NULL,
  name        VARCHAR(255)       NOT NULL,
  price       INT                NOT NULL,
  INDEX IDX_D34A04AD12469DE2 (category_id),
  PRIMARY KEY (id)
)
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;

CREATE TABLE products_category (
  id   INT AUTO_INCREMENT NOT NULL,
  name VARCHAR(255)       NOT NULL,
  PRIMARY KEY (id)
)
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;

ALTER TABLE orderr
  ADD CONSTRAINT FK_9228CD789395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id);

ALTER TABLE products_orders
  ADD CONSTRAINT FK_631C76C48D9F6D38 FOREIGN KEY (order_id) REFERENCES orderr (id)
  ON DELETE CASCADE;

ALTER TABLE products_orders
  ADD CONSTRAINT FK_631C76C44584665A FOREIGN KEY (product_id) REFERENCES product (id)
  ON DELETE CASCADE;

ALTER TABLE product
  ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES products_category (id);
