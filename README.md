# OrcasCars.com NEXT Theme 
local setup for VVV
For when you just need a simple dev site

# Configuration

### Setup/Download Varying Vagrant Vagrants
https://github.com/Varying-Vagrant-Vagrants/VVV

```bash
git clone -b master git://github.com/Varying-Vagrant-Vagrants/VVV.git vagrant-oc-next
```
Inside `vagrant-oc-next` run

```bash
cd vagrant-oc-next

vagrant up
```

This will take some ~10-15 mins and require root permissions.

### Now setup orcascars.dev locally
Inside `vagrant-oc-next` to create a OrcasCars.com dev environment, edit `vvv-custom.yml` adding `vvv-oc-next` into the `sites:`:

```yaml
  # orcascars.test
  vvv-oc-next:
    repo: https://github.com/Varying-Vagrant-Vagrants/custom-site-template.git
    hosts:
      - orcascars.test
    custom:
      wp_version: 4.9.10
      db_name: orcascar_wp
```

Should look something like:
```yaml
sites:

    # orcascars.test
  # orcascars.test
  vvv-oc-next:
    repo: https://github.com/Varying-Vagrant-Vagrants/custom-site-template.git
    hosts:
      - orcascars.test
    custom:
      wp_version: 4.9.10
      db_name: orcascar_wp
      nginx_upstream: php70

      
  # latest version of WordPress, can be used for client work and testing
  wordpress-one:
    skip_provisioning: false
    description: "A standard WP install, useful for building plugins, testing things, etc"
    repo: https://github.com/Varying-Vagrant-Vagrants/custom-site-template.git
    hosts:
      - one.wordpress.test
```

| Setting    | Value       |
|------------|-------------|
| Domain     | orcascars.test |
| Site Title | Orcas Island Car Rentals – The San Juan Islands, WA |
| DB Name    | orcascar_wp    |
| Site Type  | Single      |
| WP Versionc | 4.9.10      |

###
Now run with the `vvv-custom.yml` changes:
```bash
vagrant reload --provision
```

Your tree should look like (some files maybe added after `vagrant up`:
```bash
.
├── CHANGELOG.md
├── LICENSE
├── README.md
├── Vagrantfile
├── config
├── database
├── version
├── vvv-config.yml
├── vvv-custom.yml
├── wp-cli.yml
└── www
    ├── README.md
    ├── default
    │   ├── dashboard
    │   ├── database-admin
    │   ├── index.php
    │   ├── memcached-admin
    │   ├── opcache-status
    │   ├── phpinfo
    │   └── webgrind
    └── vvv-oc-next
        ├── bin
        ├── log
        ├── provision
        ├── public_html
        └── wp-cli.yml

```

## Now set it up with OrcasCars.com Wordpress & DB

### Theme & Plugins
Clone theme into `www/vvv-oc-next/public_html/` and place this repo there by overwriting the existing `wp-content`
```bash
cd www/vvv-oc-next/public_html/
git clone https://github.com/velvetropes/vvv-oc-next.git wp-content
```

If going to http://orcascars.test/ you'll see `Error establishing a database connection` you need to update the databases.

# Import data from
orcasislandcarrentals.wordpress.2019-05-14.xml (directions inside the file)