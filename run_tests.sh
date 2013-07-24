#!/bin/bash

rm -fr tests/reports/coverage/* && \
vendor/bin/phpunit --coverage-html tests/reports/coverage -c tests/phpunit.xml.dist tests
