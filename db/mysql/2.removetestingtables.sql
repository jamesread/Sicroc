-- +migrate Up
drop table dataEnvironments;
drop table dataIpAddresses;
drop table dataLocations;
drop table dataMacAddresses;
drop table dataPurchaseOrders;

-- +migrate Down
