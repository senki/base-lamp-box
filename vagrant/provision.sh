#!/usr/bin/env bash

# Copyright (c) 2015 Csaba Maulis
#
# The MIT License (MIT)

# To re-run full provisioning, delete /var/provision/* files and run
#  $ vagrant provision
# From the host machine

set -e

NOW=$(date +"%Y-%m-%d-%H-%M-%S")
HOST_NAME="boilerplate.local"
PROVISION_LOG="/var/log/project-provision.log"

do_update() {
    if [[ -f "/var/provision/update" ]] && [[ `stat --format=%Y /var/provision/update` -ge $(( `date +%s` - (60*60*24) )) ]]; then
        echo "Skipping: System already updated within a day" | tee -a $PROVISION_LOG
        return
    fi
    echo "Updating System..."  | tee -a $PROVISION_LOG
    apt-get -qy update >> $PROVISION_LOG 2>&1
    apt-get -qy dist-upgrade >> $PROVISION_LOG 2>&1
    apt-get -qy autoremove >> $PROVISION_LOG 2>&1
    touch /var/provision/update
}

do_config_network() {
    if [[ -f "/var/provision/config-network" ]]; then
        echo "Skipping: Hostname already confugured" | tee -a $PROVISION_LOG
        return
    fi
    echo "Configuring hostname..."  | tee -a $PROVISION_LOG
    IPADDR=$(/sbin/ifconfig eth1 | awk '/inet / { print $2 }' | sed 's/addr://')
    sed -i "s/^${IPADDR}.*//" /etc/hosts
    echo ${IPADDR} ${HOST_NAME} >> /etc/hosts           # Just to quiet down some error messages
    touch /var/provision/config-network
}

main() {
    if [[ ! -f $PROVISION_LOG ]]; then
        touch $PROVISION_LOG
    fi
    echo "==> Project provisioning start at: $(date)" >> $PROVISION_LOG 2>&1
    if [[ ! -d "/var/provision" ]]; then
        mkdir /var/provision
    fi
    echo -n "==> " >> $PROVISION_LOG 2>&1
    do_update
    echo -n "==> " >> $PROVISION_LOG 2>&1
    do_config_network
    echo -n "==> " >> $PROVISION_LOG 2>&1
    do_mysql
    updatedb >> $PROVISION_LOG 2>&1
    echo "All done"
    echo "==> Project provisioning done at: $(date)" >> $PROVISION_LOG 2>&1
    cp $PROVISION_LOG /vagrant/vagrant/log/$HOST_NAME-$NOW.log
}

# Script start here
main
