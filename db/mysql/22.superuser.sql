-- +migrate Up
INSERT INTO permissions (id, `key`) VALUES (1, 'SUPERUSER');

-- +migrate Down
