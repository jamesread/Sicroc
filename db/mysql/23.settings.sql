-- +migrate Up
CREATE TABLE site_settings (id int not null primary key auto_increment, setting_key varchar(64) not null unique, setting_value varchar(128));

-- +migrate Down
