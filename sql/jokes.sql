DROP DATABASE IF EXISTS jokes;
CREATE DATABASE jokes;
USE jokes;

CREATE TABLE `settings` (
  `idSettings` int(10) UNSIGNED NOT NULL,
  `idUser` int(10) UNSIGNED NOT NULL,
  `category_any` tinyint(1) NOT NULL DEFAULT 1,
  `category_programming` tinyint(1) NOT NULL DEFAULT 0,
  `category_misc` tinyint(1) NOT NULL DEFAULT 0,
  `category_dark` tinyint(1) NOT NULL DEFAULT 0,
  `category_pun` tinyint(1) NOT NULL DEFAULT 0,
  `category_spooky` tinyint(1) NOT NULL DEFAULT 0,
  `category_christmas` tinyint(1) NOT NULL DEFAULT 0,
  `language_code` enum('cs','de','en','es','fr','pt') NOT NULL,
  `blacklist_nsfw` tinyint(1) NOT NULL DEFAULT 0,
  `blacklist_religious` tinyint(1) NOT NULL DEFAULT 0,
  `blacklist_political` tinyint(1) NOT NULL DEFAULT 0,
  `blacklist_racist` tinyint(1) NOT NULL DEFAULT 0,
  `blacklist_sexist` tinyint(1) NOT NULL DEFAULT 0,
  `blacklist_explicit` tinyint(1) NOT NULL DEFAULT 0,
  `safe_mode` tinyint(1) NOT NULL DEFAULT 0,
  `allow_single` tinyint(1) NOT NULL DEFAULT 1,
  `allow_two_part` tinyint(1) NOT NULL DEFAULT 0,
  `joke_amount` tinyint(3) UNSIGNED NOT NULL DEFAULT 1
);

CREATE TABLE `Users` (
  `idUser` int(10) UNSIGNED NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `userName` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(256) NOT NULL,
  `profilePicture` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `settings`
  ADD PRIMARY KEY (`idSettings`),
  ADD KEY `fk_settings_user` (`idUser`);

ALTER TABLE `Users`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`userName`);

ALTER TABLE `settings`
  MODIFY `idSettings` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `Users`
  MODIFY `idUser` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `settings`
  ADD CONSTRAINT `fk_settings_user` FOREIGN KEY (`idUser`) REFERENCES `Users` (`idUser`) ON DELETE CASCADE,
  ADD CONSTRAINT chk_joke_amount
        CHECK (joke_amount BETWEEN 1 AND 10),

   ADD CONSTRAINT chk_joke_type
        CHECK (allow_single = TRUE OR allow_two_part = TRUE),

   ADD CONSTRAINT chk_category_logic
        CHECK (
            (category_any = TRUE AND
                category_programming = FALSE AND
                category_misc = FALSE AND
                category_dark = FALSE AND
                category_pun = FALSE AND
                category_spooky = FALSE AND
                category_christmas = FALSE
            )
            OR
            (category_any = FALSE AND
                (
                    category_programming = TRUE OR
                    category_misc = TRUE OR
                    category_dark = TRUE OR
                    category_pun = TRUE OR
                    category_spooky = TRUE OR
                    category_christmas = TRUE
                )
            )
        )
