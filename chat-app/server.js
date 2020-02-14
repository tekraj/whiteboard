let express = require('express');
require('dotenv').config();
let path = require('path');
let app = express();
let http = require('http').Server(app);
let bodyParser = require('body-parser');
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));
global.clients = {};


let io = require('socket.io')(http,{ origins: '*:*'});
io.set('transports',['websocket']);
let chat = require('./socket/chat')(io);
let route = require('./routes/route');
app.use('/', route);

let port =process.env.PORT || 8000;
http.listen(port);
