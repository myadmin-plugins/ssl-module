# MyAdmin SSL Certificates Module

[![Tests](https://github.com/detain/myadmin-ssl-module/actions/workflows/tests.yml/badge.svg)](https://github.com/detain/myadmin-ssl-module/actions/workflows/tests.yml)
[![License: LGPL-2.1](https://img.shields.io/badge/License-LGPL%202.1-blue.svg)](https://opensource.org/licenses/LGPL-2.1)
[![Latest Stable Version](https://poser.pugx.org/detain/myadmin-ssl-module/version)](https://packagist.org/packages/detain/myadmin-ssl-module)

SSL Certificates module for the [MyAdmin](https://github.com/detain/myadmin) control panel. Provides provisioning, activation, reactivation, and lifecycle management of SSL certificate services with automated admin notifications and billing integration.

## Features

- Full service lifecycle support: enable, reactivate, disable
- Automated admin email notifications for certificate creation and reactivation
- Symfony EventDispatcher integration for hook-based architecture
- Configurable billing, suspension, and deletion policies
- Out-of-stock toggle for sales control

## Requirements

- PHP >= 5.0
- ext-soap
- Symfony EventDispatcher ^5.0
- MyAdmin Plugin Installer

## Installation

```bash
composer require detain/myadmin-ssl-module
```

## Testing

```bash
composer install
vendor/bin/phpunit
```

## License

This package is licensed under the [LGPL-2.1](LICENSE).
