# VITS-SCM-MS

## Shared database seed data

This project can export the current database rows into seeder classes so that collaborators can seed their local DBs with the exact same data.

How it works:

1) Export seeders from your current database

- Exports one seeder per table into `database/seeders/dumps/`
- Truncates then inserts all rows in chunks when seeding

PowerShell (Windows):

```powershell
# Optional: choose specific tables
php artisan seed:export --tables=users,super_admins

# Or export everything (default excludes 'migrations')
php artisan seed:export
```

2) Commit the generated files

```powershell
git add database/seeders/dumps
git commit -m "chore(seeds): export current DB data"
git push
```

3) Teammates can migrate + seed

```powershell
php artisan migrate:fresh --seed
```

Notes

- Generated seeder files live under the namespace `Database\\Seeders\\Dumps` and are auto-discovered by `DatabaseSeeder`.
- Foreign key checks are handled per driver (MySQL/SQLite/PGSQL) during truncation/inserts.
- To exclude certain tables, use `--exclude=table1,table2`.
- If you need to re-export, simply run `php artisan seed:export` again and re-commit updated files.

## Mail configuration (password reset & profile flow)

This app sends password reset emails (including the profile “Save Changes” flow which emails a reset link to the student's plv.edu.ph address). Make sure your environment has valid mail settings in `.env`:

- MAIL_MAILER=smtp
- MAIL_HOST=your.smtp.host
- MAIL_PORT=587
- MAIL_USERNAME=your_username
- MAIL_PASSWORD=your_password
- MAIL_ENCRYPTION=tls
- MAIL_FROM_ADDRESS=noreply@yourdomain.tld
- MAIL_FROM_NAME="VITS SCM"

Tips

- Use Mailpit, Mailhog, or a real SMTP to verify emails locally.
- Ensure the user's email ends with `@plv.edu.ph`; the profile flow enforces this domain.
- If emails don't arrive, check `storage/logs/laravel.log` and your SMTP provider logs.
