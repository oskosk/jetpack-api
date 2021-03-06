# Jetpack Api

**Jetpack API** is a PHP class covering every need for interfacing with Jetpack's internals without the need to actually have Jetpack installed side by side with your plugin for using this API.

This package is useful for including in any other WordPress plugin that can run side by side with Jetpack on the same WordPress installation but also without Jetpack being necessarily always installed on the same site.

## Installation

JetpackApi can be used just by downloading the class or via composer.

### Installation via composer

```sh
$ cd my-plugin
$ composer require oskosk/jetpack-api
```

### Installation via simple download of the class file

```sh
$ cd my-plugin
$ wget https://raw.githubusercontent.com/oskosk/jetpack-api/master/class-jetpackapi.php
```


## Usage

```php
<?php
// This is your plugin file

if ( ! class_exists( 'JetpackApi' ) ) {
	require 'class.jetpackapi.php';
}


// Attempt to disable Jetpack comments

JetpackApi::disable_jetpack_comments();

// Check if a Jetpack feature is available

if ( JetpackApi::is_feature_available( 'photon' ) ) {
	// Do stuff with photon
}

// Check if Jetpack is connected
if ( JetpackApi::is_connected() ) {
	// Use safely anything that Jetpack disabled until it's been connected
}
```


## License

GPL V2
