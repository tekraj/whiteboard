# Development Setup: With Docker

> Make sure you have docker and docker-composer installed.
> Docker compose setup guide: https://docs.docker.com/compose/install/

> Make sure **`docker-compose --version`** is above 1.25.4.<br>
> And **`docker --version`** is above 19.03.8.


Execute the following commands in root directory of the project to copy the example.env file to .env file: in root directory, in server and in client.
You may do it manually as well. Fill up the missing values or ask a team member for his .env files.


```
cp .env.example .env
cp server/.env.example server/.env
cp client/.env.example client/.env
```

## Start the project
```
docker-compose up
```

Let the process finish, and visit [http://localhost:2222](https://localhost:2222) for the client.

Server URL is proxied to client and is available at [http://localhost:2222/api](https://localhost:2222/api).<br>
The original server URL can be visited at [http://localhost:2223/api](https://localhost:2223/api)

Use [JSON Viewer](https://chrome.google.com/webstore/detail/json-viewer/gbmdgpbipfallnflgajpaliibnhdgobh), a chrome extension, to beautify JSON in the browser.

### Messed Up?
If you mess up something, example: not having database variables in the .env file in root directory will build the image with default credentials but the server will try with the provided credentials. Server won't be able to connect to database.

To start over, fix the errors (example: make sure all the required variables are in the .env file) and re-build the image. **`docker-compose up` will use the existing image**.

To rebuild, run `docker-compose build --no-cache`
Then run `docker-compose up`


### Known issues:

1. If you don't run `npm install` for **server** and **client** yourself, *node_modules* will be owned by the root user.
You can run the install (for both server and client) beforehand to get around.<br>
If already installed via docker-compose, run the following from root directory to fix the permission issue.
```
sudo chown -hR $USER:$USER ./server/node_modules & sudo chown -hR $USER:$USER ./client/node_modules
```

1. Adding a npm package via `npm install` needs container to be rebuilt.<br> Run `docker-compose build --no-cache`


# Production Setup: With Docker

TODO: Set up the Continuous Deployment to server with webhooks
