-- +migrate Up
ALTER TABLE sections RENAME TO navigation_links;
ALTER TABLE navigation_links RENAME COLUMN `index` TO index_page;
ALTER TABLE navigation_links ADD CONSTRAINT fk_index_page FOREIGN KEY (index_page) REFERENCES pages (id);

-- +migrate Down
