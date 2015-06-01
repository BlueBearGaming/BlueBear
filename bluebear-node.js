var http = require('http');
var app = http.createServer(handler);
var io = require('socket.io')(app);
var fs = require('fs');

app.listen(8000, function () {
    console.log('Welcome in BlueBear, listening on *:8000');
});

function handler(req, res) {
    console.log('listen');
    return true;
}

io.on('connection', function (socket) {
    var waitingRoom = [];
    socket.emit('message', {message: 'Welcome dude'});

    console.log('Client connected');
    console.log('Connection initiated : ', socket.client.conn.id);
    socket.on('bluebear.engine.clientUpdate', function (data) {
        //console.log('Data received');
        io.emit('bluebear.engine.clientUpdate', data);
        return true;
    });
    socket.on('bluebear.chess.findGame', function () {
        console.log('Finding games...');

        if (waitingRoom.length) {

        } else {
            console.log('No game found, creating one...');

            var options = {
                host: 'dev.bluebear.fr',
                port: 80,
                path: '/app_dev.php/api/events/trigger/bluebear.engine.gameCreate',
                method: 'POST'
            };
            var req = http.request(options, function (res) {
                console.log('STATUS: ' + res.statusCode);
                console.log('HEADERS: ' + JSON.stringify(res.headers));
                res.setEncoding('utf8');
                res.on('data', function (chunk) {
                    console.log('BODY: ' + chunk);
                });
            });
            // write data to request body
            req.write('data\n');
            req.write('data\n');
            req.end();
        }
        return true;
    });


    return true;
});
