-- +migrate Up
alter table page_content add ordinal int default 0;

-- +migrate Down
alter table page_content drop ordinal;
