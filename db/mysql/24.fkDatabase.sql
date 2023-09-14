-- +migrate Up
ALTER TABLE table_fk_metadata ADD foreignDatabase varchar(64);

-- +migrate Down
