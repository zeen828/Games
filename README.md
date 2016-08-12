###系統
建置在CentOS
PHP 5.6
CodeIgniter 3.1.0

***

###Games
借用CodeIgniter測試一些html5遊戲測試

***

###懷舊倉庫番
1.[倉庫番關卡參考](http://blog.xuite.net/laurated/game/52802979-%E5%80%89%E5%BA%AB%E7%95%AA-%E5%8F%B2%E4%B8%8A%E5%AE%8C%E6%95%B4%E7%89%88(%E6%94%BB%E7%95%A5))<br/>
建立關卡陣列javascript
直接編輯在applicationviews/games/sokobans/level_1.php(關卡1)

###(1):編輯關卡地圖
		//地圖資料
		var map = [
			["*","*","*","*","*","*","*","*","*"],
			["*","*",".","p",".",".",".","*","*"],
			["*","*",".",".",".","*","*","*","*"],
			["*","*","*","b",".",".",".",".","*"],
			["*",".",".",".","*","b","*",".","*"],
			["*",".","b",".","*",".",".",".","*"],
			["*",".",".",".","*","*","*","*","*"],
			["*","*","*","*","*","*","*","*","*"]
		];
    * => 牆壁
    . => 可行走道路
    b => 有箱子在的可行走道路
    p => 推箱人
###(2):編輯目的地座標
		//
		var goal_array = [
			"4","1",//(X座標, Y座標)
			"5","1",
			"6","1"
		];
    目標要在關卡地圖的哪個座標上0開始算起的陣列
###(3):添加目的地圖形判斷
    function DrawMap(){}
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
			}else{//pathの描画
				ctx.beginPath();
				ctx.fillStyle = "#B8F2A5";
				ctx.fillRect(w*j,w*i,w,h);
				ctx.strokeStyle = "#153B08";
				ctx.strokeRect(w*j,w*i,w,h);
				ctx.closePath();
			}
			break;
    每一點目標要添加一組}else if(j== goal_array[2]&& i == goal_array[3]){//(1,3)のgoalの描画
###(4):建立重置地圖
    function Reset(){}
    //初期の位置設定に戻す
    map = [
      ["*","*","*","*","*","*","*"],
      ["*","g",".","g",".","g","*"],
      ["*",".","b","b","b",".","*"],
      ["*","g","b","p","b","g","*"],
      ["*",".","b","b","b",".","*"],
      ["*","g",".","g",".","g","*"],
      ["*","*","*","*","*","*","*"]
    ];
    可複製(1)的地圖去掉var
###(5):完成判斷
    //クリアのチェック
    if(map[goal_array[1]][goal_array[0]] == "b" && map[goal_array[3]][goal_array[2]] == "b"){
      $("#end").css("display","inline");
      $("#sub_control").css("display","none");
      $("#side").css("display","none");
      $("#wrapper").css("opacity","0.5");
    }
    添加完成條件一個點多一個&&
