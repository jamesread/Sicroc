-- +migrate Up
ALTER TABLE table_configurations ADD listVerb varchar(128) default null;

-- +migrate Down
