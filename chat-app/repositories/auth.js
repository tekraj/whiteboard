let express = require('express');
let db = require('../repositories/db');
let jwt = require('jsonwebtoken');
//let bcrypt = require('bcrypt');
exports.IsAuthenticated =  (req,callback)=>{
    let name = req.body.name;
    let userType = req.body.userType;
    let table = db.studentTable;
    if(userType=='tutor'){
        table = db.tutorTable;
    }else if(userType=='admin'){
        table = db.adminTable;
    }else if(userType=='subadmin'){
        table = db.subadminTable;
    }
    
    let query = `SELECT name as Name,uuid as ObjectID FROM ${table} WHERE name= '${name}' LIMIT 1`;

    db.getData(query,function(response){
        if(!response)
            return callback(false);
        if(response.length<1)
            return callback(false);
        let user = response[0];
        user.userType = userType;
        user.subject = req.body.subject;
        let token = jwt.sign(user, process.env.SECRET_KEY, {
            algorithm : 'HS256'
        });

        user.token = token;
        user.subject = req.body.subject;
        return callback( {
            status : 'success',
            user : user
        });
    });
};

exports.authAdmin = (data,callback)=>{
    let query = `SELECT name as Name,uuid as ObjectID FROM ${ db.subadminTable}  WHERE email= '${data.email}' LIMIT 1`;
   
    db.getData(query,function(response){
        if(!response)
            return callback(false);
        if(response.length<1)
            return callback(false);
        let user = response[0];
        // bcrypt.compare(data.password, user.password, function(err, res) {
        //    if(err){
        //        return callback(false);
        //    }

           if(res){
            user.userType = 'admin';
            user.subject = 'all';
            let token = jwt.sign(user, process.env.SECRET_KEY, {
                algorithm : 'HS256'
            });
    
            user.token = token;
            user.subject = 'all';
            return callback( {
                status : 'success',
                user : user
            });
           }
           return callback(false);
        });
        
    // });
}