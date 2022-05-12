SELECT `domain`, LOCATE('/', `domain`), LEFT(`domain`, LOCATE('/', `domain`) - 1) FROM `wp_website` WHERE 1;

UPDATE `wp_website` SET `domain` = `website`;
UPDATE `wp_website` SET `domain` = REPLACE(`domain`, 'https://', '');
UPDATE `wp_website` SET `domain` = REPLACE(`domain`, 'http://', '');

UPDATE `wp_website` SET `domain` = LEFT(`domain`, LOCATE('/', `domain`) - 1) WHERE 1;
