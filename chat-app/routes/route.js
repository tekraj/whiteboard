const express = require('express');
const auth = require('../repositories/auth');
const path = require("path");
const model = require('../repositories/model');
const router = express.Router();
const utility = require('../repositories/utility');

    router.use((req, res, next) => {
        res.setHeader('Access-Control-Allow-Origin', '*');
        res.setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept,X-CSRF-TOKEN');
        res.setHeader('Access-Control-Allow-Methods', 'POST, GET');
        next();
    });

    router.get('/', (req, res) => {
        res.send('It works');
    });

    router.post('/authenticate', (req, res) => {
        res.send({status: 'error'});
        // auth.IsAuthenticated(req, (user) => {
        //     console.log(user);
        //     if (user !== false)
        //         res.send(user);
        //     else
        //         res.send({status: 'error'});
        // });


    });

    router.post('/get-user-messages', (req, res) => {
        model.getChattingMessages(req.body.fromUser, req.body.toUser, req.body.userType, (messages) => {
            if (messages) {
                res.send({status: true, messages: messages});
            } else {
                res.send({status: false});
            }

        });
    });

    router.post('/get-users', (req, res) => {
        model.getUsers(req.body.type, (users) => {
            res.send(users);
        });
    });
    router.post('/check-public-drawing', (req, res) => {
        let user = req.body;
        let status = false;
        if(global.clients.hasOwnProperty(user.tutor)){
             status =  global.clients[user.tutor].public;
        }

        res.send({status:status});

    });

    router.post('/set-public-drawing', (req, res) => {
        let user = req.body;
        let status = false;
        if(global.clients.hasOwnProperty(user.socket)){
            global.clients[user.socket].public = true;
            status = true;
        }

        res.send({status:status})
    });

    router.post('/unset-public-drawing', (req,res)=>{
        let user = req.body;
        if(global.clients.hasOwnProperty(user.socket)) {
            global.clients[user.socket].public = false;
        }
        res.send({status:true})

    });

    router.post('/admin-auth', (req,res)=>{
        let data = req.body;
      
        auth.authAdmin(data, (user) => {
            if (user !== false)
                res.send(user);
            else
                res.send({status: 'error'});
        });
    });
    router.post('/conert-html-to-image',(req,res)=>{
        let html = req.body.html;
        utility.convertHtmlToImage(html);
        res.send(true);
        
    })

module.exports= router;


