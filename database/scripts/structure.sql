SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema egzamin_zawodowy
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema egzamin_zawodowy
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `egzamin_zawodowy` DEFAULT CHARACTER SET utf8 COLLATE utf8_polish_ci ;
USE `egzamin_zawodowy` ;

-- -----------------------------------------------------
-- Table `egzamin_zawodowy`.`questions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `egzamin_zawodowy`.`questions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` MEDIUMTEXT NOT NULL,
  `image_path` TINYTEXT NULL DEFAULT NULL,
  `next_repetition_time` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `egzamin_zawodowy`.`answers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `egzamin_zawodowy`.`answers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` MEDIUMTEXT NOT NULL,
  `is_correct` TINYINT(1) NOT NULL DEFAULT 0,
  `ques_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `FK_anrs_ques_idx` (`ques_id` ASC) VISIBLE,
  CONSTRAINT `FK_answ_ques`
    FOREIGN KEY (`ques_id`)
    REFERENCES `egzamin_zawodowy`.`questions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `egzamin_zawodowy`.`users_data`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `egzamin_zawodowy`.`users_data` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ques_id` INT NOT NULL,
  `answ_id` INT NULL,
  `view_date_time` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `FK_udat_ques_idx` (`ques_id` ASC) VISIBLE,
  INDEX `FK_udat_answ_idx` (`answ_id` ASC) VISIBLE,
  CONSTRAINT `FK_udat_ques`
    FOREIGN KEY (`ques_id`)
    REFERENCES `egzamin_zawodowy`.`questions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_udat_answ`
    FOREIGN KEY (`answ_id`)
    REFERENCES `egzamin_zawodowy`.`answers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `egzamin_zawodowy` ;

-- -----------------------------------------------------
-- procedure addQuestion
-- -----------------------------------------------------

DELIMITER $$
USE `egzamin_zawodowy`$$
CREATE PROCEDURE `addQuestion` (IN content MEDIUMTEXT, IN has_img TINYINT(1), IN img_path TINYTEXT)
BEGIN
	IF has_img = 0 THEN
		INSERT INTO `questions` (`content`) VALUES (content);
    ELSEIF has_img = 1 THEN
		INSERT INTO `questions` (`content`, `image_path`) VALUES (content, img_path);
    ELSE
		SELECT "Nie udało się dodać pytania.";
    END IF;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure addAnswer
-- -----------------------------------------------------

DELIMITER $$
USE `egzamin_zawodowy`$$
CREATE PROCEDURE `addAnswer`(IN ques_id INT, IN content MEDIUMTEXT, IN is_correct BOOLEAN)
BEGIN
	INSERT INTO answers (`content`, `is_correct`, `ques_id`) VALUES (content, is_correct, ques_id);
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure addQuestionReply
-- -----------------------------------------------------

DELIMITER $$
USE `egzamin_zawodowy`$$
CREATE PROCEDURE `addQuestionReply`(IN ques_id INT, IN answer_content MEDIUMTEXT)
BEGIN
    DECLARE answer_id INT;
	IF (answer_content != "Nie wiem") THEN 
		SET answer_id = (SELECT answers.id FROM answers WHERE `content` = answer_content AND `ques_id` = ques_id LIMIT 1);
		INSERT INTO users_data (`ques_id`, `answ_id`, `view_date_time`) VALUES (ques_id, answer_id, NOW());
		SELECT `is_correct` FROM answers WHERE `id` = answer_id;
	ELSE 
		INSERT INTO users_data (`ques_id`, `view_date_time`) VALUES (ques_id, NOW());
        SELECT "0";
    END IF;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure getAnswersRelatedToQuestion
-- -----------------------------------------------------

DELIMITER $$
USE `egzamin_zawodowy`$$
CREATE PROCEDURE `getAnswersRelatedToQuestion`(IN question_id INT)
BEGIN
	SELECT `id`, `content`, `is_correct` FROM answers WHERE `ques_id` = question_id;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure getLatestAddedQuestionId
-- -----------------------------------------------------

DELIMITER $$
USE `egzamin_zawodowy`$$
CREATE PROCEDURE `getLatestAddedQuestionId`()
BEGIN
	SELECT id FROM questions ORDER BY id DESC LIMIT 1;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure getRandomQuestion
-- -----------------------------------------------------

DELIMITER $$
USE `egzamin_zawodowy`$$
CREATE PROCEDURE `getRandomQuestion` ()
BEGIN
SELECT * FROM `questions`order by rand() limit 1;
END$$

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;