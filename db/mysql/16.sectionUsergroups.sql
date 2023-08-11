-- +migrate Up
ALTER TABLE sections ADD (usergroup int default null);

-- +migrate Down
