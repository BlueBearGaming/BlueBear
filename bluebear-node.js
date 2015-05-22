var app = require('http').createServer(handler);
var io = require('socket.io')(app);
var fs = require('fs');

app.listen(8000, function () {
    console.log('Welcome in BlueBear, listening on *:3000');
});

function handler(req, res) {
    console.log('listen');
    return true;
}


io.on('connection', function (socket) {
    //console.log('Connection initiated : ', socket.client);
    socket.on('bluebear.engine.clientUpdate', function (data) {
        //console.log('Data received');
        io.emit('bluebear.engine.clientUpdate', data);
        return true;
    });
    return true;
});
