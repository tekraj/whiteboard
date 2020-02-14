// const  wkhtmltox = require("wkhtmltox");
// let converter = new wkhtmltox();
// const fs = require('fs');
// exports.convertHtmlToImage =(html,callback)=>{
//     converter.image(html, { format: 'jpg' })
//     .pipe(fs.createWriteStream("foo.png"))
//     .on("finish", (response)=>{
//         console.log(response);
//     });
// }