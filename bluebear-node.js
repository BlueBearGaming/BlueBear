var http = require('http');
var querystring = require('querystring');
var app = http.createServer(function handler(req, res) {
    console.log('listen');
    return true;
});
var io = require('socket.io')(app);
var fs = require('fs');
var mongo = require('mongodb');
var MongoClient = mongo.MongoClient;
var assert = require('assert');

app.listen(8000, function () {
    console.log('Welcome in BlueBear, listening on *:8000');
});


var url = 'mongodb://localhost:27017/test';


MongoClient.connect(url, function(err, db) {
    assert.equal(null, err);
    insertDocument(db, function() {
        db.close();
    });
});


var insertDocument = function(db, callback) {
    db.collection('system.js').insertOne(
        {
            _id : "myAddFunction" ,
            value : new mongo.Code("(function (x, y){ return x + y; })")
        }
    );
    var test = db.collection('system.js').find();
    test.each(function(err, doc) {
        assert.equal(err, null);

        if (doc != null) {
            console.log(doc);
            console.log(doc.value.code);


            var lol = doc.value.code;
            //lol = '(function () {})';

            var condition = eval(lol);

            console.log('condition', condition);

        } else {
            callback();
        }
    });
/*
    db.collection('restaurants').insertOne( {
        "address" : {
            "street" : "2 Avenue",
            "zipcode" : "10075",
            "building" : "1480",
            "coord" : [ -73.9557413, 40.7720266 ]
        },
        "borough" : "Manhattan",
        "cuisine" : "Italian",
        "grades" : [
            {
                "date" : new Date("2014-10-01T00:00:00Z"),
                "grade" : "A",
                "score" : 11
            },
            {
                "date" : new Date("2014-01-16T00:00:00Z"),
                "grade" : "B",
                "score" : 17
            }
        ],
        "test": function () {
            var test = 'lol';
        },
        "name" : "Vella",
        "restaurant_id" : "41704620"
    }, function(err, result) {
        assert.equal(err, null);
        console.log("Inserted a document into the restaurants collection.");
        callback(result);
    });*/
};


var Client = {
    options: {
        host: 'dev.bluebear.fr',
        port: 80,
        path: '/app_dev.php/api/events/trigger/',
        method: 'POST'
    },

    send: function (eventName, data, socket) {
        data = querystring.stringify(data);

        var options = this.options;
        var postRequest = http.request({
            host: options.host,
            port: options.port,
            path: options.path + eventName,
            method: options.method,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Content-Length': Buffer.byteLength(data)
            }
        }, function (res) {
            res.setEncoding('utf8');
            res.on('data', function (content) {
                console.log('response', content);
                socket.emit('move', content);
            });
        });
        // write data to request body
        postRequest.write(data);
        postRequest.end();
    }
};

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
                //console.log('STATUS: ' + res.statusCode);
                //console.log('HEADERS: ' + JSON.stringify(res.headers));
                res.setEncoding('utf8');
                res.on('data', function (content) {
                    console.log(content);
                });
            });
            // write data to request body
            req.write('data\n');
            req.write('data\n');
            req.end();
        }
        return true;
    });
    // Fireman stuff
    socket.on('bluebear.fire.move', function () {
        Client.send('bluebear.fire.move', {}, socket);
    });


    return true;
});
