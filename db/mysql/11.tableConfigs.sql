-- +migrate Up
CREATE TABLE table_configurations (id int not null primary key auto_increment, `table` varchar(128), `database` varchar(128), orderColumn varchar(128), orderAsc boolean default false, insertVerb varchar(128)) ;
ALTER TABLE table_configurations ADD CONSTRAINT unique_tc UNIQUE (`table`, `database`);

-- +migrate Down
DROP TABLE table_configurations;
