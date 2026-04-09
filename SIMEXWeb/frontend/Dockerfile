# frontend/Dockerfile

# ---- Build stage ----
FROM node:20-alpine AS build

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .

# VITE_LARAVEL_API se inyecta en build-time
ARG VITE_LARAVEL_API=http://localhost:8000/api
ENV VITE_LARAVEL_API=$VITE_LARAVEL_API

RUN npm run build

# ---- Serve stage ----
FROM nginx:alpine

COPY --from=build /app/dist /usr/share/nginx/html
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
