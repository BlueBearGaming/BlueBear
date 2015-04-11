

var app = require('http').createServer(handler);
var io = require('socket.io')(app);
var fs = require('fs');

app.listen(8000);

function handler (req, res) {
    console.log('listen');

    return 'lol';
}


io.on('connection', function (socket) {
    console.log('listen');
    socket.emit('lol', { hello: 'world' });
    socket.on('lol', function (data) {
        console.log(data);
        return 'lol';
    });
    return 'lol';
});
