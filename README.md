# PubMaster

## Info

**A web archive of publications**

The web service acts as a repository of scientific publications, conference proceedings and software licenses. It is extremely useful for exporting references in the required format, synchronizing with scientific databases (Scopus, e-Library), batch downloading, etc.

The web service was deployed on the infrastructure of Samara University, implemented into the department processes and supported from 2016 to 2024.

## Table of Contents
- [Features](#features)
  - [Common](#common)
  - [Functionality](#functionality)
- [Installation](#installation)
  - [For development](#for-development)
  - [For production](#for-production)
  - [For backup](#for-backup)
- [License](#license)

## Features

### Common
- PHP v7.4
- WordPress v5.8.3
- Bootstrap v4.1.1
- jQuery v3.3.1
- MySQL v8
- Mobile-friendly
- Dockerized

### Functionality
- Submission wizard with automatic parsing of publication metadata
- Formatted export with customizable templates
- Synchronizing with Scopus and e-Library by author's ID
- Batch downloading
- CRUD for Papers, Proceedings and Software licenses
- Publication statistics
- PDF-preview
- Quick search
- Filtering

## Installation

### For development

1. Create `.env`

2. Create `wp-config.php`

3. Fill in the salt in `wp-config.php`
```sh
curl https://api.wordpress.org/secret-key/1.1/salt/
```

4. Deploy
```sh
./deploy.sh
```

5. Create admin user
```sh
docker exec -it dev_pub_server php init.php
```

### For production

1. Create `.env`

2. Create `wp-config.php`

3. Fill in the salt in `wp-config.php`
```sh
curl https://api.wordpress.org/secret-key/1.1/salt/
```

4. Change ownership
```sh
sudo chown www-data:www-data .
sudo chown www-data:www-data ./files -R
sudo chmod 775 .
```

5. Deploy
```sh
./deploy.sh --prod
```

6. Create admin user
```sh
docker exec -it prod_pub_server php init.php
```

7. Nginx proxy: don't forget to set Host in global nginx settings
```sh
proxy_set_header Host $http_host;
```

### For backup

1. Install pv
```sh
sudo apt update && sudo apt install pv
```

## License

MIT License