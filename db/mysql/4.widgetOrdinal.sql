-- +migrate Up
alter table content add ordinal int default 0;

-- +migrate Down
alter table content drop ordinal;
