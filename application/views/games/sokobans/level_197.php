<!DOCTYPE html>
<html>
<head>
<title>倉庫番</title>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport"
	content="width=480px, initial-scale=0.7, maximum-scale=1.0, user-scalable=no" />
<link rel="shortcut icon" href="/assets/img/favicon.ico">
<link rel="stylesheet" href="/assets/css/games/sokobans/sokban.css">
</head>
<body>
	<div id="content" onclick="start()">
		<div id="wrapper">
			<canvas id='world'></canvas>

			<div id="sub_control">
				<button class="reset" onclick="Reset();">重來</button>
			</div>
			<div id="side">
				<div id="control">
					<button class="punch up" onclick="movePlayer('up');">上</button>
					<button class="punch left" onclick="movePlayer('left');">左</button>
					<button class="punch right" onclick="movePlayer('right');">右</button>
					<button class="punch down" onclick="movePlayer('down');">下</button>
				</div>

			</div>
		</div>
		<p id="start">點擊畫面開始</p>
		<p id="end">過關!</p>
	</div>
	<script
		src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script>
			var canvas = document.getElementById("world");
			//設置工作區域寬
			canvas.setAttribute("width", 480);
			//設置工作區域高
			canvas.setAttribute("height", 200);
			var ctx = canvas.getContext("2d");
			ctx.fillStyle = "#B8F2A5";
			ctx.fillRect(0,0,canvas.width,canvas.height);
			//一格寬
			var w = 40;
			//一格高
			var h = w;
			//地圖資料
			var map = [
				["*","*","*","*","*","*","*","*","*","*","*","*"],
				["*",".",".",".",".",".",".",".",".","b",".","*"],
				["*",".","b","b","b","b","b","b",".","b","p","*"],
				["*",".",".",".",".",".",".",".",".","b",".","*"],
				["*","*","*","*","*","*","*","*","*","*","*","*"]
			];
			//
			var goal_array = [
				"5","1",
				"6","1",
				"7","1",
				"5","2",
				"6","2",
				"7","2",
				"5","3",
				"6","3",
				"7","3"
			];
			//話一個目標
			function DrawGoal(){
				for(var i = 0;i<goal_array.length;i=i+2){
					ctx.beginPath();
					ctx.fillStyle = "#FF801E";
					ctx.fillRect(w*goal_array[i],w*goal_array[i+1],w,h);
					var lw = 3;
					ctx.fillStyle = "#E16200";
					ctx.fillRect(w*goal_array[i] + lw,w*goal_array[i+1] + lw ,w - lw -3,h -lw -3);
					ctx.closePath();
				}
			}

			//繪製布局
			function DrawMap(){
				DrawGoal();
				for(var i = 0;i<map.length;i++){
					for(var j = 0;j<map[i].length;j++){
						switch(map[i][j]){
						case "*"://Wall
							//マスを初期化
							ctx.clearRect(w*j,w*i,w,h);
							//背景を塗る
							ctx.fillStyle = "#B8F2A5";
							ctx.fillRect(w*j,w*i,w,h);
							//角の丸み
							var a = 10;
							ctx.fillStyle = "#680E1F";
							//壁の描画
							ctx.beginPath();
							ctx.moveTo(w*j + a, w*i);
							ctx.lineTo(w*j + w - a, w*i);
							ctx.quadraticCurveTo(w*j + w, w*i,w*j + w, w*i + a );
							ctx.lineTo(w*j + w, w*i + h -a);
							ctx.quadraticCurveTo(w*j + w, w*i + h,w*j + w - a, w*i + h);
							ctx.lineTo(w*j + a, w*i + h);
							ctx.quadraticCurveTo(w*j, w*i + h,w*j, w*i + h -a);
							ctx.lineTo(w*j,w*i + a);
							ctx.quadraticCurveTo(w*j,w*i,w*j + a,w*i);
							ctx.closePath();
							ctx.fill();
							//border-bottom
							ctx.beginPath();
							ctx.lineWidth = 1;
							ctx.moveTo(w*j - 1, w*i + h - 1);
							ctx.lineTo(w*j + 1, w*i + h - 1);
							ctx.closePath();
							ctx.strokeStyle = "#B4BCAD";
							ctx.stroke();
							//border-left
							ctx.beginPath();
							ctx.lineWidth = 1;
							ctx.moveTo(w*j + 1, w*i + 1);
							ctx.lineTo(w*j + 1, w*i + h - 1);
							ctx.closePath();
							ctx.strokeStyle = "#682834";
							ctx.stroke();
							//壁の影の描画
							//影の大きさ
							var shadow = 7;
							ctx.fillStyle = "#4A0A16";
							//パスを開始
							ctx.beginPath();
							//パス開始点に移動
							ctx.moveTo(w*j + w, w*i + h -a - shadow);
							ctx.lineTo(w*j + w, w*i + h -a);
							//右下の曲線
							ctx.quadraticCurveTo(w*j + w, w*i + h,w*j + w - a, w*i + h);
							ctx.lineTo(w*j + a, w*i + h);
							//左下の曲線
							ctx.quadraticCurveTo(w*j, w*i + h,w*j, w*i + h -a);
							ctx.lineTo(w*j, w*i + h -a - shadow);
							//左下、影の内枠の曲線
							ctx.quadraticCurveTo(w*j, w*i + h - shadow,w*j + a, w*i + h - shadow);
							ctx.lineTo(w*j + w - a, w*i + h - shadow);
							//右下、影の内枠の曲線
							ctx.quadraticCurveTo(w*j + w, w*i + h - shadow,w*j + w, w*i + h -a - shadow);
							ctx.closePath();
							//塗りつぶし
							ctx.fill();
							break;
						case "."://Path
							//マスを初期化
							ctx.clearRect(w*j,w*i,w,h);
							if(j== goal_array[0]&& i == goal_array[1]){//(2,1)のgoalの描画
								ctx.beginPath();
								ctx.fillStyle = "#FF801E";
								ctx.fillRect(w*goal_array[0],w*goal_array[1],w,h);
								var lw = 3;
								ctx.fillStyle = "#E16200";
								ctx.fillRect(w*goal_array[0] + lw,w*goal_array[1] + lw ,w - lw -3,h -lw -3);
								ctx.closePath();
							}else if(j== goal_array[2]&& i == goal_array[3]){
								ctx.beginPath();
								ctx.fillStyle = "#FF801E";
								ctx.fillRect(w*goal_array[2],w*goal_array[3],w,h);
								var lw = 3;
								ctx.fillStyle = "#E16200";
								ctx.fillRect(w*goal_array[2] + lw,w*goal_array[3] + lw ,w - lw -3,h -lw -3);
								ctx.closePath();
							}else if(j== goal_array[4]&& i == goal_array[5]){
								ctx.beginPath();
								ctx.fillStyle = "#FF801E";
								ctx.fillRect(w*goal_array[4],w*goal_array[5],w,h);
								var lw = 3;
								ctx.fillStyle = "#E16200";
								ctx.fillRect(w*goal_array[4] + lw,w*goal_array[5] + lw ,w - lw -3,h -lw -3);
								ctx.closePath();
							}else if(j== goal_array[6]&& i == goal_array[7]){
								ctx.beginPath();
								ctx.fillStyle = "#FF801E";
								ctx.fillRect(w*goal_array[6],w*goal_array[7],w,h);
								var lw = 3;
								ctx.fillStyle = "#E16200";
								ctx.fillRect(w*goal_array[6] + lw,w*goal_array[7] + lw ,w - lw -3,h -lw -3);
								ctx.closePath();
							}else if(j== goal_array[8]&& i == goal_array[9]){
								ctx.beginPath();
								ctx.fillStyle = "#FF801E";
								ctx.fillRect(w*goal_array[8],w*goal_array[9],w,h);
								var lw = 3;
								ctx.fillStyle = "#E16200";
								ctx.fillRect(w*goal_array[8] + lw,w*goal_array[9] + lw ,w - lw -3,h -lw -3);
								ctx.closePath();
							}else if(j== goal_array[10]&& i == goal_array[11]){
								ctx.beginPath();
								ctx.fillStyle = "#FF801E";
								ctx.fillRect(w*goal_array[10],w*goal_array[11],w,h);
								var lw = 3;
								ctx.fillStyle = "#E16200";
								ctx.fillRect(w*goal_array[10] + lw,w*goal_array[11] + lw ,w - lw -3,h -lw -3);
								ctx.closePath();
							}else if(j== goal_array[12]&& i == goal_array[13]){
								ctx.beginPath();
								ctx.fillStyle = "#FF801E";
								ctx.fillRect(w*goal_array[12],w*goal_array[13],w,h);
								var lw = 3;
								ctx.fillStyle = "#E16200";
								ctx.fillRect(w*goal_array[12] + lw,w*goal_array[13] + lw ,w - lw -3,h -lw -3);
								ctx.closePath();
							}else if(j== goal_array[14]&& i == goal_array[15]){
								ctx.beginPath();
								ctx.fillStyle = "#FF801E";
								ctx.fillRect(w*goal_array[14],w*goal_array[15],w,h);
								var lw = 3;
								ctx.fillStyle = "#E16200";
								ctx.fillRect(w*goal_array[14] + lw,w*goal_array[15] + lw ,w - lw -3,h -lw -3);
								ctx.closePath();
							}else if(j== goal_array[16]&& i == goal_array[17]){
								ctx.beginPath();
								ctx.fillStyle = "#FF801E";
								ctx.fillRect(w*goal_array[16],w*goal_array[17],w,h);
								var lw = 3;
								ctx.fillStyle = "#E16200";
								ctx.fillRect(w*goal_array[16] + lw,w*goal_array[17] + lw ,w - lw -3,h -lw -3);
								ctx.closePath();
							}else{//pathの描画
								ctx.beginPath();
								ctx.fillStyle = "#B8F2A5";
								ctx.fillRect(w*j,w*i,w,h);
								ctx.strokeStyle = "#153B08";
								ctx.strokeRect(w*j,w*i,w,h);
								ctx.closePath();
							}
							break;
						case "p"://Player					
							ctx.beginPath();
							ctx.arc(w*j+w/2,w*i+w/2, w/2,0, Math.PI*2, false);
							ctx.closePath();
							ctx.fillStyle = "#002477";
							ctx.fill();

							break;
						case "b"://Box
							a = 15;
							ctx.fillStyle = "#888888";

							ctx.beginPath();

							ctx.moveTo(w*j + a, w*i);
							ctx.lineTo(w*j + w - a, w*i);
							ctx.quadraticCurveTo(w*j + w, w*i,w*j + w, w*i + a );
							ctx.lineTo(w*j + w, w*i + h -a);
							ctx.quadraticCurveTo(w*j + w, w*i + h,w*j + w - a, w*i + h);
							ctx.lineTo(w*j + a, w*i + h);
							ctx.quadraticCurveTo(w*j, w*i + h,w*j, w*i + h -a);
							ctx.lineTo(w*j,w*i + a);
							ctx.quadraticCurveTo(w*j,w*i,w*j + a,w*i);

							ctx.closePath();

							ctx.fill();

							shadow = 7;
							ctx.fillStyle = "#6A6A6A";
							ctx.beginPath();

							ctx.moveTo(w*j + w, w*i + h -a - shadow);
							ctx.lineTo(w*j + w, w*i + h -a);
							//右下の曲線
							ctx.quadraticCurveTo(w*j + w, w*i + h,w*j + w - a, w*i + h);
							ctx.lineTo(w*j + a, w*i + h);
							//左下の曲線
							ctx.quadraticCurveTo(w*j, w*i + h,w*j, w*i + h -a);
							ctx.lineTo(w*j, w*i + h -a - shadow);

							//左下、影の内枠の曲線
							ctx.quadraticCurveTo(w*j, w*i + h - shadow,w*j + a, w*i + h - shadow);
							ctx.lineTo(w*j + w - a, w*i + h - shadow);

							//右下、影の内枠の曲線
							ctx.quadraticCurveTo(w*j + w, w*i + h - shadow,w*j + w, w*i + h -a - shadow);

							ctx.closePath();

							ctx.fill();
							break;
						default:
							break;
						}
					}
				}
				//解答區
				if(
						map[goal_array[1]][goal_array[0]] == "b" &&
						map[goal_array[3]][goal_array[2]] == "b" &&
						map[goal_array[5]][goal_array[4]] == "b" &&
						map[goal_array[7]][goal_array[6]] == "b" &&
						map[goal_array[9]][goal_array[8]] == "b" &&
						map[goal_array[11]][goal_array[10]] == "b" &&
						map[goal_array[13]][goal_array[12]] == "b" &&
						map[goal_array[15]][goal_array[14]] == "b" &&
						map[goal_array[17]][goal_array[16]] == "b"
				){
					$("#end").css("display","inline");
					$("#sub_control").css("display","none");
					$("#side").css("display","none");
					$("#wrapper").css("opacity","0.5");
				}

			}

			//重製初始畫面
			function Reset(){
				//初期の位置設定に戻す
				map = [
					["*","*","*","*","*","*","*","*","*","*","*","*"],
					["*",".",".",".",".",".",".",".",".","b",".","*"],
					["*",".","b","b","b","b","b","b",".","b","p","*"],
					["*",".",".",".",".",".",".",".",".","b",".","*"],
					["*","*","*","*","*","*","*","*","*","*","*","*"]
				];
				//クリア
				ctx.clearRect(0,0,canvas.width,canvas.height);
				ctx.fillStyle = "#B8F2A5";
				ctx.fillRect(0,0,canvas.width,canvas.height);
				//再描画
				DrawMap();
			}

			//取得當下位置
			function CurrentMap(elememt){
				for(var i = 0;i<map.length;i++){
					for(var j = 0;j<map[i].length;j++){
						if(map[i][j]==elememt){
							var current = {x:j,y:i}; 
							return current;
						}
					}
				}
			}

			//移動後繪圖
			function NextMap(preX,preY,nextX,nextY,element){  
				//クリア
				ctx.clearRect(w*preX,h*preY,w,h);
				//移動後をpathにする
				map[preY][preX] = ".";
				//マップの要素を更新
				map[nextY][nextX] = element;
				//再描画
				DrawMap();
			}

			//人的運動
			function movePlayer(key) {
				var cur_p,next_p;
				var cur_b,next_b;
				//プレイヤーの現在地を取得
				cur_p = CurrentMap("p");
				switch(key){
				case "up":
					next_p = {x:cur_p.x,y:cur_p.y-1};
					break;
				case "down":
					next_p = {x:cur_p.x,y:cur_p.y+1};
					break;
				case "left":
					next_p = {x:cur_p.x-1,y:cur_p.y};
					break;
				case "right":
					next_p = {x:cur_p.x+1,y:cur_p.y};
					break;
				default:
					break;
				}
				if(map[next_p.y][next_p.x] != "*"){//playerの移動後,Wallと重ならない場合
					if(map[next_p.y][next_p.x] == "b"){//playerとboxが重なるとき
						//boxもキー入力に従って移動
						switch(key){
						case "up":
							next_b = {x:next_p.x,y:next_p.y-1};
							break;
						case "down":
							next_b = {x:next_p.x,y:next_p.y+1};
							break;
						case "left":
							next_b = {x:next_p.x-1,y:next_p.y};
							break;
						case "right":
							next_b = {x:next_p.x+1,y:next_p.y};
							break;
						default:
							break;
						}
						if(map[next_b.y][next_b.x] != "*" && map[next_b.y][next_b.x] != "b"){//boxの移動後,boxとWallに重ならない場合
							//移動後のマップをセット
							NextMap(next_p.x,next_p.y,next_b.x,next_b.y,"b");
							NextMap(cur_p.x,cur_p.y,next_p.x,next_p.y,"p");
						}
					}else{
						NextMap(cur_p.x,cur_p.y,next_p.x,next_p.y,"p"); 
					}
				}
			}

			//點擊開始
			function start(){
				$("#start").css("display","none");
				$("#wrapper").css("opacity","1");
			}
			window.onload = function(){
				//スタート時の描画
				DrawMap();
			};
			$("body").live("keydown", function(evt) {
				switch (evt.which) {
				case 38: //上
					movePlayer('up');
					break;
				case 40: //下
					movePlayer('down')
					break;
				case 37: //左(會導致輸入時無法使用左右移)
					movePlayer('left')
					break;
				case 39: //右(會導致輸入時無法使用左右移)
					movePlayer('right')
					break;
				default:
					return;
				}
			});
	</script>
</body>
</html>



