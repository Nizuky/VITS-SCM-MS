# SCMS — Docker & CI

This repository includes Docker configurations and a GitHub Actions CI workflow to build frontend assets, install PHP dependencies, build Docker images and push them to a registry.

Files added
- `Dockerfile` (multi-stage php-fpm image) — original multi-stage php-fpm image.
- `docker/Dockerfile.prod` — single-container production image with Nginx + PHP-FPM (supervisord).
- `docker/nginx/prod.conf` — production Nginx config with gzip, security headers and caching.
- `docker/nginx/default.conf` — development nginx config (also updated with caching & headers).
- `docker-compose.yml` / `docker-compose.dev.yml` — compose stacks for production/dev.
- `.github/workflows/ci-docker.yml` — GitHub Actions workflow that runs composer/npm build and pushes Docker image.
- `.env.example` — example environment variables file.

Quick start — Development (with docker-compose.dev.yml)

1. Copy `.env.example` to `.env` and set values (especially `APP_KEY` and DB settings).
2. Start dev stack (this runs a Node-based Vite dev service and nginx on `8080`):

```powershell
docker compose -f docker-compose.dev.yml up --build
```

Open `http://localhost:8080` to view the app. Vite HMR is available at `http://localhost:5173`.

Quick start — Production (single-container)

1. Build the production single-container image (uses `docker/Dockerfile.prod`):

```powershell
docker build -f docker/Dockerfile.prod -t youruser/scms:latest .
docker run -d --name scms -p 80:80 --env-file .env youruser/scms:latest
```

Replace `youruser/scms` with your registry/repository.

CI (GitHub Actions)

The workflow `ci-docker.yml` is configured to run on push to `main`. It expects the following repository secrets:

- `DOCKER_REGISTRY` (e.g. `docker.io`)
- `DOCKER_USERNAME` (Docker Hub username or registry user)
- `DOCKER_PASSWORD` (Docker Hub password/token)
- `DOCKER_REPOSITORY` (repository name, e.g. `youruser/scms`)

The workflow installs composer & node deps, builds frontend assets, and uses `docker/build-push-action` to build and push `docker/Dockerfile.prod`.

Notes & recommendations
- Do not commit `.env` to the repo. Use secrets or environment variables in CI and production.
- For smaller images consider using multi-stage builds and removing build-only dependencies.
- If you prefer Kubernetes deployments or GHCR, update the workflow to push to your target registry and adjust permissions.

Tailwind CDN fallback
- The Blade layout now falls back to the official Tailwind CDN when Vite-built assets are missing (useful if a build step fails during deploy). This keeps basic Tailwind utilities working in production even if `public/build/manifest.json` is absent.
- Note: plugins that run at build-time (for example `daisyui` custom themes) may not be available via the CDN fallback. For full feature parity, ensure `npm run build` completes successfully during your CI pipeline so `@vite` assets are used.
