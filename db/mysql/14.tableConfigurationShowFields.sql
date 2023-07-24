-- +migrate Up
ALTER TABLE table_configurations ADD showId boolean not null default true;
ALTER TABLE table_configurations ADD showTypes boolean not null default true;
ALTER TABLE table_configurations ADD isSystem boolean not null default false;

-- +migrate Down
DROP TABLE table_configurations;
