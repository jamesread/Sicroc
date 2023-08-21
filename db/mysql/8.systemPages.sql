-- +migrate Up
INSERT INTO pages (id, title, ident, isSystem) VALUES (1, 'Homepage', 'HOME', true);

-- +migrate Down
