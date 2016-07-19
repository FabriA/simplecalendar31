-- Dummy SQL file to set schema version so next update will work
ALTER TABLE `#__simplecalendar` ADD `recur_every` INT NOT NULL DEFAULT '0', ADD `recur_end_after` INT NOT NULL DEFAULT '0';