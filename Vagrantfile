# -*- mode: ruby -*-
# vi: set ft=ruby :

# Copyright (c) 2015 Csaba Maulis

def local_cache(box_name)
    cache_dir = File.join(File.expand_path("~/.vagrant.d"),
                            'cache',
                            'apt',
                            box_name)
    partial_dir = File.join(cache_dir, 'partial')
    FileUtils.mkdir_p(partial_dir) unless File.exists? partial_dir
    cache_dir
end

Vagrant.configure(2) do |config|
    config.vm.box = "senki/precise"
    config.vm.hostname = "boilerplate.local"
    config.vm.network "private_network", ip:"192.168.33.13"
    config.vm.provision "shell", path: "vagrant/provision.sh"
    cache_dir = local_cache("ubuntu/precise64")
    config.vm.synced_folder cache_dir, "/var/cache/apt/archives/"
    config.vm.synced_folder "vagrant/test", "/var/www",
        id: "www-data",
        owner: "www-data",
        group: "www-data",
        mount_options: ["dmode=775,fmode=664"]
    config.vm.provider "virtualbox" do |v|
      v.name = config.vm.hostname
    end
end
