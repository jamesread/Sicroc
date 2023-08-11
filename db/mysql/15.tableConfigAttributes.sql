-- +migrate Up
ALTER TABLE table_configurations ADD (showId BOOLEAN default true, showTypes BOOLEAN default true);

-- +migrate Down
