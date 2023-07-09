-- +migrate Up
INSERT INTO sections (id, title, master, `index`) VALUES (1, 'First section', 1, 1);

-- +migrate Down
