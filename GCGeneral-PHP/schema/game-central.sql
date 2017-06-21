CREATE TABLE IF NOT EXISTS `genres` (
  `genre_id` INT NOT NULL AUTO_INCREMENT,
  `genre` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`genre_id`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `games` (
  `game_id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `price` DOUBLE NOT NULL,
  `genre_id` INT NOT NULL,
  `type` ENUM('DIGITAL', 'PHYSICAL') NOT NULL,
  `download_url` VARCHAR(255),
  `quantity` INT DEFAULT 0,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`game_id`),
  INDEX `fk_game_genre_idx` (`genre_id` ASC),
  CONSTRAINT `fk_game_genre`
    FOREIGN KEY (`genre_id`)
    REFERENCES `genres` (`genre_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` INT NOT NULL AUTO_INCREMENT,
  `game_id` INT NOT NULL,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NULL,
  `email` VARCHAR(255) NOT NULL,
  `address` TEXT NULL,
  `quantity` INT NOT NULL DEFAULT '1',
  `total_paid` DOUBLE NOT NULL DEFAULT '0',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  INDEX `fk_orderr_game_idx` (`game_id` ASC),
  CONSTRAINT `fk_orderr_game`
    FOREIGN KEY (`game_id`)
    REFERENCES `games` (`game_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

