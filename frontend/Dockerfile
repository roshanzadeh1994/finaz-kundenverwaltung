
FROM node:20-alpine as build-stage

WORKDIR /app
COPY . .
RUN npm install
RUN npm run build


FROM nginx:stable-alpine as production-stage

COPY --from=build-stage /app/dist/spa /usr/share/nginx/html


COPY nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
