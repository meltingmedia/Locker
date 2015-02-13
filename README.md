# Locker

An helper to lock users out of MODX Revolution manager.

Locker was PoC'ed at [MODXCCC2015](https://github.com/modx-ccc-2015), as a first component to handle (automatic) MODX revolution upgrade.


## Goals

Prevent login into the manager when planning some maintenance (backup, migration...).
Only "sudo" users or users with the `use_in_maintenance_mode` permission (you would have to manually create it) would be allowed to log in the manager when being "locked".


## Requirements

* PHP 5.3+
* MODX Revolution 2.3+


## Installation

1. Download and install the transport package
2. Head over system settings to tweak to your needs

You should be ready to go!


## Documentation

<https://docs.melting-media.com/locker/>


## Bug reports

Head over <https://github.com/meltingmedia/Locker/issues>


## License

Locker is licensed under the [MIT license](LICENSE.md).
Copyright 2015 Melting Media <https://github.com/meltingmedia>
