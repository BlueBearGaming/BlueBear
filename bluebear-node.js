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

var Game = function () {
    this.numberOfPlayers = 0;
    this.mapName = '';
};

io.on('connection', function (socket) {
    var waitingRoom = [];

    console.log('Client connected');
    console.log('Connection initiated : ', socket.client.conn.id);
    socket.on('bluebear.engine.clientUpdate', function (data) {
        //console.log('Data received');
        io.emit('bluebear.engine.clientUpdate', data);
        return true;
    });
    socket.on('bluebear.engine.joinGame', function (data) {


        if (waitingRoom.length) {

        } else {
            // create a new Game for players
            var game = new Game();
            game.numberOfPlayers = data.numberOfPlayers;
            game.mapName = data.mapName;
        }
        console.log('join ?', data);
        return true;
    });


    return true;
});
