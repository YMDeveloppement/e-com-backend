# ========================================
# Build React
# ========================================

FROM node:22-alpine AS build

WORKDIR /app

COPY package*.json ./

RUN npm ci

COPY . .

RUN npm run build


# ========================================
# Serve React with Nginx
# ========================================

FROM nginx:alpine

COPY --from=build /app/dist /usr/share/nginx/html

COPY nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
# //can be removed 
CMD ["nginx", "-g", "daemon off;"] 