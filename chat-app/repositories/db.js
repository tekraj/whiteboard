let mysql = require('mysql');
exports.studentTable = 'students';
exports.tutorTable = 'tutors';
exports.adminTable = 'admins';
exports.subadminTable = 'admins';
exports.messageTable = 'messages';

let connection = mysql.createConnection({
    host     : process.env.DB_HOST,
    user     : process.env.DB_USERNAME,
    password : process.env.DB_PASSWORD,
    database : process.env.DB_DATABASE
});

exports.getData = function(sqlQuery, callback) {

    connection.query(sqlQuery, (error, results) => {
        if (error) {
            console.log(error);
            return callback(false);
        }
        return callback(results);
    });

}
