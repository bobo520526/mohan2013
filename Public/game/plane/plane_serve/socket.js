/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2017-06-03 23:12:17
 * @version $Id$
 */
/**
Hall:{大厅
	roomID1:{房间
		users:{用户集合
			roomsOwner:{房主
				nickName:msg.nickName,用户名
				Avatar:msg.Avatar,用户头像
				roomID,用户所在房间id
				userID:socket.id,用户id
				status:"readying",用户状态
				Identity:"roomsOwner"用户身份
			},
			roomer : {房客
				nickName:msg.nickName,
				Avatar:msg.Avatar,
				userID:socket.id,
				status:"readying",
				Identity:"roomer"
			}
		},
		status: "normal",房间状态
		dieCount:0,房间内死亡人数
		TimerAddBox:null   房间障碍添加定时器
	},
	roomID2:{
		users:{
			roomsOwner:{
				nickName:msg.nickName,
				Avatar:msg.Avatar,
				roomID,
				userID:socket.id,
				status:"readying",
				Identity:"roomsOwner"
			},
			roomer : {
				nickName:msg.nickName,
				Avatar:msg.Avatar,
				userID:socket.id,
				status:"readying",
				Identity:"roomer"
			}
		}
	},
	roomID3:{
		users:{
			roomsOwner:{
				nickName:msg.nickName,
				Avatar:msg.Avatar,
				roomID,
				userID:socket.id,
				status:"readying",
				Identity:"roomsOwner"
			},
			roomer : {
				nickName:msg.nickName,
				Avatar:msg.Avatar,
				userID:socket.id,
				status:"readying",
				Identity:"roomer"
			}
		}
	},
	...
}
*/

/*
	房间状态：1 normal (房间人数不足两个人的时候，可以加入)
			  2 readying (房间人数已满，不可加入，等待准备，房间的人都准备了，就可以开始游戏)
			  3 playing (房间人数已满，不可加入，正在游戏中，房间的人都die了，就结束游戏，如果房间还有两个人，就切换成readying状态)
			  4 gameover (房间人数)
	房客状态：1 normal (初始状态，可马上退出房间)
			  2 readying (准备状态，可退出房间，但要修改房间状态)
			  3 playing (游戏状态，退出或者死亡修改为die，两个都die了。转为normal重新切换。)
			  3 die
*/

//引入模块
var express = require('express'),io = require('socket.io');
io=io.listen(express().listen(5230,()=>{
	console.log("服务开启！")
}));
var Hall = {};
var roomID,user,Idcount=0,TimerAddBox;
Hall.RoomerUpOwner=function(roomID,status){
	delete Hall[roomID].users.roomsOwner;
	Hall[roomID].users.roomsOwner = Hall[roomID].users.roomer;
	Hall[roomID].users.roomsOwner.Identity = "roomsOwner";
	Hall[roomID].users.roomsOwner.status = status;
	delete Hall[roomID].users.roomer;
}

