# CI/CD guide for Moto Service

CI/CD automates two different jobs:

| Term | Meaning here |
| --- | --- |
| CI (Continuous Integration) | Every change is formatted and tested before it reaches `main`. |
| CD (Continuous Deployment) | A reviewed version is sent to the production server. |

## What was added

`/.github/workflows/ci.yml` is the CI workflow. GitHub runs it for every pull request to `main` and every push to `main`.

It does this, in order:

1. `actions/checkout` downloads your repository into a temporary GitHub computer (the **runner**).
2. `setup-php` installs PHP 8.3 and the SQLite extension. Your application requires PHP 8.3; your local PHP is currently 8.2, so GitHub's runner gives us the correct version.
3. The Composer cache reuses downloaded packages when `composer.lock` has not changed. It makes later runs faster.
4. `composer install` installs exactly the versions saved in `composer.lock`.
5. `./vendor/bin/pint --test` checks Laravel code style without changing files. If it fails locally, run `./vendor/bin/pint` to fix formatting, then commit the changes.
6. `php artisan test` runs the automated tests. They use an in-memory SQLite database, so CI never touches production or your Docker MySQL database.

`/.github/workflows/deploy.yml` is a **manual CD template**. It does not run automatically and is disabled by default. Deploying needs a real server/host and credentials, which must never be put in this repository.

## First push to GitHub

The folder is not a Git repository yet. In the terminal, from this project folder, run:

```bash
git init
git add .
git commit -m "Add CI/CD with GitHub Actions"
git branch -M main
git remote add origin https://github.com/hlaingminkyaw/motocycle.git
git push -u origin main
```

If GitHub says the remote already has files (for example a README), run `git pull origin main --allow-unrelated-histories`, resolve any conflict, commit, and push again. Do not use `git push --force`.

After pushing, open the repository's **Actions** tab, choose **CI**, and open the latest run. A green check means the pipeline passed. A red X means one of the named steps failed; click that step to see its output.

## Practice workflow

1. Create a branch: `git switch -c learn/ci-practice`.
2. Make a small change, commit it, and push it: `git push -u origin learn/ci-practice`.
3. On GitHub, open a pull request from that branch into `main`.
4. Watch CI run. Fix anything it reports, commit again, and see a new run.
5. Merge only after the check is green. In repository **Settings → Branches**, you can later require this CI check before merging into `main`.

## Enable deployment later (a Docker server)

Only do this once you have a Linux server with Docker Compose, a clone of this repository at a fixed path, and a working `.env` on that server.

1. In GitHub, make a **production** environment: **Settings → Environments → New environment**. Add required reviewers if you want approval before every deploy.
2. Add these **environment secrets**: `DEPLOY_HOST`, `DEPLOY_USER`, and `DEPLOY_SSH_PRIVATE_KEY`. The SSH key must be a deployment-only private key; put its public key in the server user's `~/.ssh/authorized_keys`.
3. Add environment variable `DEPLOY_PATH`, for example `/var/www/motocycle`.
4. Add environment variable `DEPLOY_ENABLED` with value `true`.
5. In **Actions → Deploy → Run workflow**, choose `main` (or preferably an immutable tag) and run it. GitHub will wait for any environment approval, connect to the server, update the code, rebuild containers, run migrations, and refresh Laravel caches.

The template deliberately has no database password, `.env`, or SSH key in source control. Keep the production `.env` only on the server and back up the database before schema-changing migrations.

## Useful commands

```bash
# Format code locally (run inside a PHP 8.3 environment)
./vendor/bin/pint

# Run the same tests as CI
php artisan test

# Check current Git state
git status
```

Your local command line currently uses PHP 8.2, while this app requires PHP 8.3. Run these Laravel commands inside your Docker `app` service or switch your local PHP to 8.3.
