<?php

/**
 * PHP - FolioFrame
 *
 * A PHP MVC mini-framework using modern PHP design security, 
 * with the intent of usage for online portfolios, personal webpages,
 * small business sites, etc.. 
 *
 * See README.md for more information 
 *     --> https://github.com/gavbro/php-folioframe/blob/main/README.md
 *
 *
 * @package    php-folioframe
 * @copyright  2014-2021 Gavin Brown
 * @license    MIT License through GITHUB
 * @git        https://github.com/gavbro/php-folioframe
 * @link       https://gavinbrown.ca/
 * @since      See the README for current overall version info. 
 *
 * @file       version 0.0.1
 *
 * @author Gavin Brown <gavin@gavinbrown.ca>
 *
 */

// Sorry if this isn't commented below. I tried using -- comments, but the 
// string would break. I intend on make the commenting of this another readme file or
// part of the main README.

return "DROP TABLE IF EXISTS `{dbase_install_prefix}attempt`;
CREATE TABLE `{dbase_install_prefix}attempt` (
  `attempt_id` int(11) NOT NULL, 
  `at_code_id` int(11) NOT NULL, 
  `at_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE={dbase_engine} DEFAULT CHARSET={dbase_char};

DROP TABLE IF EXISTS `{dbase_install_prefix}code`;
CREATE TABLE `{dbase_install_prefix}code` (
  `code_id` int(11) NOT NULL COMMENT 'ID of code attempt',
  `cd_en_id` int(11) NOT NULL COMMENT 'User ID, which ties to email. ',
  `cd_hash` varchar(64) NOT NULL COMMENT 'Hash for resuming code try.',
  `cd_code` varchar(8) NOT NULL COMMENT 'Code sent to email to match.',
  `cd_stamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cd_status` int(11) NOT NULL DEFAULT '2' COMMENT 'The status of the code, 0=disabled, 1=active, 2=verified'
) ENGINE={dbase_engine} DEFAULT CHARSET={dbase_char};

DROP TABLE IF EXISTS `{dbase_install_prefix}codetry`;
CREATE TABLE `{dbase_install_prefix}codetry` (
  `codetry_id` int(11) NOT NULL,
  `ct_code_id` int(11) NOT NULL,
  `ct_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE={dbase_engine} DEFAULT CHARSET={dbase_char};

DROP TABLE IF EXISTS `{dbase_install_prefix}entry`;
CREATE TABLE `{dbase_install_prefix}entry` (
  `en_id` int(11) NOT NULL COMMENT 'User ID',
  `en_hash` varchar(255) NOT NULL COMMENT 'Hash of email to detect duplicates.',
  `en_email` varchar(255) NOT NULL COMMENT 'Visitor Email',
  `en_name` varchar(255) DEFAULT NULL COMMENT 'What do I call you?',
  `en_linked` varchar(255) DEFAULT NULL COMMENT 'LinkedIN',
  `en_fbook` varchar(255) DEFAULT NULL COMMENT 'Facebook ID',
  `en_twit` varchar(255) DEFAULT NULL COMMENT 'Twitter ID',
  `en_git` varchar(255) DEFAULT NULL COMMENT 'GitHub ID',
  `en_reddit` varchar(255) DEFAULT NULL COMMENT 'Reddit ID',
  `en_insta` varchar(255) DEFAULT NULL COMMENT 'Instagram ID',
  `en_stage` int(11) NOT NULL DEFAULT '1' COMMENT 'Current stage of induction. zero is banned.',
  `en_level` int(11) NOT NULL DEFAULT '2' COMMENT 'Defines the current user level, 0 = denied, 1 = admin, 2 = user'
) ENGINE={dbase_engine} DEFAULT CHARSET={dbase_char};

ALTER TABLE `{dbase_install_prefix}attempt`
  ADD PRIMARY KEY (`attempt_id`);

ALTER TABLE `{dbase_install_prefix}code`
  ADD PRIMARY KEY (`code_id`);

ALTER TABLE `{dbase_install_prefix}codetry`
  ADD PRIMARY KEY (`codetry_id`);

ALTER TABLE `{dbase_install_prefix}entry`
  ADD PRIMARY KEY (`en_id`);

ALTER TABLE `{dbase_install_prefix}attempt`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `{dbase_install_prefix}code`
  MODIFY `code_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID of code attempt', AUTO_INCREMENT=1;

ALTER TABLE `{dbase_install_prefix}codetry`
  MODIFY `codetry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `{dbase_install_prefix}entry`
  MODIFY `en_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'User ID', AUTO_INCREMENT=1;

DROP PROCEDURE IF EXISTS `{dbase_name}`.`auth_attempts`;
CREATE PROCEDURE `{dbase_name}`.`auth_attempts`
 (
	IN `cId` INT(11), 
	OUT `isOK` TINYINT(1)
	) 
	BEGIN
	
	DECLARE aID INT(11);
	DECLARE bID INT(11);
	
	SELECT COUNT(`{dbase_install_prefix}attempt`.`attempt_id`) INTO bID FROM `{dbase_name}`.`{dbase_install_prefix}attempt` WHERE `{dbase_install_prefix}attempt`.`at_code_id` = cId AND `{dbase_install_prefix}attempt`.`at_stamp` > date_add(CURRENT_TIMESTAMP, INTERVAL -30 MINUTE);
	CASE 
		WHEN bID > 4 THEN
			SET isOK = 0;
		ELSE
			SELECT COUNT(`{dbase_install_prefix}attempt`.`attempt_id`) INTO aID FROM `{dbase_name}`.`{dbase_install_prefix}attempt` WHERE `{dbase_install_prefix}attempt`.`at_code_id` = cId AND `{dbase_install_prefix}attempt`.`at_stamp` > date_add(CURRENT_TIMESTAMP, INTERVAL -5 MINUTE);
			
			CASE 
				WHEN aID > 2 THEN
					SET isOK = 0;
				ELSE
					INSERT INTO `{dbase_name}`.`{dbase_install_prefix}attempt` (`{dbase_install_prefix}attempt`.`at_code_id`) VALUES (cId);
					SET isOK = 1;
			END CASE ;
		END CASE ;
	END ;

DROP PROCEDURE IF EXISTS `{dbase_name}`.`auth_codeCheck`;
CREATE PROCEDURE `{dbase_name}`.`auth_codeCheck`
 (IN `inHash` VARCHAR(64), IN `inCode` VARCHAR(8), IN `setMins` INT(11), IN `setTries` INT(11), OUT `allOK` TINYINT(1), OUT `isLocked` TINYINT(1), OUT `stage` TINYINT(1), OUT `userid` VARCHAR(255), OUT `access` TINYINT(1))  BEGIN

	DECLARE tmpCode1 INT(11);
	DECLARE tmpCode2 INT(11);
	DECLARE tmpEmailID INT(11);
	DECLARE timeout INT(11);
	DECLARE tries INT(11);
	
	SET timeout = setMins*-1;
	SET stage = 0;
	SET access = 0;
	SET isLocked = 0;
	
	SELECT `{dbase_install_prefix}code`.`code_id` INTO tmpCode1 FROM `{dbase_name}`.`{dbase_install_prefix}code` WHERE `{dbase_install_prefix}code`.`cd_hash` = inHash  AND `{dbase_install_prefix}code`.`cd_status` > 0 AND `{dbase_install_prefix}code`.`cd_stamp` > date_add(CURRENT_TIMESTAMP, INTERVAL timeout MINUTE) LIMIT 1;
	CASE
		WHEN tmpCode1 > 0 THEN
			SELECT count(`{dbase_install_prefix}code`.`code_id`) INTO tmpCode2 FROM `{dbase_name}`.`{dbase_install_prefix}code` WHERE `{dbase_install_prefix}code`.`code_id` = tmpCode1 AND `{dbase_install_prefix}code`.`cd_code` = inCode LIMIT 1;
			CASE WHEN tmpCode2 > 0 THEN
				SELECT `{dbase_install_prefix}code`.`cd_en_id` INTO tmpEmailID FROM `{dbase_name}`.`{dbase_install_prefix}code` WHERE `{dbase_install_prefix}code`.`code_id` = tmpCode1 LIMIT 1;
				SELECT `{dbase_install_prefix}entry`.`en_stage` INTO stage FROM `{dbase_name}`.`{dbase_install_prefix}entry` WHERE `{dbase_install_prefix}entry`.`en_id` = tmpEmailID LIMIT 1;
				SELECT `{dbase_install_prefix}entry`.`en_hash` INTO userid FROM `{dbase_name}`.`{dbase_install_prefix}entry` WHERE `{dbase_install_prefix}entry`.`en_id` = tmpEmailID LIMIT 1;
				SELECT `{dbase_install_prefix}entry`.`en_level` INTO access FROM `{dbase_name}`.`{dbase_install_prefix}entry` WHERE `{dbase_install_prefix}entry`.`en_id` = tmpEmailID LIMIT 1;
				UPDATE `{dbase_name}`.`{dbase_install_prefix}code` SET `{dbase_install_prefix}code`.`cd_status` = 0 WHERE `{dbase_install_prefix}code`.`code_id` = tmpCode1;
				SET allOk = 1;
			ELSE
				SELECT count(`{dbase_install_prefix}codetry`.`codetry_id`) INTO tries FROM `{dbase_name}`.`{dbase_install_prefix}codetry` WHERE `{dbase_install_prefix}codetry`.`ct_code_id` = tmpCode1;
				CASE
					WHEN tries < setTries THEN
						INSERT INTO `{dbase_name}`.`{dbase_install_prefix}codetry` (`{dbase_install_prefix}codetry`.`ct_code_id`) VALUES (tmpCode1);
					ELSE
						UPDATE `{dbase_name}`.`{dbase_install_prefix}code` SET `{dbase_install_prefix}code`.`cd_status` = 0 WHERE `{dbase_install_prefix}code`.`code_id` = tmpCode1;
						SET isLocked = 1;
					END CASE;
					SET allOK = 0;
			END CASE;
		ELSE
			SET allOK = 0;
		END CASE ;
END ;

DROP PROCEDURE IF EXISTS `{dbase_name}`.`auth_email`;
CREATE PROCEDURE `{dbase_name}`.`auth_email`
 (IN `email` VARCHAR(255), IN `secemail` VARCHAR(255), IN `setMins` INT(11), IN `mCode` INT(11), OUT `hash` VARCHAR(64), OUT `code` VARCHAR(8), OUT `emOK` TINYINT(1))  BEGIN
	DECLARE enid INT(11);
	DECLARE LID INT(11);
	DECLARE rCnt INT(11);
	DECLARE timeout INT(11);
  DECLARE maxCode INT(11);
	
	CASE 
		WHEN mCode = Null OR (mCode < 4 OR mCode > 8) THEN
			SET maxCode = 6;
		ELSE
			SET maxCode = mCode;
	END CASE ;

	
	SET timeout = setMins*-1;
	
	SET enid = 0;
	
	SELECT `{dbase_install_prefix}entry`.`en_id` INTO enid FROM `{dbase_name}`.`{dbase_install_prefix}entry` WHERE `{dbase_install_prefix}entry`.`en_hash` = SHA2(email, 256) LIMIT 1;
	
	CASE 
		WHEN enid > 0 THEN
			Call auth_emhash(enid, timeout, maxCode, @cID, @Hash, @emOK);
			SELECT @emOK INTO emOK;
	ELSE
		INSERT INTO `{dbase_name}`.`{dbase_install_prefix}entry` (`{dbase_install_prefix}entry`.`en_hash`,`{dbase_install_prefix}entry`.`en_email`) VALUES (SHA2(email, 256), secemail); 
		SET LID = LAST_INSERT_ID();
		Call auth_emhash(LID, timeout, maxCode, @cID, @Hash, @emOK);
		SELECT @emOK INTO emOK;
	END CASE ;
	SELECT @cID INTO code;
	SELECT @Hash INTO hash;
END ;

DROP PROCEDURE IF EXISTS `{dbase_name}`.`auth_emhash`;
CREATE PROCEDURE `{dbase_name}`.`auth_emhash`
 (IN `eNid` INT(11), IN `timeout` INT(11), IN `maxCode` INT(11), OUT `cId` VARCHAR(8), OUT `hash` VARCHAR(64), OUT `emOK` TINYINT(1))  BEGIN
	DECLARE authHash VARCHAR(64);
	DECLARE authCode VARCHAR(8);
	DECLARE eKcd INT(11);
	DECLARE rCnt INT(11);
	DECLARE tmpInt INT(11);
	DECLARE tmpMulti INT(11);
	DECLARE isOK TINYINT(1);
	
	SET eKcd = 0;
	SET emOK = 0;
	SET authHash = SHA2(RAND(), 256);
	
	
	SELECT RPAD(1, (maxCode+1), 0) INTO tmpMulti;
	
	SELECT SUBSTR(LPAD(ROUND(RAND()*tmpMulti, 0), maxCode, 0), 1, maxCode) INTO authCode;	
	
	SELECT `{dbase_install_prefix}code`.`code_id` INTO eKcd FROM `{dbase_name}`.`{dbase_install_prefix}code` WHERE `{dbase_install_prefix}code`.`cd_en_id` = eNid AND `{dbase_install_prefix}code`.`cd_stamp` > date_add(CURRENT_TIMESTAMP, INTERVAL timeout MINUTE) AND `{dbase_install_prefix}code`.`cd_status` <> 0 ORDER BY `{dbase_install_prefix}code`.`cd_stamp` DESC LIMIT 1;
	CASE 
		WHEN eKcd > 0 THEN
			CALL auth_attempts(eKcd, @isOk);
			SELECT @isOK INTO isOK;
			CASE 
				WHEN isOK = 1 THEN
					SELECT `{dbase_install_prefix}code`.`cd_hash` INTO hash FROM `{dbase_name}`.`{dbase_install_prefix}code` WHERE `{dbase_install_prefix}code`.`code_id` = eKcd LIMIT 1;
					SELECT `{dbase_install_prefix}code`.`cd_code` INTO cId FROM `{dbase_name}`.`{dbase_install_prefix}code` WHERE `{dbase_install_prefix}code`.`code_id` = eKcd LIMIT 1;
					SET emOK = 1;
				ELSE
					SET emOK = 0;
			END CASE ;
	ELSE
		INSERT INTO `{dbase_name}`.`{dbase_install_prefix}code` (`{dbase_install_prefix}code`.`cd_en_id`, `{dbase_install_prefix}code`.`cd_code`, `{dbase_install_prefix}code`.`cd_hash`) VALUES (eNid, authCode, authHash);
		
		SET cId = authCode;
		SET hash = authhash;
		SET tmpInt = LAST_INSERT_ID();
		
		INSERT INTO `{dbase_name}`.`{dbase_install_prefix}attempt` (`{dbase_install_prefix}attempt`.`at_code_id`) VALUES (tmpInt);
		SET emOK = 1;
	END CASE ;
END ;

DROP PROCEDURE IF EXISTS `{dbase_name}`.`auth_getEmail`;
CREATE PROCEDURE `{dbase_name}`.`auth_getEmail`
 (IN `hashID` VARCHAR(64), IN `minSet` INT(11), OUT `mailcode` VARCHAR(255))  BEGIN
	DECLARE mc VARCHAR(255);
	DECLARE timeout INT(11);
	
	SET timeout = minSet*-1;
	
	SELECT `{dbase_install_prefix}entry`.`en_email` INTO mc FROM `{dbase_name}`.`{dbase_install_prefix}entry`, `{dbase_name}`.`{dbase_install_prefix}code` WHERE `{dbase_install_prefix}code`.`cd_hash` = hashID AND `{dbase_install_prefix}code`.`cd_en_id` = `{dbase_install_prefix}entry`.`en_id` AND `{dbase_install_prefix}code`.`cd_stamp` > date_add(CURRENT_TIMESTAMP, INTERVAL timeout MINUTE) LIMIT 1;
	CASE
		WHEN LENGTH(mc) > 0 THEN
		SET mailcode = mc;
	ELSE
		SET mailcode = 'None';
	END CASE ;
END ;

DROP PROCEDURE IF EXISTS `{dbase_name}`.`auth_getIDHash`;
CREATE PROCEDURE `{dbase_name}`.`auth_getIDHash`
 (IN `hashID` VARCHAR(64), IN `minSet` INT(11), OUT `mailcode` VARCHAR(255))  BEGIN
	DECLARE mc VARCHAR(255);
	DECLARE timeout INT(11);
	
	SET timeout = minSet*-1;
	
	SELECT `{dbase_install_prefix}entry`.`en_email` INTO mc FROM `{dbase_name}`.`{dbase_install_prefix}entry`, `{dbase_name}`.`{dbase_install_prefix}code` WHERE `{dbase_install_prefix}code`.`cd_hash` = hashID AND `{dbase_install_prefix}code`.`cd_en_id` = `{dbase_install_prefix}entry`.`en_id` AND `{dbase_install_prefix}code`.`cd_stamp` > date_add(CURRENT_TIMESTAMP, INTERVAL timeout MINUTE) LIMIT 1;
	CASE
		WHEN LENGTH(mc) > 0 THEN
		SET mailcode = mc;
	ELSE
		SET mailcode = 'None';
	END CASE ;
END ;

DROP PROCEDURE IF EXISTS `{dbase_name}`.`auth_getUserDetails`;
CREATE PROCEDURE `{dbase_name}`.`auth_getUserDetails`
 (IN `hashID` VARCHAR(64), OUT `encMail` VARCHAR(255), OUT `encName` VARCHAR(255), OUT `encLinked` VARCHAR(255), OUT `encFbook` VARCHAR(255), OUT `encTwit` VARCHAR(255), OUT `encGit` VARCHAR(255), OUT `encReddit` VARCHAR(255), OUT `encInsta` VARCHAR(255))  BEGIN
	
	SELECT `{dbase_install_prefix}entry`.`en_email` INTO encMail FROM `{dbase_name}`.`{dbase_install_prefix}entry` WHERE `{dbase_install_prefix}entry`.`en_hash` = hashID LIMIT 1;
	SELECT `{dbase_install_prefix}entry`.`en_name` INTO encName FROM `{dbase_name}`.`{dbase_install_prefix}entry` WHERE `{dbase_install_prefix}entry`.`en_hash` = hashID LIMIT 1;
	SELECT `{dbase_install_prefix}entry`.`en_linked` INTO encLinked FROM `{dbase_name}`.`{dbase_install_prefix}entry` WHERE `{dbase_install_prefix}entry`.`en_hash` = hashID LIMIT 1;
	SELECT `{dbase_install_prefix}entry`.`en_fbook` INTO encFbook FROM `{dbase_name}`.`{dbase_install_prefix}entry` WHERE `{dbase_install_prefix}entry`.`en_hash` = hashID LIMIT 1;
	SELECT `{dbase_install_prefix}entry`.`en_twit` INTO encTwit FROM `{dbase_name}`.`{dbase_install_prefix}entry` WHERE `{dbase_install_prefix}entry`.`en_hash` = hashID LIMIT 1;
	SELECT `{dbase_install_prefix}entry`.`en_git` INTO encGit FROM `{dbase_name}`.`{dbase_install_prefix}entry` WHERE `{dbase_install_prefix}entry`.`en_hash` = hashID LIMIT 1;
	SELECT `{dbase_install_prefix}entry`.`en_reddit` INTO encReddit FROM `{dbase_name}`.`{dbase_install_prefix}entry` WHERE `{dbase_install_prefix}entry`.`en_hash` = hashID LIMIT 1;
	SELECT `{dbase_install_prefix}entry`.`en_insta` INTO encInsta FROM `{dbase_name}`.`{dbase_install_prefix}entry` WHERE `{dbase_install_prefix}entry`.`en_hash` = hashID LIMIT 1;
END ;


DROP PROCEDURE IF EXISTS `{dbase_name}`.`auth_hashCheck`;
CREATE PROCEDURE `{dbase_name}`.`auth_hashCheck`
 (IN `inHash` VARCHAR(64), IN `setMins` INT(11), OUT `isHash` TINYINT(1))  BEGIN

	DECLARE tmpCode INT(11);
	DECLARE timeout INT(11);
	
	SET timeout = setMins*-1;
	
	SELECT `{dbase_install_prefix}code`.`code_id` INTO tmpCode FROM `{dbase_name}`.`{dbase_install_prefix}code` WHERE `{dbase_install_prefix}code`.`cd_hash` = inHash AND `{dbase_install_prefix}code`.`cd_stamp` > date_add(CURRENT_TIMESTAMP, INTERVAL timeout MINUTE) AND `{dbase_install_prefix}code`.`cd_status` <> 0 ORDER BY `{dbase_install_prefix}code`.`cd_stamp` DESC LIMIT 1;
	CASE 
		WHEN tmpCode > 0 THEN
			SET isHash = 1;
	ELSE	
			SET isHash = 0;
	END CASE ;
END ;

COMMIT;";