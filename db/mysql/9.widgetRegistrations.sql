-- +migrate Up
alter table widget_instances add unique (`type`, principle(128));
alter table widget_types add unique (viewableController);
alter table page_content add unique (page, widget);
alter table widget_argument_values add unique (widget, `key`);

-- +migrate Down
