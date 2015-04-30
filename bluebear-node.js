

var app = require('http').createServer(handler);
var io = require('socket.io')(app);
var fs = require('fs');

app.listen(8000);

function handler (req, res) {
    console.log('listen');
    return true;
}


io.on('connection', function (socket) {
    socket.on('bluebear.engine.clientUpdate', function (data) {
        console.log(data);
        return true;
    });
    return true;
});
