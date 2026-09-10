# CI/CD learning guide: Moto Service

This guide explains the CI setup for **this Laravel motorcycle-service project**. It uses the same commands and files found in this repository, not a generic JavaScript example.
docs/CI-CD-LEARNING.md
## Project audit

| Question | What this project uses |
| --- | --- |
| Language and framework | PHP 8.3 and Laravel 12 |
| Package manager | Composer (`composer.json` and `composer.lock`) |
| Install dependencies | `composer install` |
| Linting / formatting | Laravel Pint: `./vendor/bin/pint --test` checks formatting without editing files |
| Tests | PHPUnit, started by Laravel with `php artisan test` |
| Application build | `docker compose build app`, using `Dockerfile` to create a PHP-FPM image |
| Frontend build | None. There is no `package.json`, Vite, npm, or compiled frontend asset workflow. The CSS is a committed plain CSS file. |
| Environment variables | Yes for normal use: `.env.example` lists `APP_*`, database, cache, session, mail, and Redis settings. Tests override these with safe test values in `phpunit.xml`, including an in-memory SQLite database. |
| Deployment configuration | Docker Compose describes local services (app, Nginx, MySQL, phpMyAdmin), but it does not name a production server, cloud provider, domain, registry, or deployment credentials. Therefore no CD deployment has been added. |

## CI and CD in plain language

**CI** means *Continuous Integration*. Developers regularly combine their work in a shared branch, and an automated system checks every change. Here, CI checks PHP formatting, runs PHPUnit tests, and builds the Docker image.

**CD** can mean *Continuous Delivery* or *Continuous Deployment*. Delivery means the checked application is ready for a human to deploy. Deployment means the system actually releases it automatically. We only have CI today because a real destination for releases has not been chosen.

Developers use CI/CD to discover problems early, avoid broken code reaching `main`, make checks consistent for everyone, and make releases repeatable instead of relying on memory.

## GitHub Actions vocabulary

GitHub Actions is GitHub's automation service. It reads workflow files and rents a temporary computer to run the instructions.

A **workflow** is one automation recipe. GitHub only recognizes workflow recipes in `.github/workflows/`; that location is a GitHub convention. This project's recipe is `.github/workflows/ci.yml`.

YAML is a human-readable configuration language. Its indentation shows structure, so use spaces consistently and never use tabs.

| YAML word | Meaning in our workflow |
| --- | --- |
| `name` | A friendly label shown in the Actions page, such as `CI`. |
| `on` | The event that starts the workflow. |
| `push` | Starts CI when commits are pushed to `main`. |
| `pull_request` | Starts CI when a pull request targets `main`, before it is merged. |
| `jobs` | Separate groups of work. This workflow has one job: `quality`. Jobs can run independently if there are several. |
| `runs-on` | Selects the operating system image for a job: `ubuntu-latest`. |
| runner | The temporary GitHub-hosted Ubuntu computer that performs the job. It is discarded afterward. |
| `steps` | Ordered actions or commands inside a job. A later step does not run if an earlier step fails. |
| `uses` | Runs a reusable action published by GitHub or the community. |
| `run` | Runs a shell command directly on the runner. |

## Our `ci.yml`, line by line

```yaml
name: CI                         # Label shown in GitHub's Actions page

on:                              # Events that start the workflow
  push:
    branches: [main]             # A push directly to main
  pull_request:
    branches: [main]             # A PR whose destination is main

permissions:
  contents: read                 # Least privilege: it may read code, not change it

jobs:
  quality:                       # Internal ID for this one job
    name: Format and test (PHP 8.3) # Friendly job label
    runs-on: ubuntu-latest       # Start a temporary Ubuntu runner

    steps:
      - uses: actions/checkout@v4 # Download this commit onto the runner

      - uses: shivammathur/setup-php@v2 # Install the required PHP runtime
        with:
          php-version: '8.3'     # Matches composer.json and Dockerfile
          extensions: mbstring, xml, pdo_sqlite # Needed by Laravel/tests
          coverage: none          # Do not spend time generating coverage

      - uses: actions/cache@v4   # Reuse downloaded Composer packages when safe
        with:
          path: ~/.cache/composer/files
          key: composer-${{ runner.os }}-${{ hashFiles('composer.lock') }}
          restore-keys: composer-${{ runner.os }}-

      - run: composer install --no-interaction --prefer-dist --optimize-autoloader
                                    # Install versions locked in composer.lock
      - run: ./vendor/bin/pint --test # Check formatting; does not modify files
      - run: php artisan test         # Run this project's PHPUnit tests
      - run: docker compose build app # Build the real Docker application image
```

`actions/checkout` is especially important: a fresh runner starts empty. This official action downloads the commit that triggered the run. `setup-php` then gives the runner PHP 8.3 because this project requires it; it does not use your local PHP version.

The Composer cache is optional for correctness. `composer.lock` is part of its cache key, so a dependency change gets a different cache. `composer install` remains the source of truth and always creates the usable `vendor/` directory.

The final Docker build is the appropriate build step here. It validates the existing `Dockerfile`, and the Dockerfile now installs production Composer dependencies within the image. The image does not depend on an uncommitted local `vendor/` directory.

## What happens after `git push`

```text
Developer
    |
    v
git push / open pull request
    |
    v
GitHub receives the commit
    |
    v
GitHub Actions starts an Ubuntu runner
    |
    v
Checkout -> PHP setup -> Composer install -> Pint -> Tests -> Docker build
    |                                                    |
    +------------------------ failure ------------------+--> stop and report failure
    |
    +------------------------ success ------------------+--> ready to merge / deliver
```

If CI passes, GitHub puts a green check beside the commit or pull request. The code is not automatically deployed. You can merge it when the review is also ready.

If CI fails, GitHub marks the exact step red and stops the remaining steps. That is useful feedback, not a disaster: open the failed run, open the failed step, read the command output from the bottom upward, reproduce the command locally, fix the cause, commit, and push again. Every push creates a fresh CI run.

To read logs: open your repository on GitHub, select **Actions**, choose the **CI** workflow and then a run. Click the `Format and test (PHP 8.3)` job, then expand the red step. Copy the first meaningful error rather than only the final summary line.

For formatting failures, run `./vendor/bin/pint` in PHP 8.3 to make the safe formatting changes, then commit them. For test failures, run `php artisan test` and fix either the application or the test. This project can run both commands in the Docker app container if your machine's PHP is not 8.3:

```bash
docker compose exec app ./vendor/bin/pint
docker compose exec app php artisan test
```

## Protecting `main`

After the first successful GitHub run, configure a branch protection/ruleset for `main` in repository **Settings**. Require the `Format and test (PHP 8.3)` status check before merging pull requests. Then a pull request with a failed CI run cannot be merged until it is fixed (subject to your repository's administrator rules).

## Adding CD later

Before we implement CD, you need to choose and provide:

1. A deployment target: for example, a Linux VPS, Laravel Forge, AWS, Render, or another provider.
2. The target's domain/server address and whether Docker Compose is used there.
3. A secure way to authenticate from GitHub: provider token, GitHub OIDC, or a restricted SSH deployment key.
4. Where production environment variables and database credentials will live (GitHub environment secrets and/or the server—never committed in `.env`).
5. A database migration and backup/rollback plan.
6. Whether releases should be automatic after `main`, manual with approval, or tag-based.

The suggested next lesson is **manual Continuous Delivery to a test/staging server**: create a GitHub `staging` environment, learn encrypted secrets and approval gates, deploy a tagged Docker image, run migrations safely, then add production only after staging is reliable.
