-- +migrate Up
ALTER TABLE users add editMode tinyint default 0;

-- +migrate Down
