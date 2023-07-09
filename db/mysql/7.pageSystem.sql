-- +migrate Up
ALTER TABLE pages ADD isSystem tinyint default 0;

-- +migrate Down
