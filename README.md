# Base Vagrant Box

Ubuntu LAMP vagrant Box.  
Tailored for my needs.

> Please aware that, the `senki/precise` box not available online (yet).  
> But you can generate yourself with [senki/vagrant-boxes](https://github.com/senki/vagrant-boxes).

## Features

- Inherited features from [senki/vagrant-boxes](https://github.com/senki/vagrant-boxes)
- Private network ip: `192.168.33.13`
- Backup `/var/lib/mysql/` dir on shutdown to `vagrant/db/mysql-$NOW.tar.gz`
- Provision logs:
  - guest: `/var/log/project-provision.log`
  - host: `vagrant/log/$HOST_NAME-$NOW.log`
- `provision.sh` feature: remove old (more than 7 days) logs and db backup files

## Install

```sh
$ git clone --depth=1 https://github.com/senki/base-lamp-box.git
```
For customize, edit the `Vagrantfile` and the `vagrant/provision.sh` files.  
Especially the `hostname` and `synced_folder` settings.

## Copyright and license

Code and documentation Copyright 2015 Csaba Maulis. Released under [the MIT license](LICENSE).
