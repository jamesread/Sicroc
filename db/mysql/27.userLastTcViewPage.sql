-- +migrate Up
ALTER TABLE users add lastTcViewPage int;

-- +migrate Down
