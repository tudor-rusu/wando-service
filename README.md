Wando Service
======================================

[![Conventional Commits][conventional-commits-image]][conventional-commits-url]

The scope of the project is about to highlight Oriented Object Programming concepts and S.O.L.I.D principles by
 building a small service.

## About the project
#### Project stack  
* PHP 7.2
* nginx alpine
* Composer
* Phpunit

#### Design patterns
* Singleton
* Front Controller
* Fluent Interface
* Dependency Injection
* Strategy
* Delegation

#### Overview and Project requirements  
 
Wando runs an internet site for product search and price comparison. It helps users to find the best deals for a
 particular item, by searching through different product feeds from various providers (e.g. ebay, amazon, etc.).   
 
 Managing an almost infinite number of products has a lot of challenges, in this exercise we will focus only on
  the backend side and create a small service, which connects the ebay product feed and provides an interface to get product data.

## Install and run on local machine

### Prerequisites
Before start install, user will need:
* One [Bionic Beaver][1], and a non-root user with `sudo` privileges.
* [Composer][2]
* [Docker][3]
* [Docker Compose][4]

### Install local and run
* Clone repository
```shell script
git clone https://github.com/tudor-rusu/wando-service.git ${PROJECT_ROOT}
```
* Copy and adjust environmental settings in the root of the project, assumed `${PROJECT_ROOT}`:
```shell script
cd ${PROJECT_ROOT}
cp .env.dist .env
```
* Run the `sh` script which deploy Docker environment
```shell script
./run.sh
```

* Enjoy!

[conventional-commits-image]: https://img.shields.io/badge/Conventional%20Commits-1.0.0-yellow.svg
[conventional-commits-url]: https://conventionalcommits.org/
[1]: http://releases.ubuntu.com/18.04.4/
[2]: https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-18-04
[3]: https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-18-04
[4]: https://www.digitalocean.com/community/tutorials/how-to-install-docker-compose-on-ubuntu-18-04
