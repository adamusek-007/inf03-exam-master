SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema egzamin_zawodowy
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema egzamin_zawodowy
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `egzamin_zawodowy` DEFAULT CHARACTER SET utf8mb4;
USE `egzamin_zawodowy` ;

-- -----------------------------------------------------
-- Table `egzamin_zawodowy`.`questions` (alias ques)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `egzamin_zawodowy`.`questions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` MEDIUMTEXT NOT NULL,
  `image_path` TINYTEXT NULL DEFAULT NULL,
  `next_repetition_time` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `egzamin_zawodowy`.`answers` (alias anrs)
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
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `egzamin_zawodowy`.`users_data` (alias udat)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `egzamin_zawodowy`.`users_data` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `answ_id` INT NULL,
  `view_date_time` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `FK_udat_anrs_idx` (`answ_id` ASC) VISIBLE,
  CONSTRAINT `FK_udat_anrs`
    FOREIGN KEY (`answ_id`)
    REFERENCES `egzamin_zawodowy`.`answers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- procedure addQuestion
-- -----------------------------------------------------

DELIMITER $$
CREATE PROCEDURE `addQuestion` (IN in_content MEDIUMTEXT, IN in_has_img TINYINT(1), IN in_img_path TINYTEXT)
BEGIN
	IF in_has_img = 0 THEN
		INSERT INTO `questions` (`content`) VALUES (in_content);
    ELSEIF in_has_img = 1 THEN
		INSERT INTO `questions` (`content`, `image_path`) VALUES (in_content, in_img_path);
    ELSE
		SELECT "Nie udało się dodać pytania.";
    END IF;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure addAnswer
-- -----------------------------------------------------

DELIMITER $$
CREATE PROCEDURE `addAnswer`(IN in_ques_id INT, IN in_content MEDIUMTEXT, IN in_is_correct BOOLEAN)
BEGIN
	INSERT INTO `answers` (`content`, `is_correct`, `ques_id`) VALUES (in_content, in_is_correct, in_ques_id);
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure addQuestionReply
-- -----------------------------------------------------

DELIMITER $$
CREATE PROCEDURE `addQuestionReply`(IN in_ques_id INT, IN in_answer_content MEDIUMTEXT)
BEGIN
    DECLARE answer_id INT;
	IF (in_answer_content != "Nie wiem") THEN 
		SET answer_id = (SELECT `answers`.`id` FROM `answers` WHERE `content` = in_answer_content AND `ques_id` = in_ques_id LIMIT 1);
		INSERT INTO `users_data` (`answ_id`, `view_date_time`) VALUES (answer_id, NOW());
	-- ELSE
		-- INSERT INTO `users_data` (`ques_id`, `view_date_time`) VALUES (in_ques_id, NOW());
    END IF;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure getQuestionAnswers
-- -----------------------------------------------------

DELIMITER $$
CREATE PROCEDURE `getQuestionAnswers`(IN in_ques_id INT)
BEGIN
	SELECT `id`, `content`, `is_correct` FROM `answers` WHERE `ques_id` = in_ques_id;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure getLatestAddedQuestionId
-- -----------------------------------------------------

DELIMITER $$
CREATE PROCEDURE `getLatestAddedQuestionId`()
BEGIN
	SELECT `id` FROM `questions` ORDER BY `id` DESC LIMIT 1;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure getRandomQuestion
-- -----------------------------------------------------

DELIMITER $$
CREATE PROCEDURE `getRandomQuestion` ()
BEGIN
  SELECT * FROM `questions`order by rand() limit 1;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure getAnswerCorrectness
-- -----------------------------------------------------

DELIMITER $$
CREATE PROCEDURE `getAnswerCorrectness` (IN question_id INT, IN answer_content MEDIUMTEXT)
BEGIN
	DECLARE answer_id INT;
    
	IF answer_content != "Nie wiem" THEN
		SET answer_id = (SELECT answers.id FROM answers WHERE `content` = answer_content AND `ques_id` = question_id LIMIT 1);
		SELECT `is_correct` FROM answers WHERE `id` = answer_id;
    ELSE 
		SELECT "0" AS `is_correct`;
    END IF;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure getQuestionCorrectAnswer
-- -----------------------------------------------------

DELIMITER $$
CREATE PROCEDURE `getQuestionCorrectAnswer` (IN question_id INT)
BEGIN
	SELECT `content` FROM `answers` WHERE `is_correct` = 1 AND `ques_id` = question_id;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure getQuestionsCardsView
-- -----------------------------------------------------

DELIMITER $$
CREATE PROCEDURE `getQuestionsCardsView` ()
BEGIN
  -- This view needs to be in the procedure because of MySQL formats views and makes in where statment TRUE
  SELECT 
  MAX(`reply_date_time`) AS `last_viewed`,
  COUNT(`answer_id`) AS `times_replied`,
  `question_id` AS `ques_id`,
  `question_content` AS `question_content`,
  (SELECT COUNT(`answer_id`)
    FROM `v_everything`
    WHERE 
      `question_id` = `ques_id` AND 
      `answer_correctness` = 1
  ) AS `correct_answers`,
  (SELECT COUNT(`answer_id`)
    FROM `v_everything`
    WHERE
      `question_id` = `ques_id` AND 
      `answer_correctness` = 0
  ) AS `incorrect_answers`
  FROM
  `v_everything`
  GROUP BY `question_id`;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- View `egzamin_zawodowy`.`v_everything`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `egzamin_zawodowy`.`v_everything`;
USE `egzamin_zawodowy`;
CREATE VIEW `v_everything` AS
  SELECT `users_data`.`id` AS `reply_id`,
  `users_data`.`view_date_time` AS `reply_date_time`,
  `users_data`.`answ_id` AS `answer_id`,
  `answers`.`content` AS `answer_content`,
  `answers`.`is_correct` AS `answer_correctness`,
  `questions`.`id` AS `question_id`,
  `questions`.`content` AS `question_content`
  FROM `users_data` 
  JOIN `answers` ON `users_data`.`answ_id` = `answers`.`id`
  join `questions` on `answers`.`ques_id` = `questions`.`id`;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;