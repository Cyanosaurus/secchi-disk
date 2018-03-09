const express = require('express');
var app = express();
var server = require('http').Server(app);

server.listen(8080);

app.use(express.static('public'));

app.get('/', function (req, res) {
  res.sendFile(__dirname + '/index.html');
});
