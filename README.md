# Artsofte test task

# About
This is an implementation of a test task.

See [Task.docx](./Task.docx) (in Russian).

## How to run
    git clone https://github.com/f1amy/artsofte-test-task.git
    cd artsofte-test-task
    composer create-project
    ./vendor/bin/sail up -d
    ./vendor/bin/sail artisan migrate --seed

## Test
See [api-tests.http](./api-tests.http) for simple API tests (open in PhpStorm).
