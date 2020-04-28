FROM node:12
WORKDIR /app

COPY package*.json ./


RUN npm install
ENV NODE_PATH=/app/node_modules
ENV PATH /app/node_modules/.bin:$PATH
# If it still says react-scripts undefined!
# RUN npm install react-scripts@3.4.1 -g

COPY . .
