# Sicroc

[![PHP CodeStyle](https://github.com/jamesread/Sicroc/actions/workflows/php-codestyle.yml/badge.svg)](https://github.com/jamesread/Sicroc/actions/workflows/php-codestyle.yml)

Build CRUD (Create, Read, Update and Delete) apps around the MySQL Database.

![var/screenshot.png](var/screenshot.png)

## Installation

```shell
docker pull ghcr.io/jamesread/sicroc:0.0.3
docker create --name sicroc -p 8080:8080 ghcr.io/jamesread/sicroc:0.0.3
```

## Project Overview

### **Sicroc is a No-Nonsense Open Source project;**

- All code and assets are Open Source (AGPL).
- No company is paying for development, there is no paid-for support from the developers.
- No separate core and premium version, no plus/pro version or paid-for extra features.
- No SaaS service or "special cloud version".
- No "anonymous data collection", usage tracking, user tracking, telemetry or email address collection.
- No requests for reviews in any "app store" or feedback surveys.
- No prompts to "upgrade to the latest version".
- No internet-connection required for any functionality.

### **Sicroc gets out of the way of your data;**

- Your data is stored in plain old MySQL tables.
- All Sicroc metadata is stored in a self contained database, meaning your data and tables are clean and safe if you move away from Sicroc. 
- Sicroc doesn't stop you using real data types and real foreign keys, etc. you can bring it to existing databases and it will just work. 

### **Sicroc has the following design goals;**

- **Zero lock-in**: It should be easy and safe to delete or migrate away from Sicroc at any time, and leave your data intact (and still very usable). 
- **Self-hackable**: easy to change layouts and functionality as you see fit.
- **Super cheap to host/run**: Any LAMP server with 512mb of RAM and a MySQL database will work.- millions of hosters can run Sicroc for $3/month.
- **Very low effort to maintain**: No Kubernetes, docker, services, python libraries or setup is needed. All database changes are migratable. 
- **Very low dependencies**: Sicroc only really uses libAllure (which is mostly a shim on core PHP features), and a library for OpenID connect. 

## Misc

Packagist.org link: https://packagist.org/packages/jamesread/sicroc
