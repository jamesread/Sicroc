-- +migrate Up
ALTER TABLE `groups` DROP `id`;
ALTER TABLE `groups` ADD id int not null primary key auto_increment;

-- +migrate Down
