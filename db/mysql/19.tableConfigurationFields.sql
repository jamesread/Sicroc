-- +migrate Up
ALTER TABLE pages ENGINE = 'InnoDB';

ALTER TABLE table_configurations RENAME COLUMN listVerb to listPhrase;
ALTER TABLE table_configurations RENAME COLUMN insertVerb to createPhrase;
ALTER TABLE table_configurations MODIFY createPhrase varchar(128) default null AFTER listPhrase;
ALTER TABLE table_configurations ADD editPhrase varchar(128) default null AFTER createPhrase;
ALTER TABLE table_configurations ADD createPageDelegate int default null;
ALTER TABLE table_configurations ADD CONSTRAINT fk_createPageDelegate FOREIGN KEY (createPageDelegate) REFERENCES pages(id);
ALTER TABLE table_configurations ADD editPageDelegate int default null;
ALTER TABLE table_configurations ADD CONSTRAINT fk_editPageDelegate FOREIGN KEY (editPageDelegate) REFERENCES pages(id);

-- +migrate Down
