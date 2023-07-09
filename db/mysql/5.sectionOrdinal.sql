-- +migrate Up
alter table sections add ordinal int default 0;

-- +migrate Down
alter table sections drop ordinal;
