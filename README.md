## Project details
USPS xml API is not really a pleasure to use (to live without rest api in 2023 is a shame). 
Because of that I checked existing packages wrapping USPS to save some time. All of them are quite old 
and poorly implemented, but still better than nothing. In real-world project I would more likely
wrap API myself using Guzzle, or even check for another modern address validation service.

## Setup guide
1. Before setup: make sure your [homestead](https://laravel.com/docs/9.x/homestead) vm is installed and ready for use
2. Put the source code into new folder named address-collector.local inside [folder shared with Homestead](https://laravel.com/docs/9.x/homestead#configuring-shared-folders)
3. In Homestead.yaml add [site](https://laravel.com/docs/9.x/homestead#configuring-nginx-sites) and database(s) related to this code
4. Add host name resolution for address-collector.local in your hosts file: `192.168.56.56    address-collector.local`
5. Run and provision your homestead vm: `vagrant up --provision`
6. Connect to your vm: `vagrant ssh`
7. Cd into code/address-collector.local (this project root)
8. Run `composer install`
9. Copy .env.example into .env and provide your db connection configurations
10. Run `art migrate:fresh`
11. Open http://address-collector.local/ in your browser
