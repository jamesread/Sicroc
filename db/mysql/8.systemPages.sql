-- +migrate Up
INSERT INTO pages (id, title, ident, isSystem) VALUES (1, 'Welcome', 'WELCOME', true);

-- +migrate Down
