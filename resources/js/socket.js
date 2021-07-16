// var Redis = require('ioredis');
// var redis = new Redis({
//     password: 'honte511'
// });
// // 訂閱 redis 的 ncu-channel 頻道，也就是我們在事件中 broadcastOn 所設定的
// redis.subscribe('ncu-channel', function(err, count) {
//     console.log('connect!');
// });
// // 當該頻道接收到訊息時就列在 terminal 上
// redis.on('message', function(channel, notification) {
//     console.log(notification);
// });

var app = require('express');
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis({
    password: 'honte511'
});
redis.subscribe('chat', function(err, count) {
    console.log('connect!');
});
redis.on('message', function(channel, notification) {
    console.log(notification);
    notification = JSON.parse(notification);
    // 將訊息推播給使用者
    io.emit('notification', notification.data.message);
});
// 監聽 3000 port
http.listen(3000, function() {
    console.log('Listening on Port 3000');
});
