# -*- mode: ruby -*-
# vi: set ft=ruby :
require 'yaml'

# Load up our vagrant config files -- vagrantconfig.yml first
_config = YAML.load(File.open(File.join(File.dirname(__FILE__), "vagrant/vagrantconfig.yml"), File::RDONLY).read)
CONF = _config

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = "ubuntu/trusty64"
  config.vm.hostname = "listat.dev"
  config.vm.network "private_network", ip: CONF["ipaddress"]

  config.ssh.forward_agent = true

  config.vm.synced_folder "./", "/var/www/listat", type: "nfs", mount_options: ['rw', 'vers=3', 'tcp', 'fsc']

  config.vm.provider "virtualbox" do |vb|
    vb.customize ["modifyvm", :id, "--memory", CONF["ram"]]
    vb.customize ["modifyvm", :id, "--cpus", CONF["cpus"]]
    vb.customize ["modifyvm", :id, "--name", CONF["name"]]
  end

  config.vm.provision :shell, :path => "vagrant/scripts/install-ansible.sh", :args => "/var/www/listat/vagrant"
  config.vm.provision :shell, :path => "vagrant/scripts/run-ansible.sh", :args => "/var/www/listat/vagrant"

end
