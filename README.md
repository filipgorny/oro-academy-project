Issues - bug reporting - academical project based on Oro Platform
=================================================================

This is a training project, made in purpose of studying [Oro Platform](https://github.com/orocrm).

## Installation

To view installation process, visit [Installation](docs/installation.md) page.

## Usage

Usage instruction and list of featers is available under [features and usage](docs/features_and_usage.md) page.

## Testing

Tests are based on PHPUnit. The phpunit.xml file is located under the `app` directory.

In order to run functional tests, the test environment installation must be performed first, according to this link:
[https://www.orocrm.com/documentation/index/current/book/functional-tests](https://www.orocrm.com/documentation/index/current/book/functional-tests)

Basically this two commands are required to execute before testing:
```
php app/console oro:install --env test --organization-name Oro --user-name admin --user-email admin@example.com --user-firstname John --user-lastname Doe --user-password admin --sample-data n --application-url http://localhost --force
php app/console doctrine:fixture:load --no-debug --append --no-interaction --env=test --fixtures ./vendor/oro/platform/src/Oro/Bundle/TestFrameworkBundle/Fixtures
```

## Further development
There are number of improvements that may be added to the project.
For more details please see the link below.

[List of improvements that may be added](docs/improvements.md)

## Known issues
Some issues were spotted due the development and are awaiting to be solved in consultation with trainer.

[List of issues](docs/issues.md)

## Ideas and feedback
Some feedback and ideas about Oro Bundles are listed under [ideas page](docs/ideas_for_oro.md)
They are meant to be read by a trainer and treated only as a this repository's author personal point of view.