-- +migrate Up
ALTER TABLE widget_instances DROP KEY `type`;
ALTER TABLE widget_instances DROP COLUMN `principle`;
ALTER TABLE widget_instances DROP `method`; 
ALTER TABLE widget_instances ADD CONSTRAINT unique_wi UNIQUE (`title`, `type`);
ALTER TABLE page_content DROP `order`;

-- +migrate Down
