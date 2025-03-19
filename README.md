## Clone a repository

    git clone git@github.com:DanHariton/api-projects.git

---

## Docker

In project folder in terminal run:
    
    docker-compose up -d --build

---

## Environmental variables

Enter inside PHP container

* `docker exec -it twist_admin_php /bin/bash`

Then copy the `.env` to `.env.local`

* `cp .env .env.local`

Edit the `.env.local` file and populate it with all necessary variables:
Set your email and password for your local admin user in .env.local file.

---

#### Init project
For the first time you can use `int` command, which will do all first steps

#### Fixtures
You can load fixtures with the following command.  
You have to use the `--append` option to avoid deleting all data from the database.

    bin/console doctrine:fixtures:load --append

or just use alias `fxt`

    fxt

---

## JWT Authentication

You will need to set your own `JWT_PASSPHRASE`, for example, a random string like `5806ad40b3874b6c2836`.
Based on this passphrase, you can generate a new key pair by running:

```bash
php bin/console lexik:jwt:generate-keypair
```