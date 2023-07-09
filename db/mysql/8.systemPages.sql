-- +migrate Up
INSERT INTO pages (id, title, isSystem) VALUES (0, 'Default page', true);
INSERT INTO pages (title, ident, isSystem) VALUES 
	('Admin panel', 'ADMIN', true),
	('Logout', 'LOGOUT', true),
	('Create page', 'PAGE_CREATE', true),
	('List of pages', 'PAGE_LIST', true),
	('Update page', 'PAGE_UPDATE', true),
	('Create Section', 'SECTION_CREATE', true),
	('List of Sections', 'SECTION_LIST', true),
	('Update Section', 'SECTION_UPDATE', true),
	('Insert Row', 'TABLE_INSERT', true),
	('View Row', 'TABLE_ROW', true),
	('Delete Row', 'TABLE_ROW_DELETE', true),
	('Edit Row', 'TABLE_ROW_EDIT', true),
	('Table Structure', 'TABLE_STRUCTURE', true),
	('Create Widget', 'WIDGET_CREATE', true),
	('Update Widget', 'WIDGET_INSTANCE_UPDATE', true),
	('List of Widgets', 'WIDGET_LIST', true),
	('Register Widget', 'WIDGET_REGISTER', true),
	('Edit Wiki', 'WIKI_EIDT', true)
	;

ALTER TABLE content RENAME TO page_content;

-- +migrate Down