io.on("connection",(socket)=>{
	//创建房间
	socket.on("creatroom",(user)=>{
		roomID = "roomID"+ (++Idcount);
		socket.join(roomID)
		socket.roomID = roomID;
		console.log("创建的房间号是"+roomID)
		roomsOwner = {
			nickName:user.nickName,
			Avatar:user.Avatar,
			userID:socket.id,
			UserGold:0,
			roomID,
			status:"normal",
			Identity:"roomsOwner",
			score:0
		};
		Hall[roomID] = {
			users:{roomsOwner},status:"normal",TimerAddBox:null,roomID
		};
		socket.emit("creatroom",Hall[roomID])
	})

	//加入房间
	socket.on("joinroom",(user,roomID)=>{
		if (!Hall[roomID] || Hall[roomID].status != "normal") {
			if (Hall[roomID]) {
				socket.emit("noroom",Hall[roomID].users);
			}else {
				socket.emit("noroom",'没有房间');
			}
			return;
		};
		socket.join(roomID);
		socket.roomID = roomID;
		roomer = {
			nickName:user.nickName,
			Avatar:user.Avatar,
			userID:socket.id,
			roomID,
			UserGold:0,
			status:"normal",
			Identity:"roomer",
			score:0
		};
		console.log("加入的房间号是"+roomID);
		Hall[roomID].users.roomer=roomer;
		io.to(roomID).emit("addroomer",Hall[roomID]);
		Hall[roomID].status = "readying";
	});

	socket.on("ready",(user)=>{
		roomID=socket.roomID;
		Hall[roomID].users[user].status = "readying";
		if (Hall[roomID].users.roomer.status == "readying" && Hall[roomID].users.roomsOwner.status == "readying") {
			Hall[roomID].users.roomer.status = "playing";
			Hall[roomID].users.roomsOwner.status = "playing";
			io.to(roomID).emit("gamestart");
			Hall[roomID].status = "playing";
			return;
		};
		if (Hall[roomID].users.roomer.status == "readying") {
			socket.broadcast.to(roomID).emit("ready","roomsuser ready");
		}
	});

	//飞机运动
	socket.on("runplane",(user,ori)=>{
		io.to(socket.roomID).emit("runplane",user,ori);
	});

	//障碍宝箱的同步添加
	socket.on("gamestart",()=>{
		Hall[socket.roomID].TimerAddBox=setInterval(function(){
			let Rb = Math.ceil(Math.random()*10);
            let Ro = Math.ceil(Math.random()*10);
            let Bb = Math.ceil(Math.random()*10);
            let Bo = Math.ceil(Math.random()*10);
            if ( Hall[socket.roomID] && Hall[socket.roomID].status == "playing" && Hall[socket.roomID].users.roomsOwner &&Hall[socket.roomID].users.roomsOwner.status == "playing") {
            	io.to(socket.roomID).emit("addbox","blueBox",Bb,Bo);
            };
            if (Hall[socket.roomID] && Hall[socket.roomID].status == "playing" && Hall[socket.roomID].users.roomer&&Hall[socket.roomID].users.roomer.status == "playing") {
            	io.to(socket.roomID).emit("addbox","redBox",Rb,Ro);
            }
		},1000)
	});

	//分数增加
	socket.on("upscore",(user)=>{
		roomID = socket.roomID;
		Hall[roomID].users[user].score += 2;
		console.log(user, Hall[roomID].users[user].score)
		io.to(roomID).emit("upscore",user,Hall[roomID].users[user].score);
	});

	//死亡监听
	socket.on("userdie",(user)=>{
		roomID = socket.roomID;
		Hall[roomID].users[user].status = "die";
		io.to(roomID).emit("userdie",user);
		if ((Hall[roomID].users.roomer.status == "die"||Hall[roomID].users.roomer.status =="exit") && (Hall[roomID].users.roomsOwner.status == "die"||Hall[roomID].users.roomsOwner.status =="exit")) {
			if (Hall[roomID].users.roomsOwner.status == "exit") {
				// delete Hall[roomID].roomsOwner;
				// Hall[roomID].users.roomsOwner = Hall[roomID].users.roomer;
				// Hall[roomID].users.roomsOwner.Identity = "roomsOwner";
				// Hall[roomID].users.roomsOwner.status = "gameover";
				// delete Hall[roomID].users.roomer;
				Hall.RoomerUpOwner(roomID,"gameover");
				if (!Hall[roomID].users.roomsOwner) {
					delete Hall[roomID];
				}
			}
			clearInterval(Hall[roomID].TimerAddBox);
			io.to(roomID).emit("gameover");
			Hall[roomID].status = "gameover";
		};
	});

	//游戏结束
	socket.on("gameover",(user)=>{
		roomID = socket.roomID;
		clearInterval(Hall[roomID].TimerAddBox);
		if (Hall[roomID].status !="gameover") {
			io.to(roomID).emit("gameover",Hall[roomID]);
			if (Hall[roomID].users.roomer.score == Hall[roomID].users.roomsOwner.score) {
				io.to(roomID).emit("outcome","peace");
			}else if(Hall[roomID].users.roomer.score > Hall[roomID].users.roomsOwner.score) {
				io.to(roomID).emit("outcome","roomer");
			}else if(Hall[roomID].users.roomer.score < Hall[roomID].users.roomsOwner.score) {
				io.to(roomID).emit("outcome","roomsOwner");
			};
			Hall[roomID].status = "gameover";
		}
		if (Hall[roomID].users.roomsOwner.status == "exit") {
			Hall[roomID].users.roomsOwner.status = "exit"
		}else {
			Hall[roomID].users.roomsOwner.status = "gameover";
		};
		if (Hall[roomID].users.roomer.status == "exit") {
			Hall[roomID].users.roomer.status = "exit"
		}else {
			Hall[roomID].users.roomer.status = "gameover";
		}
	});

	//返回主页
	socket.on("goback",()=>{
		roomID = socket.roomID;
		for(var k in Hall[roomID].users){
			if(Hall[roomID].users[k].userID == socket.id){
				user = Hall[roomID].users[k];
				Identity=user.Identity;
			}
		};
		if (Hall[roomID].users.roomsOwner.status == "exit") {
			Hall[roomID].users.roomsOwner = Hall[roomID].users.roomer;
			Hall[roomID].users.roomsOwner.Identity = "roomsOwner";
			delete Hall[roomID].users.roomer;
		};
		if (Hall[roomID].users.roomer && Hall[roomID].users.roomer.status == "exit") {
			delete Hall[roomID].users.roomer;
		}
		if (!Hall[roomID].users.roomsOwner || !Hall[roomID].users.roomer) {
			Hall[roomID].users.roomsOwner?Hall[roomID].users.roomsOwner.status = "normal":"";
			Hall[roomID].users.roomer?Hall[roomID].users.roomer.status = "normal":"";
			Hall[roomID].status = "normal"
		};
		if (Hall[roomID].users[Identity] && Hall[roomID].users[Identity].status=="die") {
			Hall[roomID].users[Identity].status="normal";
			if (Hall[roomID].users.roomsOwner.status == "normal" && Hall[roomID].users.roomer.status == "normal") {
				Hall[roomID].status = "readying";
			}
		}
		socket.emit("goback",Hall[roomID]);
	})

	//退出房间
	socket.on("disconnect",()=>{
		roomID = socket.roomID;
		if (!roomID) return;
		if (Hall[roomID]) {
			for(let k in Hall[roomID].users){
				if(Hall[roomID].users[k].userID == socket.id){
					user = Hall[roomID].users[k];
					Identity=user.Identity;
				}
			};
			console.log("退出前")
			console.log(Identity,Hall[roomID].status)
			if (Identity == "roomsOwner") {//房主退出房间
				//正常状态下退出，删除
				if (Hall[roomID].status == "normal") {
					delete Hall[roomID];
				}else

				//准备状态下退出
				if (Hall[roomID].status == "readying") {
					// delete Hall[roomID].users.roomsOwner;
					// Hall[roomID].users.roomsOwner = Hall[roomID].users.roomer;
					// Hall[roomID].users.roomsOwner.Identity = "roomsOwner";
					// Hall[roomID].users.roomsOwner.status = "normal";
					// delete Hall[roomID].users.roomer;
					Hall.RoomerUpOwner(roomID,"normal");
					Hall[roomID].status = "normal";
				}else

				//游戏中退出房间
				if (Hall[roomID].status == "playing") {//游戏中退出房间
					Hall[roomID].users.roomsOwner.status = "exit";
					io.to(roomID).emit("userdie","roomsOwner");

					if ((Hall[roomID].users.roomer.status == "die"||Hall[roomID].users.roomer.status =="exit") && (Hall[roomID].users.roomsOwner.status == "die"||Hall[roomID].users.roomsOwner.status =="exit")) {
						clearInterval(Hall[roomID].TimerAddBox);
						Hall[roomID].status = "gameover";
						io.to(roomID).emit("gameover");
						// delete Hall[roomID].roomsOwner;
						// Hall[roomID].users.roomsOwner = Hall[roomID].users.roomer;
						// Hall[roomID].users.roomsOwner.Identity = "roomsOwner";
						// Hall[roomID].users.roomsOwner.status = "gameover";
						// delete Hall[roomID].users.roomer;
						Hall.RoomerUpOwner(roomID,"gameover");
						if (!Hall[roomID].users.roomsOwner) {
							delete Hall[roomID];
						}
					};
					
				}else

				//游戏结束的时候退出
				if (Hall[roomID].status == "gameover") {
					delete Hall[roomID].roomsOwner;
					if (!Hall[roomID].users.roomer) {
						delete Hall[roomID];
						return;
					}else {
						Hall[roomID].users.roomsOwner = Hall[roomID].users.roomer;
						Hall[roomID].users.roomsOwner.Identity = "roomsOwner";
						Hall[roomID].users.roomsOwner.status = "normal";
						delete Hall[roomID].users.roomer
					}
				}
				if (Hall[roomID]) {
					socket.broadcast.to(roomID).emit("exit",Hall[roomID].users);
				}


			}else if (Identity == "roomer") {//房客退出房间
				
				if (Hall[roomID].status == "readying") {
					if (Hall[roomID].users.roomer.status == "readying") {
						socket.broadcast.to(roomID).emit("ready","roomsuser noready");
					}
					delete Hall[roomID].users.roomer;
					Hall[roomID].status = "normal"
				}else
				if(Hall[roomID].status == "playing") {
					Hall[roomID].users.roomer.status = "exit";
					io.to(roomID).emit("userdie","roomer");
					if ((Hall[roomID].users.roomer.status == "die"||Hall[roomID].users.roomer.status =="exit") && (Hall[roomID].users.roomsOwner.status == "die"||Hall[roomID].users.roomsOwner.status =="exit")) {
						clearInterval(Hall[roomID].TimerAddBox);
						Hall[roomID].status = "gameover";
						io.to(roomID).emit("gameover");
						delete Hall[roomID].users.roomer;
						if (!Hall[roomID].users.roomsOwner) {
							delete Hall[roomID];
						}
					};
				}else 
				if (Hall[roomID].status == "gameover"){
					delete Hall[roomID].users.roomer;
					Hall[roomID].status = "normal"
				}
				if (Hall[roomID]) {
					socket.broadcast.to(roomID).emit("exit",Hall[roomID].users);
				}
			}
			socket.leave(roomID);
		}
	})
})