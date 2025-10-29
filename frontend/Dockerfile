dockerfile
FROM node:18-alpine

WORKDIR /app

COPY package*.json ./
RUN npm install

# Копируем ВЕСЬ Nuxt проект (без папки src)
COPY . .

EXPOSE 3000

CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]