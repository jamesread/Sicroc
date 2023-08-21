-- +migrate Up
CREATE TABLE table_conditional_formatting (id int not null primary key auto_increment, tc int not null, field varchar(64) not null, operator varchar(32), cell_value varchar(255), cell_style varchar(512), display_as varchar(64), priority_order int default 100);

-- +migrate Down
