<!doctype html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Shoot 'em up!</title>
        <script src="phaser.js"></script>
		<script defer src="http://localhost/laravel/shop/public/jquery.js"> </script>
    </head>
    <body>

    <script type="text/javascript">	 
	
	window.onload = function() {var game;
	$.getJSON("http://localhost/laravel/shop/public/weapons/5", function (data) {
		console.log(data);
		PremiumWeapon = data; 
	 game = new Phaser.Game(800, 600, Phaser.AUTO, '', {preload: preload, create: create, update: update, render: render});
     });

	var player;                  // игрок
	var hp; 					 // жизни
	var PowerChar = 0;           // сила персонажа
	var SpeedChar = 200;         // скорость
	var BulletSpeedChar = 0;
	var BulletSpacingChar = 0;   // перезарядка (чем больше, тем быстрее)
	var bulletTimer = 1;       
	var DifficultySpeed = 0;
	var BonusType = 0;
	var field;                   // бекграунд             
	var direction;               // направление
	var effect;                  // эффект сзади игрока
	var bullets;                 // пули
	var evilBullets;             // пули врагов
    var fireButton;              // нажатие кнопки выстрела (пробел или ЛКМ)
	var Coop;
	var XBonus;                  // расположение бонусов
	var YBonus;
	var Enemy1;                  // описание врагов
	var Enemy2;
	var Enemy3;
	var Enemy4;
	var Enemy5;                  // босс
	var Enemy6;
	var Enemy7;                  // босс
	var Enemy8;
	var Lasers;                  // вражеские лазеры
	var BonusSpacing;            // увеличение скорострельности
	var Enemy1LaunchTimer;       
	var Enemy2LaunchTimer;
	var Enemy3LaunchTimer;
	var Enemy4LaunchTimer;  
	var Enemy5LaunchTimer;  
	var explosions;              // столкновение
	var bonuses; 
	var score = 0;               // счетчик очков
	var scoreText;
	var Evil = 0;                // счетчик врагов
	var EvilCount;				 
	var OP = 1;                  // отображение неуязвимости
	var OPText;
	var PremiumWeapon;
	var Data;
	var Invisible = 1;           // счетчик неуязвимости
	var InvisibleTime = 0;
	var music = 0;
	var statistic = 0;
	var Level = 4;
	var Combo = 0;
	var ComboVisible;
	var ComboText;
	var MaxCombo = 0;
	var GameEnd = 0;

	function preload() {
		game.load.image('field', './pics/galaxi.png'); 
		game.load.image('ship', './pics/raketa3.png');
		game.load.image('bullet', './pics/bullet1.png');
		game.load.image('evilBullet', './pics/bullet2.png');
		game.load.image('evilBullet6', './pics/evilbullet6.png');
		game.load.image('button', './pics/button.png');
		game.load.image('effect', './pics/effect.png');
		game.load.image('bonus', './pics/bonus.png');
		game.load.image('troll1', './pics/aster.png'); 
		game.load.image('troll2', './pics/ship.png');
		game.load.image('boss1laser', './pics/laser.png');  // поменять!!!
		game.load.image('troll3', './pics/kamikadze.png');
		game.load.image('troll4', './pics/raketa1.png');
		game.load.image('enemy8', './pics/enemy8.png');
		game.load.image('enemy8left', './pics/enemy8left.png');
		game.load.image('enemy10', './pics/enemy10.png');
		game.load.image('enemy10top', './pics/enemy10top.png');
		game.load.image('enemy10left', './pics/enemy10left.png');
		game.load.image('enemy10right', './pics/enemy10right.png');
		game.load.image('enemy10leftdown', './pics/enemy10leftdown.png');
		game.load.image('enemy10rightdown', './pics/enemy10rightdown.png');
		game.load.image('lamb', './pics/lamb.png');
		game.load.image('chest', './pics/chest.png');
		game.load.image('HealChest', './pics/heals.png');
		game.load.image('laser', './pics/laser.png');
		game.load.image('brims', './pics/brimst.png');
		game.load.image('speed', './pics/speed.png');
		game.load.image('god', './pics/god.png');
		game.load.image('musicoff', './pics/musicoff.png');
		game.load.image('shield', './pics/shield.png');
		game.load.image('bulletspeed', './pics/bulletspeed.png');
		game.load.image('bulletspacing', './pics/bulletspacing.png');
		game.load.image('bulletpower', './pics/player1.png');
		game.load.image('OP', './pics/charshield.png');
		game.load.image('heart', './pics/heart.png');
		game.load.spritesheet('explosion', './pics/explode.png', 128, 128);
		game.load.spritesheet('bonusEating', './pics/bonus.png', 128, 128);
		game.load.audio('wpn1', './musc/wpn1.mp3'); 
		game.load.audio('wpn5', './musc/wpn5.mp3'); 
		game.load.audio('Monster', './musc/Monster.mp3'); 
		game.load.audio('UltraCombo', './musc/UltraCombo.mp3'); 
		game.load.audio('ULTRA', './musc/ULTRA.mp3'); 
	}

	function create() {
		wpn1 = game.add.audio('wpn1');
		wpn5 = game.add.audio('wpn5');
		wpn1.volume = 0.1;
		wpn5.volume = 0.1;
		ULTRA = game.add.audio('ULTRA');
		UltraCombo = game.add.audio('UltraCombo');
		Monster = game.add.audio('Monster');
		wpn1.allowMultiple = true;
		wpn5.allowMultiple = true;
		field = game.add.tileSprite(0, 0, 800, 600, 'field');  
		
		bullets = game.add.group();                       
		bullets.enableBody = true;   
		bullets.physicsBodyType = Phaser.Physics.ARCADE;
		bullets.createMultiple(100, 'bullet');
		bullets.setAll('anchor.x', 2);
		bullets.setAll('anchor.y', 2);
		bullets.setAll('outOfBoundsKill', true);
		bullets.setAll('checkWorldBounds', true);
		
		brimstone = game.add.group();                       
		brimstone.enableBody = true;   
		brimstone.physicsBodyType = Phaser.Physics.ARCADE;
		brimstone.createMultiple(30, 'brims');
		brimstone.setAll('anchor.x', 1);
		brimstone.setAll('anchor.y', 1);
		brimstone.setAll('outOfBoundsKill', true);
		brimstone.setAll('checkWorldBounds', true);
		
		player = game.add.sprite(400, 600, 'ship');       // установка игрока
		player.anchor.setTo(0.5, 0.5);
		player.health = 200;                              // количество HP и обработка столкновения
		player.weaponLevel = 1;
	
		hp = game.add.text(5, 535, 'HP: ' + player.health +'%', { font: '20px Arial', fill: '#999999' });
        hp.render = function () {
			hp.text = 'HP: ' + Math.max(player.health, 0) +'%';   // отображение HP
		}  
		hp.render();
		
		scoreText = game.add.text(5, 555, '', { font: '20px Arial', fill: '#999999' });
		scoreText.render = function () {
			scoreText.text = 'Score: ' + score;                   // отображение очков 
		};
		scoreText.render();
		
		enemyCount = game.add.text(5, 575, '', { font: '20px Arial', fill: '#999999' });
		enemyCount.render = function () {
			enemyCount.text = 'Kills: ' + Evil;                   // отображение очков
		};
		enemyCount.render();
		
		OPText = game.add.text(760, 575, '', { font: '20px Arial', fill: '#999999' });
		OPText.render = function () {
			OPText.text =  'x' + OP;                   // отображение очков
		};
		OPText.render();
		
		CharPowerText = game.add.text(5, 5, 'Bullet Power: +' + PowerChar.toFixed(1), { font: '16px Arial', fill: '#999999' });
		CharPowerText.render = function () {
			CharPowerText.text = 'Bullet Power: +' + PowerChar.toFixed(1);
		}
		CharPowerText.render();
		
	    CharBulletSpeedText = game.add.text(5, 25, 'Bullet Speed: +' + BulletSpeedChar, { font: '16px Arial', fill: '#999999' });
		CharBulletSpeedText.render = function () {
			CharBulletSpeedText.text = 'Bullet Speed: +' + BulletSpeedChar;
		}
		CharBulletSpeedText.render();
		
		CharReloadingText = game.add.text(5, 45, 'Bullet Reloading: +' + BulletSpacingChar, { font: '16px Arial', fill: '#999999' });
		CharReloadingText.render = function () {
			CharReloadingText.text = 'Bullet Reloading: +' + BulletSpacingChar;
		}
		CharReloadingText.render();
		
		CharSpeedText = game.add.text(5, 65, 'Ship Speed: ' + SpeedChar, { font: '16px Arial', fill: '#999999' });
		CharSpeedText.render = function () {
			CharSpeedText.text = 'Ship Speed: ' + SpeedChar;
		}
		CharSpeedText.render();  
		
		MaxComboShow = game.add.text(5, 85, 'Max Combo: ' + MaxCombo, { font: '16px Arial', fill: '#999999' });
		MaxComboShow.render = function () {
			MaxComboShow.text = 'Max Combo: ' + MaxCombo;
		}
		MaxComboShow.render();  
		
		CharPowerText.visible = false;
		CharBulletSpeedText.visible = false;
		CharReloadingText.visible = false;
		CharSpeedText.visible = false; 
		MaxComboShow.visible = false;
		
		game.physics.enable(player, Phaser.Physics.ARCADE);  	
		
		Bonus1 = game.add.group();                          
		Bonus1.enableBody = true;
		Bonus1.physicsBodyType = Phaser.Physics.ARCADE;
		Bonus1.createMultiple(6, 'speed');
		Bonus1.setAll('anchor.x', 0.5);
		Bonus1.setAll('anchor.y', 0.5);
		Bonus1.setAll('scale.x', 0.5);
		Bonus1.setAll('scale.y', 0.5);
		Bonus1.setAll('outOfBoundsKill', true);
		Bonus1.setAll('checkWorldBounds', true);
		Bonus1.forEach(function(enemy){
			enemy.body.setSize(enemy.width * 3 / 4, enemy.height * 3 / 4);  
		});
		
		Bonus2 = game.add.group();                          
		Bonus2.enableBody = true;
		Bonus2.physicsBodyType = Phaser.Physics.ARCADE;
		Bonus2.createMultiple(6, 'bulletspeed');
		Bonus2.setAll('anchor.x', 0.5);
		Bonus2.setAll('anchor.y', 0.5);
		Bonus2.setAll('scale.x', 0.5);
		Bonus2.setAll('scale.y', 0.5);
		Bonus2.setAll('outOfBoundsKill', true);
		Bonus2.setAll('checkWorldBounds', true);
		Bonus2.forEach(function(enemy){
			enemy.body.setSize(enemy.width * 3 / 4, enemy.height * 3 / 4);  
		});
		
		Bonus3 = game.add.group();                          
		Bonus3.enableBody = true;
		Bonus3.physicsBodyType = Phaser.Physics.ARCADE;
		Bonus3.createMultiple(6, 'bulletspacing');
		Bonus3.setAll('anchor.x', 0.5);
		Bonus3.setAll('anchor.y', 0.5);
		Bonus3.setAll('scale.x', 0.5);
		Bonus3.setAll('scale.y', 0.5);
		Bonus3.setAll('outOfBoundsKill', true);
		Bonus3.setAll('checkWorldBounds', true);
		Bonus3.forEach(function(enemy){
			enemy.body.setSize(enemy.width * 3 / 4, enemy.height * 3 / 4);  
		});
		
		Bonus4 = game.add.group();                          
		Bonus4.enableBody = true;
		Bonus4.physicsBodyType = Phaser.Physics.ARCADE;
		Bonus4.createMultiple(6, 'bulletpower');
		Bonus4.setAll('anchor.x', 0.5);
		Bonus4.setAll('anchor.y', 0.5);
		Bonus4.setAll('scale.x', 0.5);
		Bonus4.setAll('scale.y', 0.5);
		Bonus4.setAll('outOfBoundsKill', true);
		Bonus4.setAll('checkWorldBounds', true);
		Bonus4.forEach(function(enemy){
			enemy.body.setSize(enemy.width * 3 / 4, enemy.height * 3 / 4);  
		});
		
		Bonus5 = game.add.group();                          
		Bonus5.enableBody = true;
		Bonus5.physicsBodyType = Phaser.Physics.ARCADE;
		Bonus5.createMultiple(6, 'OP');
		Bonus5.setAll('anchor.x', 0.5);
		Bonus5.setAll('anchor.y', 0.5);
		Bonus5.setAll('scale.x', 0.5);
		Bonus5.setAll('scale.y', 0.5);
		Bonus5.setAll('outOfBoundsKill', true);
		Bonus5.setAll('checkWorldBounds', true);
		Bonus5.forEach(function(enemy){
			enemy.body.setSize(enemy.width * 3 / 4, enemy.height * 3 / 4);  
		});
		
		Bonus6 = game.add.group();                          
		Bonus6.enableBody = true;
		Bonus6.physicsBodyType = Phaser.Physics.ARCADE;
		Bonus6.createMultiple(6, 'heart');
		Bonus6.setAll('anchor.x', 0.5);
		Bonus6.setAll('anchor.y', 0.5);
		Bonus6.setAll('scale.x', 0.5);
		Bonus6.setAll('scale.y', 0.5);
		Bonus6.setAll('outOfBoundsKill', true);
		Bonus6.setAll('checkWorldBounds', true);
		Bonus6.forEach(function(enemy){
			enemy.body.setSize(enemy.width * 3 / 4, enemy.height * 3 / 4);  
		});
		
		Enemy1 = game.add.group();                          
		Enemy1.enableBody = true;
		Enemy1.physicsBodyType = Phaser.Physics.ARCADE;
		Enemy1.createMultiple(5, 'troll1');
		Enemy1.setAll('anchor.x', 0.5);
		Enemy1.setAll('anchor.y', 0.5);
		Enemy1.setAll('scale.x', 0.5);
		Enemy1.setAll('scale.y', 0.5);
		Enemy1.setAll('outOfBoundsKill', true);
		Enemy1.setAll('checkWorldBounds', true);
		Enemy1.forEach(function(enemy){
			enemy.body.setSize(enemy.width * 2 / 4, enemy.height * 2 / 4);  
		});
		
		Enemy2 = game.add.group();                       
		Enemy2.enableBody = true;
		Enemy2.physicsBodyType = Phaser.Physics.ARCADE;
		Enemy2.createMultiple(30, 'troll2');
		Enemy2.setAll('anchor.x', 0.5);
		Enemy2.setAll('anchor.y', 0.5);
		Enemy2.setAll('scale.x', 0.5);
		Enemy2.setAll('scale.y', 0.5);
		Enemy2.forEach(function(enemy){
			enemy.body.setSize(enemy.width * 3 / 4, enemy.height * 3 / 4); 
		});
			
		Enemy2evilBullets = game.add.group();                            
		Enemy2evilBullets.enableBody = true;
		Enemy2evilBullets.physicsBodyType = Phaser.Physics.ARCADE;
		Enemy2evilBullets.createMultiple(30, 'evilBullet');
		Enemy2evilBullets.callAll('crop', null, {x: 90, y: 0, width: 90, height: 70});  // {x: 90, y: 0, width: 90, height: 70});
		Enemy2evilBullets.setAll('alpha', 0.9);
		Enemy2evilBullets.setAll('anchor.x', 0.5);
		Enemy2evilBullets.setAll('anchor.y', 0.5);
		Enemy2evilBullets.setAll('outOfBoundsKill', true);
		Enemy2evilBullets.setAll('checkWorldBounds', true);
		Enemy2evilBullets.forEach(function(enemy){
			enemy.body.setSize(20, 20);
		});
		
		Enemy3 = game.add.group();                        
		Enemy3.enableBody = true;
		Enemy3.physicsBodyType = Phaser.Physics.ARCADE;
		Enemy3.createMultiple(30, 'troll3');
		Enemy3.setAll('anchor.x', 0.5);
		Enemy3.setAll('anchor.y', 0.5);
		Enemy3.setAll('scale.x', 0.5);
		Enemy3.setAll('scale.y', 0.5);
		Enemy3.forEach(function(enemy){
			enemy.body.setSize(enemy.width * 3 / 4, enemy.height * 3 / 4);  
		});
		
		Enemy4 = game.add.group();                       
		Enemy4.enableBody = true;
		Enemy4.physicsBodyType = Phaser.Physics.ARCADE;
		Enemy4.createMultiple(30, 'troll4');
		Enemy4.setAll('anchor.x', 0.5);
		Enemy4.setAll('anchor.y', 0.5);
		Enemy4.setAll('scale.x', 0.5);
		Enemy4.setAll('scale.y', 0.5);
		Enemy4.setAll('angle', 180);
		Enemy4.forEach(function(enemy){
			enemy.body.setSize(enemy.width * 3 / 4, enemy.height * 2 / 4);  
		});
		
		Enemy5 = game.add.group();                       
		Enemy5.enableBody = true;
		Enemy5.physicsBodyType = Phaser.Physics.ARCADE;
		Enemy5.createMultiple(30, 'troll4');
		Enemy5.setAll('anchor.x', 0.5);
		Enemy5.setAll('anchor.y', 0.5);
		Enemy5.setAll('scale.x', 0.5);
		Enemy5.setAll('scale.y', 0.5);
		Enemy5.setAll('angle', 180);
		Enemy5.forEach(function(enemy){
			enemy.body.setSize(enemy.width * 3 / 4, enemy.height * 2 / 4);  
		});
		
		Enemy6 = game.add.group();                       
		Enemy6.enableBody = true;
		Enemy6.physicsBodyType = Phaser.Physics.ARCADE;
		Enemy6.createMultiple(30, 'troll4');
		Enemy6.setAll('anchor.x', 0.5);
		Enemy6.setAll('anchor.y', 0.5);
		Enemy6.setAll('scale.x', 0.5);
		Enemy6.setAll('scale.y', 0.5);
		Enemy6.setAll('angle', 180);
		Enemy6.forEach(function(enemy){
			enemy.body.setSize(enemy.width * 3 / 4, enemy.height * 2 / 4);  
		});
		
		Enemy6evilBullets = game.add.group();                            
		Enemy6evilBullets.enableBody = true;
		Enemy6evilBullets.physicsBodyType = Phaser.Physics.ARCADE;
		Enemy6evilBullets.createMultiple(100, 'evilBullet6');
		Enemy6evilBullets.setAll('anchor.x', 0.5);
		Enemy6evilBullets.setAll('anchor.y', 0.5);
		Enemy6evilBullets.setAll('outOfBoundsKill', true);
		Enemy6evilBullets.setAll('checkWorldBounds', true);
		Enemy6evilBullets.forEach(function(enemy){
			enemy.body.setSize(15, 15);
		});
		
		Enemy7 = game.add.group();                       
		Enemy7.enableBody = true;
		Enemy7.physicsBodyType = Phaser.Physics.ARCADE;
		Enemy7.createMultiple(30, 'troll4');
		Enemy7.setAll('anchor.x', 0.5);
		Enemy7.setAll('anchor.y', 0.5);
		Enemy7.setAll('scale.x', 0.5);
		Enemy7.setAll('scale.y', 0.5);
		Enemy7.setAll('angle', 180);
		Enemy7.forEach(function(enemy){
			enemy.body.setSize(enemy.width * 3 / 4, enemy.height * 2 / 4);  
		});
		
		Enemy8 = game.add.group();                       
		Enemy8.enableBody = true;
		Enemy8.physicsBodyType = Phaser.Physics.ARCADE;
		Enemy8.createMultiple(1000, 'enemy8');
		Enemy8.setAll('anchor.x', 0.5);
		Enemy8.setAll('anchor.y', 0.5);
		Enemy8.setAll('scale.x', 1);
		Enemy8.setAll('scale.y', 1);
		Enemy8.setAll('angle', 180);
		Enemy8.forEach(function(enemy){
			enemy.body.setSize(enemy.width * 3 / 4, enemy.height * 2 / 4);  
		});
		
		Enemy9 = game.add.group();                       
		Enemy9.enableBody = true;
		Enemy9.physicsBodyType = Phaser.Physics.ARCADE;
		Enemy9.createMultiple(30, 'enemy8');
		Enemy9.setAll('anchor.x', 0.5);
		Enemy9.setAll('anchor.y', 0.5);
		Enemy9.setAll('scale.x', 1);
		Enemy9.setAll('scale.y', 1);
		Enemy9.setAll('angle', 180);
		Enemy9.forEach(function(enemy){
			enemy.body.setSize(enemy.width * 3 / 4, enemy.height * 2 / 4);  
		});
		
		Enemy10 = game.add.group();                       
		Enemy10.enableBody = true;
		Enemy10.physicsBodyType = Phaser.Physics.ARCADE;
		Enemy10.createMultiple(2, 'enemy10');
		Enemy10.setAll('anchor.x', 0.5);
		Enemy10.setAll('anchor.y', 0.5);
		Enemy10.setAll('scale.x', 1);
		Enemy10.setAll('scale.y', 1);
		Enemy10.forEach(function(enemy){
			enemy.body.setSize(enemy.width, enemy.height);  
		});
		
		effect = game.add.emitter(player.x, player.y, 400);  // эффект сзади коробля
    	effect.width = 10;
    	effect.makeParticles('effect');
    	effect.setXSpeed(30, -30);
		effect.setYSpeed(200, 180);
		effect.setRotation(50, -50);  
		effect.setAlpha(1, 0.01, 800);  
		effect.setScale(0.05, 0.4, 0.05, 0.4, 2000, Phaser.Easing.Quintic.Out);  
		effect.start(false, 5000, 10);
		
    	explosions = game.add.group();                       
		explosions.enableBody = true;
		explosions.physicsBodyType = Phaser.Physics.ARCADE;
		explosions.createMultiple(30, 'explosion');
		explosions.setAll('anchor.x', 0.5);
		explosions.setAll('anchor.y', 0.5);
		explosions.forEach(function(explosion){
			explosion.animations.add('explosion');
		});  
		
		bonuses = game.add.group();                       
		bonuses.enableBody = true;
		bonuses.physicsBodyType = Phaser.Physics.ARCADE;
		bonuses.createMultiple(30, 'bonusEating');
		bonuses.setAll('anchor.x', 0.5);
		bonuses.setAll('anchor.y', 0.5);
		bonuses.forEach(function(bonusEating){
			bonusEating.animations.add('bonusEating');
		});  
		
		lasers = game.add.group();                       
		lasers.enableBody = true;
		lasers.physicsBodyType = Phaser.Physics.ARCADE;
		lasers.createMultiple(30, 'laser');
		lasers.setAll('anchor.x', 0.5);
		lasers.setAll('anchor.y', 0.5);
		lasers.forEach(function(laser){
			laser.animations.add('laser');
		});  
		
		gods = game.add.group();                      
		gods.enableBody = true;
		gods.physicsBodyType = Phaser.Physics.ARCADE;
		gods.createMultiple(30, 'god');
		gods.setAll('anchor.x', 0.5);
		gods.setAll('anchor.y', 0.5);
		gods.forEach(function(god){
			god.animations.add('god');
		}); 
		
		lambs = game.add.group();                      
		lambs.enableBody = true;
		lambs.physicsBodyType = Phaser.Physics.ARCADE;
		lambs.createMultiple(30, 'lamb');
		lambs.setAll('anchor.x', 0.5);
		lambs.setAll('anchor.y', 0.5);
		lambs.forEach(function(lamb){
			lamb.animations.add('lamb');
		}); 

		musics = game.add.group(); 
		musics.enableBody = true;
		musics.physicsBodyType = Phaser.Physics.ARCADE;
		musics.createMultiple(30, 'musicoff');
		musics.setAll('anchor.x', 0.5);
		musics.setAll('anchor.y', 0.5);
		musics.forEach(function(musicoff){
			musicoff.animations.add('musicoff');
		}); 
		
		shields = game.add.group();                      
		shields.enableBody = true;
		shields.physicsBodyType = Phaser.Physics.ARCADE;
		shields.createMultiple(30, 'shield');
		shields.setAll('anchor.x', 0.5);
		shields.setAll('anchor.y', 0.5);
		shields.forEach(function(shield){
			shield.animations.add('shield');
		}); 
		
		Chests = game.add.group();                      
		Chests.enableBody = true;
		Chests.physicsBodyType = Phaser.Physics.ARCADE;
		Chests.createMultiple(30, 'chest');
		Chests.setAll('anchor.x', 0.5);
		Chests.setAll('anchor.y', 0.5);
		Chests.forEach(function(chest){
			chest.animations.add('chest');
		}); 
		
		HealChests = game.add.group();                      
		HealChests.enableBody = true;
		HealChests.physicsBodyType = Phaser.Physics.ARCADE;
		HealChests.createMultiple(30, 'HealChest');
		HealChests.setAll('anchor.x', 0.5);
		HealChests.setAll('anchor.y', 0.5);
		HealChests.forEach(function(HealChest){
			HealChest.animations.add('HealChest');
		}); 
		
		var shield = shields.getFirstExists(false);
		shield.reset(745, game.height - 20);
		
		boss1lasers = game.add.group();                       // обработка события столкновения
		boss1lasers.enableBody = true;
		boss1lasers.physicsBodyType = Phaser.Physics.ARCADE;
		boss1lasers.createMultiple(30, 'boss1laser');
		boss1lasers.setAll('anchor.x', 0.5);
		boss1lasers.setAll('anchor.y', 0.5);
		boss1lasers.forEach(function(laser){
			laser.animations.add('laser');
		});
			
		/*game.time.events.add(500, launchEnemy1);
		game.time.events.add(1500, launchEnemy2);
		game.time.events.add(2500, launchEnemy1);
		game.time.events.add(4000, launchEnemy1);
		game.time.events.add(6500, launchEnemy1);
		game.time.events.add(7500, launchEnemy1);
		game.time.events.add(8000, launchEnemy2);
		game.time.events.add(9500, launchEnemy1);
		game.time.events.add(14500, launchEnemy1);
		game.time.events.add(15000, launchEnemy2);
		game.time.events.add(17000, launchEnemy1);
		game.time.events.add(19500, launchEnemy1);
		game.time.events.add(20000, launchEnemy3);	
		game.time.events.add(21500, launchEnemy1);		
		game.time.events.add(25000, launchEnemy2);	
		game.time.events.add(26000, launchEnemy1);  
		game.time.events.add(29000, launchEnemy5);*/
	}
	
	function update() {
		if (game.time.now > ComboVisible){
			if (ComboText){
				ComboText.visible = false;
			}
		}
		if (Level === 2){
			Level = 2.5;
			game.time.events.add(2500, launchEnemy4);
			game.time.events.add(2500, launchEnemy1);
			game.time.events.add(4000, launchEnemy4);
			game.time.events.add(4500, launchEnemy1);
			game.time.events.add(5000, launchEnemy4);
			game.time.events.add(5500, launchEnemy1);
			game.time.events.add(6000, launchEnemy2);
			game.time.events.add(6500, launchEnemy1);
			game.time.events.add(12000, launchEnemy3);
			game.time.events.add(12000, launchEnemy1);
			game.time.events.add(14500, launchEnemy3);
			game.time.events.add(14700, launchEnemy1);
			game.time.events.add(17000, launchEnemy1);
			game.time.events.add(17000, launchEnemy2);
			game.time.events.add(18500, launchEnemy1);
			game.time.events.add(20000, launchEnemy2);
			game.time.events.add(21000, launchEnemy1);
			game.time.events.add(23000, launchEnemy2);
			game.time.events.add(24000, launchEnemy1);
			game.time.events.add(25500, launchEnemy6);
			game.time.events.add(26000, launchEnemy6);
			game.time.events.add(26500, launchEnemy6);
			game.time.events.add(26500, launchEnemy1);
			game.time.events.add(28000, launchEnemy1);
			game.time.events.add(30500, launchEnemy1);
			game.time.events.add(32000, launchEnemy1);
			game.time.events.add(34000, launchEnemy2);
			game.time.events.add(35500, launchEnemy1);
			game.time.events.add(36500, launchEnemy4);
			game.time.events.add(36600, launchEnemy3);
			game.time.events.add(37000, launchEnemy6);
			game.time.events.add(37500, launchEnemy6);
			game.time.events.add(38000, launchEnemy6); 
			game.time.events.add(47000, launchEnemy5); 
			game.time.events.add(62000, launchEnemy2); 
			game.time.events.add(72000, launchEnemy7); 
		}
		if (Level === 3){
			// 163% score 1980 kill 61 | +1 +30 +105 245 | 2 lvl 
			game.time.events.add(100, launchChest);
			game.time.events.add(2500, launchEnemy1);
			game.time.events.add(3500, launchEnemy2);
			game.time.events.add(4500, launchEnemy1);
			game.time.events.add(5000, launchEnemy1);
			game.time.events.add(5500, launchEnemy2);
			game.time.events.add(7000, launchEnemy1);
			game.time.events.add(9000, launchEnemy1);
			game.time.events.add(9500, launchEnemy2);
			game.time.events.add(9500, launchEnemy1);
			game.time.events.add(10000, launchEnemy1);
			game.time.events.add(11000, launchEnemy2);
			game.time.events.add(12000, launchEnemy8);
			game.time.events.add(14000, launchEnemy8);
			game.time.events.add(16000, launchEnemy8);
			game.time.events.add(17000, launchEnemy1);
			game.time.events.add(17000, launchEnemy6);
			game.time.events.add(18000, launchEnemy6);
			game.time.events.add(18000, launchEnemy8);
			game.time.events.add(18000, launchEnemy1);
			game.time.events.add(19000, launchEnemy6);
			game.time.events.add(22000, launchEnemy3);
			game.time.events.add(24000, launchEnemy8);
			game.time.events.add(26000, launchEnemy8);
			game.time.events.add(28500, launchEnemy2);
			game.time.events.add(30000, launchEnemy1);
			game.time.events.add(32000, launchEnemy4);
			game.time.events.add(34500, launchEnemy4);
			game.time.events.add(35000, launchEnemy2);
			game.time.events.add(35500, launchEnemy4);
			game.time.events.add(36000, launchEnemy1);
			game.time.events.add(36000, launchEnemy8);
			game.time.events.add(36250, launchEnemy6);
			game.time.events.add(36500, launchEnemy4);
			game.time.events.add(39000, launchEnemy3);
			game.time.events.add(40000, launchEnemy2);
			game.time.events.add(40000, launchEnemy1);
			game.time.events.add(41000, launchEnemy6);
			game.time.events.add(43000, launchEnemy9);
			game.time.events.add(44000, launchEnemy8);
			game.time.events.add(45000, launchEnemy1);
			game.time.events.add(46000, launchEnemy1);
			game.time.events.add(46000, launchEnemy8);
			game.time.events.add(49000, launchEnemy9);
			game.time.events.add(50000, launchChest);
			game.time.events.add(52000, launchEnemy2);
			game.time.events.add(55000, launchEnemy2);
			game.time.events.add(56000, launchEnemy1);
			game.time.events.add(58000, launchEnemy9);
			game.time.events.add(59000, launchEnemy3);
			game.time.events.add(60000, launchEnemy8);
			game.time.events.add(63000, launchEnemy3);
			game.time.events.add(65000, launchEnemy8);
			game.time.events.add(65500, launchEnemy9);
			game.time.events.add(66000, launchEnemy1);
			game.time.events.add(67000, launchEnemy3);
			game.time.events.add(69000, launchEnemy9);
			game.time.events.add(70000, launchEnemy8);
			for (i = 0; i < 10; i++){
				game.time.events.add(71000, launchEnemy6);
			}
			game.time.events.add(79000, launchEnemy1);
			game.time.events.add(81000, launchEnemy8);
			game.time.events.add(83000, launchEnemy9);
			game.time.events.add(84500, launchEnemy8);
			game.time.events.add(85000, launchEnemy1);
			game.time.events.add(87000, launchEnemy6);
			game.time.events.add(87500, launchEnemy8);
			game.time.events.add(89000, launchEnemy2);
			game.time.events.add(90000, launchEnemy1);
			game.time.events.add(90000, launchEnemy9); 
			game.time.events.add(100000, launchChest);
			game.time.events.add(105000, launchEnemy10);
			Level = 3.5;
		}	
		if (Level === 4){
			Level = 4.5;	
		}
			
		if (player.alive){
			field.tilePosition.y += 0.1;    // "передвижение" фона при жизни игркока
		}			                        // "передвижение" фона
		
	    player.body.velocity.setTo(0, 0);	
		
		if (game.input.keyboard.addKey(Phaser.Keyboard.A).isDown){ 
			player.body.velocity.x = -200 - SpeedChar;
		}  
		if (game.input.keyboard.addKey(Phaser.Keyboard.D).isDown){
			player.body.velocity.x = 200 + SpeedChar;
		} 	
		if (game.input.keyboard.addKey(Phaser.Keyboard.W).isDown){
			player.body.velocity.y = -200 - SpeedChar;
		} 
		if (game.input.keyboard.addKey(Phaser.Keyboard.S).isDown){
			player.body.velocity.y = 200 + SpeedChar;
		} 
		
		if (player.x > game.width - 30){  // запрет на выход за границы экрана
			player.x = game.width - 30;
			player.body.acceleration.x = 0;
		}
		if (player.x < 30){
			player.x = 30;
			player.body.acceleration.x = 0;
		}
		if (player.y > game.height - 30){  
			player.y = game.height - 30;
			player.body.acceleration.y = 0;
		}
		if (player.y < 30){
			player.y = 30;
			player.body.acceleration.y = 0;
		}
		
		if (player.alive && game.input.activePointer.isDown){  // при нажатии пробела			
			fireBullet();
    	}
		
		var god = gods.getFirstExists(false);
		if (OP > 0 && player.alive && game.input.keyboard.addKey(Phaser.Keyboard.SPACEBAR).isDown && Invisible === 0){  // при нажатии пробела
			OP -= 1;
			OPText.render();
			Invisible = 1;
			InvisibleTime = game.time.now + 2000;
			god.reset(player.x, player.y + 5);
    	}
		
		god.update = function(){
			if (Invisible === 1){
				god.x = player.x;
				god.y = player.y;
			}
			else
			{
				god.visible = false;
			}
			if (!player.alive){
				god.visible = false;
			}
		}
		
		var musicoff = musics.getFirstExists(false);
		if (game.time.now > music && game.input.keyboard.addKey(Phaser.Keyboard.M).isDown){
			if (wpn1.volume === 0.1){
				wpn1.volume = 0;
				wpn5.volume = 0;
				Monster.volume = 0;
				UltraCombo.volume = 0;
				ULTRA.volume = 0;
				musicoff.reset(game.width - 40, 40);
			}
			else {
				wpn1.volume = 0.1;
				wpn5.volume = 0.1;
				Monster.volume = 1;
				UltraCombo.volume = 1;
				ULTRA.volume = 1;
			}
			music = game.time.now + 200;
		}
		
		musicoff.update = function(){
			if (wpn1.volume === 0.1){
				musicoff.visible = false;
			}
		}
		
		if (game.time.now > InvisibleTime && Invisible === 1){
			Invisible = 0;
		}
		
		if (game.input.keyboard.addKey(Phaser.Keyboard.R).isDown){
			score = 0;
			Evil = 0;
			OP = 1;
			PowerChar = 0;   
			SpeedChar = 200; 
			BulletSpeedChar = 0;
			BulletSpacingChar = 0;
			MaxCombo = 0;
			Level = 1;
			if (!player.weaponLevel === 5){
				player.weaponLevel = 1;
			}
			game.state.start(game.state.current);
		}
		
		if (game.input.keyboard.addKey(Phaser.Keyboard.C).isDown){
			game.state.start(game.state.start('Level1'));
		}
		
		if (game.time.now > statistic && game.input.keyboard.addKey(Phaser.Keyboard.TAB).isDown){
			if (!CharPowerText.visible === true){
				CharPowerText.render();
				CharBulletSpeedText.render();
				CharReloadingText.render();
				CharSpeedText.render();
				MaxComboShow.render();
			
				CharPowerText.visible = true;
				CharBulletSpeedText.visible = true;
				CharReloadingText.visible = true;
				CharSpeedText.visible = true;
				MaxComboShow.visible = true;
			} else {
				CharPowerText.visible = false;
				CharBulletSpeedText.visible = false;
				CharReloadingText.visible = false;
				CharSpeedText.visible = false;
				MaxComboShow.visible = false;
			} 
			statistic = game.time.now + 200;
		}
		
		effect.x = player.x + 4;   // передвижение эффекта сзади коробля
		effect.y = player.y + 35;
	 	
		game.physics.arcade.overlap(Enemy2evilBullets, player, enemyHitsPlayer, null, this); // попадание в игрока		
		game.physics.arcade.overlap(Enemy6evilBullets, player, enemyHitsPlayer, null, this);
		game.physics.arcade.overlap(Enemy1, brimstone, hitEnemy, null, this); // попадание в игрока лазером
		game.physics.arcade.overlap(Enemy2, brimstone, hitEnemy, null, this); 
		game.physics.arcade.overlap(Enemy3, brimstone, hitEnemy, null, this); 
		game.physics.arcade.overlap(Enemy4, brimstone, hitEnemy, null, this); 
		game.physics.arcade.overlap(Enemy5, brimstone, hitEnemy, null, this); 
		game.physics.arcade.overlap(Enemy6, brimstone, hitEnemy, null, this); 
		game.physics.arcade.overlap(Enemy7, brimstone, hitEnemy, null, this); 
		game.physics.arcade.overlap(Enemy8, brimstone, hitEnemy, null, this); 
		game.physics.arcade.overlap(Enemy9, brimstone, hitEnemy, null, this); 
		game.physics.arcade.overlap(Enemy10, brimstone, hitEnemy, null, this); 
		game.physics.arcade.overlap(Chests, brimstone, hitEnemy, null, this); 
		game.physics.arcade.overlap(HealChests, brimstone, hitEnemy, null, this); 
		game.physics.arcade.overlap(Enemy1, bullets, hitEnemy, null, this);   // попадание во врага
		game.physics.arcade.overlap(Enemy2, bullets, hitEnemy, null, this);	
		game.physics.arcade.overlap(Enemy3, bullets, hitEnemy, null, this);	
		game.physics.arcade.overlap(Enemy4, bullets, hitEnemy, null, this);		
		game.physics.arcade.overlap(Enemy5, bullets, hitEnemy, null, this);		
		game.physics.arcade.overlap(Enemy6, bullets, hitEnemy, null, this);		
		game.physics.arcade.overlap(Enemy7, bullets, hitEnemy, null, this);		
		game.physics.arcade.overlap(Enemy8, bullets, hitEnemy, null, this);
		game.physics.arcade.overlap(Enemy9, bullets, hitEnemy, null, this);	
		game.physics.arcade.overlap(Enemy10, bullets, hitEnemy, null, this);			
		game.physics.arcade.overlap(Chests, bullets, hitEnemy, null, this);	
		game.physics.arcade.overlap(HealChests, bullets, hitEnemy, null, this);			
		game.physics.arcade.overlap(player, Enemy1, shipCollide, null, this); // столкновение
		game.physics.arcade.overlap(player, Enemy2, shipCollide, null, this);
		game.physics.arcade.overlap(player, Enemy3, shipCollide, null, this);
		game.physics.arcade.overlap(player, Enemy4, shipCollide, null, this);
		game.physics.arcade.overlap(player, Enemy5, shipCollide, null, this);		
		game.physics.arcade.overlap(player, Enemy6, shipCollide, null, this);	
		game.physics.arcade.overlap(player, Enemy7, shipCollide, null, this);	
		game.physics.arcade.overlap(player, Enemy8, shipCollide, null, this);
		game.physics.arcade.overlap(player, Enemy9, shipCollide, null, this);	
		game.physics.arcade.overlap(player, Enemy10, shipCollide, null, this);	
		game.physics.arcade.overlap(player, Chests, shipCollide, null, this);		
		game.physics.arcade.overlap(player, HealChests, shipCollide, null, this);	
		game.physics.arcade.overlap(player, Bonus1, shipCollide, null, this);	
		game.physics.arcade.overlap(player, Bonus2, shipCollide, null, this);	
		game.physics.arcade.overlap(player, Bonus3, shipCollide, null, this);
		game.physics.arcade.overlap(player, Bonus4, shipCollide, null, this);	
		game.physics.arcade.overlap(player, Bonus5, shipCollide, null, this);
		game.physics.arcade.overlap(player, Bonus6, shipCollide, null, this);	  
	
		if (!player.alive){            // когда игрок умирает
			if (GameEnd === 0){
				field.tilePosition.y += 0; 
				effect.kill();
				gameOverText = game.add.text(game.world.centerX, game.world.centerY, 'GAME OVER!', { font: '84px Arial', fill: '#fff' });
				gameOverText.anchor.setTo(0.5, 0.5);
				button = game.add.button(game.world.centerX-175, game.world.centerY+50, 'button', function() {    
				window.open("http://localhost/laravel/shop/public/picture", "_blank");}, this);
				GameEnd = 1;
			}
		//	gameover.play;
		//	if ( music.volume > 0.1) {
		//		music.volume -= 0.1;
		//	}		
		}
	}
	
	function fireBullet() {                               // событие "Выстрел"
		switch (player.weaponLevel) {
        	case 1:
        	if (game.time.now > bulletTimer){
				var bullet = bullets.getFirstExists(false);
				if (bullet){
					var BulletSpacing = 300 - BulletSpacingChar;
					var bulletSpeed = 300 + BulletSpeedChar;
					power = 1 + PowerChar;
					bullet.reset(player.x + 21, player.y);
					bullet.angle = player.angle;
					bulletTimer = game.time.now + BulletSpacing;
					game.physics.arcade.velocityFromAngle(bullet.angle - 90, bulletSpeed, bullet.body.velocity); 	
					wpn1.play(); 
				}
			}
			break;
			
			case 2:
			if (game.time.now > bulletTimer) {
				for (var i = 0; i < 2; i++) {
					var bullet = bullets.getFirstExists(false);
					if (bullet) {
						var bulletOffset = 20 * Math.sin(game.math.degToRad(player.angle));
						var bulletSpacing = 315 - BulletSpacingChar;
						var bulletSpeed = 315 + BulletSpeedChar;
						var spreadAngle;
						power = 1 + PowerChar;
						bullet.reset(player.x + 21, player.y);						
						if (i === 0) spreadAngle = -10;
						if (i === 1) spreadAngle = 10;
						bullet.angle = player.angle + spreadAngle;
						bulletTimer = game.time.now + bulletSpacing;
						game.physics.arcade.velocityFromAngle(spreadAngle - 90, bulletSpeed, bullet.body.velocity);
					}
				}
				wpn1.play();
			}
			break;
			
			case 3:
			if (game.time.now > bulletTimer){
				for (var i = 0; i < 3; i++) {
					var bullet = bullets.getFirstExists(false);
					if (bullet){
						var BulletSpacing = 330 - BulletSpacingChar;
						var bulletSpeed = 330 + BulletSpeedChar;
						power = 1 + PowerChar;
						bullet.reset(player.x + 21, player.y);
						if (i === 0) spreadAngle = -10;
						if (i === 1) spreadAngle = 0;
						if (i === 2) spreadAngle = 10;
						bullet.angle = player.angle + spreadAngle;
						bulletTimer = game.time.now + BulletSpacing;
						game.physics.arcade.velocityFromAngle(bullet.angle - 90, bulletSpeed, bullet.body.velocity); 			
					}
				}
				wpn1.play();
			}
			break;
			
			case 4:
			if (game.time.now > bulletTimer) {
				for (var i = 0; i < 4; i++) {
					var bullet = bullets.getFirstExists(false);
					if (bullet) {
						var bulletOffset = 20 * Math.sin(game.math.degToRad(player.angle));
						var BulletSpacing = 345 - BulletSpacingChar;
						var bulletSpeed = 345 + BulletSpeedChar;
						var spreadAngle;
						power = 1 + PowerChar;
						bullet.reset(player.x + 21, player.y);
						if (i === 0) spreadAngle = -10;
						if (i === 1) spreadAngle = 10;
						if (i === 2) spreadAngle = 45;
						if (i === 3) spreadAngle = -45;
						bullet.angle = player.angle + spreadAngle;
						bulletTimer = game.time.now + BulletSpacing;
						game.physics.arcade.velocityFromAngle(spreadAngle - 90, bulletSpeed, bullet.body.velocity);
					}
				}
				wpn1.play();
			}
			break;
			
			case 5: 
			{
				var bullet = brimstone.getFirstExists(false);
				if (bullet) {
					var BulletSpacing = 1;
					var bulletSpeed = 2000 + BulletSpeedChar;
					power = 0.2;
					bullet.reset(player.x + 8, player.y);
					bullet.angle = player.angle;
					game.physics.arcade.velocityFromAngle(bullet.angle - 90, bulletSpeed, bullet.body.velocity);
					wpn5.play(); 
				}
			}				
			break;
		}
	}
	
	function launchEnemy1() {                             // астероиды      
		var speedEnemy1 = 40 + DifficultySpeed + LevelSpeed;
		var point = 10;
		var hp = 5;
		var enemy = Enemy1.getFirstExists(false);
		if (!player.alive){
			return;
		}
		if (enemy) {
			enemy.score = point;
			enemy.hp = hp;
			enemy.damageAmount = 0;
			enemy.collide = 30;
			enemy.reset(game.rnd.integerInRange(0, game.width), -20);
			enemy.body.velocity.x = game.rnd.integerInRange(-250, 250);
			enemy.body.velocity.y = speedEnemy1;
			enemy.body.drag.x = 100;  
			enemy.update = function(){	
				if (this.y > game.height + 200) {  // убийство за экраном
					this.kill();
				}
			}
		}
	}
	
	function launchEnemy2() {                             // корабли
		if (!player.alive){
			return;
		}
		var startingX = game.rnd.integerInRange(100, game.width - 100);
		var verticalSpeed = 200 + DifficultySpeed + LevelSpeed;
		var spread = 60;
		var point = 25;
		var hp = 2;
		var frequency = 70;
		var verticalSpacing = 70;
		var numEnemiesInWave = 5;
		var timeBetweenWaves = 7000;  // 7000
		for (var i = 0; i < numEnemiesInWave; i++) {
			var enemy = Enemy2.getFirstExists(false);
			if (enemy) { 
				if (!player.alive){
					return;
				}
				enemy.damageAmount = 10;
				enemy.hp = hp;
				enemy.collide = 20;
			    enemy.score = point;  // ОЧКИ ЗА ВРАГА
				enemy.startingX = player.x; 
				enemy.reset(game.width / 2, -verticalSpacing * i);
				enemy.body.velocity.y = verticalSpeed;
				var bulletSpeed = 500;                   // характеристики пуль второго врага
            	var firingDelay = 2000;
            	enemy.bullets = 10;
            	enemy.lastShot = 0;
				enemy.update = function(){	
					this.body.x = this.startingX + Math.sin((this.y) / frequency) * spread; //  Передвижение
					if (this.y > game.height + 200) {  // убийство за экраном
						this.kill();
					}
					enemyBullet = Enemy2evilBullets.getFirstExists(false);         // описание полета вражеской пули
					if (enemyBullet && this.alive && this.bullets 
					&& this.y > game.width / 8 && game.time.now > firingDelay + this.lastShot) {
						this.lastShot = game.time.now;
						this.bullets--;
						enemyBullet.reset(this.x, this.y + this.height / 2);
						enemyBullet.damageAmount = this.damageAmount;
						var angle = game.physics.arcade.moveToObject(enemyBullet, player, bulletSpeed);
						enemyBullet.angle = game.math.radToDeg(angle); 
					} 
				}
			}
		}
	}
	
	function launchEnemy3() {                             // самоубийца-копия    
		if (!player.alive){
			return;
		}
		var speedEnemy3 = 100 + DifficultySpeed + LevelSpeed;
		var point = 50;
		var hp = 7;
		var enemy = Enemy3.getFirstExists(false);
		if (enemy) {
			enemy.score = point;
			enemy.damageAmount = 0;
			enemy.hp = hp;
			enemy.collide = 35;
			enemy.reset(400, -20);
			enemy.body.velocity.y = speedEnemy3;
			enemy.update = function(){	
				if (!player.alive){
					return;
				}
				this.body.x = player.body.x + 5; 
				if (this.y > game.height + 200) {  
					this.kill();
				}
			}
		}
	}
	
	function launchEnemy4() {                             // лазерщик 
		if (!player.alive){
			return;
		}    
		var speedEnemy4 = 100 + DifficultySpeed + LevelSpeed;
		var point = 50;
		var enemyhp = 3;
		var left = game.rnd.integerInRange(0,1);
		var enemy = Enemy4.getFirstExists(false);
		if (enemy) {
			enemy.score = point;
			enemy.damageAmount = 1.5;
			enemy.collide = 25;
			enemy.hp = enemyhp;
			enemy.reset(game.rnd.integerInRange(0, game.width), -20);
			enemy.body.velocity.y = speedEnemy4;
			var laser = lasers.getFirstExists(false);
			enemy.update = function(){
				if (left === 1){
					this.x -= 2;
				}
				else {
					this.x += 2;
				}
				if (this.x < 25){
					left = 0;
				}
				if (this.x > 775){
					left = 1;
				}
				if (this.alive && this.x > player.x - 10 && this.x < player.x + 10 && this.y < player.y && Invisible === 0) {
					if (laser) {
						laser.reset(this.body.x + 12, this.body.y + 350);
					}
					player.damage(this.damageAmount);
					if (ComboText){
						ComboText.visible = false;
					}
					Combo = 0;
					hp.render();	
				}
				else {	
					laser.visible = false;
				}		
				if (this.y > game.height) {  // убийство за экраном
					this.kill();
				}	
				if (!this.alive) {
					laser.kill();
				}     				
			}
		} 
	}
	
	function launchEnemy5() {                             // босс 1
		if (!player.alive){
			return;
		} 
		var speedEnemy4 = 100 + DifficultySpeed + LevelSpeed;
		if (Level === 1){
			var point = 250;
			var enemyhp = 25;
		} else {
			var point = 35;
			var enemyhp = 7;
		}
		var enemy = Enemy5.getFirstExists(false);
		if (enemy) {
			enemy.score = point;
			if (Level === 1){
				enemy.damageAmount = 3;
				enemy.collide = 1000;	
			} else {
				enemy.damageAmount = 1;
				enemy.collide = 1000;
			}
			enemy.hp = enemyhp;
			enemy.reset(400, -20);
			enemy.body.velocity.y = speedEnemy4;
			var bulletTimer = 0;
			var laserTimer = 5000;
			var laser = boss1lasers.getFirstExists(false);
			if (Level === 1){
				laser.damageAmount = 1;
			} else {
				laser.damageAmount = 0.5;
			}
			var laserShoot = 0;
			enemy.update = function(){	
				if (!player.alive){
					if (laser){
						laser.kill();
					}
					enemy.body.velocity.y = -40;
					if (this.y > game.height + 200) {  
						this.kill();
					}
					return;
				}
				if (game.time.now > laserTimer) {	
					laserShoot += 1;
					if (laserShoot === 25) {
						laserShoot = 0;
						laserTimer = game.time.now + 3000;
						laser.visible = false;
						return;
					}
					if (this.alive && this.x > player.x - 10 && this.x < player.x + 10 && this.y < player.y && Invisible === 0) {
						if (laser) {
							laser.reset(this.body.x + 12, this.body.y + 350);
						}
						if (ComboText){
							ComboText.visible = false;
						}
						Combo = 0;
						player.damage(laser.damageAmount);
						hp.render();	
					}
					else {	
						laser.visible = false;
					}	
					if (!enemy.alive){
						laser.kill();
					}	
				}
				
				if (game.time.now > bulletTimer) {					
					for (var i = 0; i < 2; i++) {						
						var boss1bullet = Enemy2evilBullets.getFirstExists(false);
						if (boss1bullet) {
							if (Level === 1){
								boss1bullet.damageAmount = 10;
							} else {
								boss1bullet.damageAmount = 3;
							}
							var bulletOffset = 20 * Math.sin(game.math.degToRad(enemy.angle));
							var spreadAngle;
							boss1bullet.reset(enemy.x, enemy.y);						
							if (i === 0) spreadAngle = -10;
							if (i === 1) spreadAngle = 10;
							boss1bullet.angle = enemy.angle + spreadAngle;
							bulletTimer = game.time.now + 700;
							if (!enemy.alive){
								boss1bullet.kill();
							}	
							game.physics.arcade.velocityFromAngle(spreadAngle - 270, 300, boss1bullet.body.velocity);	
						}
					}	
				}
				
				if (enemy.hp > 10){
					if (enemy.y > 50){
						enemy.body.velocity.y = 0;
					}
					if (enemy.x < player.x){
						enemy.x += 4; 
					}
					if (enemy.x > player.x){
						enemy.x -= 4;
					}
					if (enemy.x === player.x){
						enemy.x += 0;
					}
				}
				
				if (enemy.hp < 10 && enemy.y < player.x)
				{
					enemy.body.velocity.y = 20;
					if (enemy.x < player.x){
						enemy.x += 4; 
					}
					if (enemy.x > player.x){
						enemy.x -= 4;
					}
					if (enemy.x === player.x){
						enemy.x += 0;
					}
				}
				
				if (enemy.hp < 10 && enemy.y > player.x)
				{
					enemy.body.velocity.y = -300;
					if (enemy.x < player.x){
						enemy.x += 4; 
					}
					if (enemy.x > player.x){
						enemy.x -= 4;
					}
					if (enemy.x === player.x){
						enemy.x += 0;
					}
				}
			}
		} 
	}
	
	function launchEnemy6() {                             // пуляки-бяки
		if (!player.alive){
			return;
		} 
		var point = 30;
		var hp = 10;
		var left = game.rnd.integerInRange(0, 1);
		var stop = game.rnd.integerInRange(40, 300);
		var enemy = Enemy6.getFirstExists(false);
		var reloading = 150;
		if (enemy) {	
			enemy.score = point;
			enemy.hp = hp;
			enemy.damageAmount = 10;
			enemy.collide = 25;
			enemy.reset(player.x, -20);
			enemy.body.velocity.y = 150;
			enemy.update = function(){	
				if (left === 1){
					this.x -= 1;
				}
				else {
					this.x += 1;
				}
				if (this.x < 25){
					left = 0;
				}
				if (this.x > 790){
					left = 1;
				}
				if (this.y > game.height + 200) {  // убийство за экраном
					this.kill();
				}
				if (this.y > stop){
					this.body.velocity.y = 1;
				}
				enemyBullet = Enemy6evilBullets.getFirstExists(false);
				if (game.time.now > reloading && this.alive){
					if (enemyBullet){
						enemyBullet.damageAmount = this.damageAmount;
						enemyBullet.reset(this.x, this.y);
						enemyBullet.body.velocity.y = 250;
						reloading = game.time.now + 800;
					}
				}
			}
		}
	}
	
	function launchEnemy7() {                             // босс 2
		if (!player.alive){
			return;
		} 
		var point = 300;
		var hp = 50;
		var left = 1;
		var stop = 20;
		var stopsecond = 0;
		var enemy = Enemy7.getFirstExists(false);
		var reloading = 150;
		var longreloaing = 0;
		var godform = 1;
		if (enemy) {	
			enemy.score = point;
			enemy.hp = hp;
			enemy.damageAmount = 5;
			enemy.collide = 1000;
			enemy.reset(game.width + 40, -20);
			enemy.body.velocity.y = 150;
			enemy.update = function(){	
				if (!player.alive){
					enemy.body.velocity.y = -40;
					if (this.y > game.height + 200) {  
						this.kill();
					}
					return;
				}
				if (left === 1){
					if (enemy.hp >= 40){
						this.x -= 2;
					}
					if (enemy.hp < 40 && enemy.hp > 30){
						this.x -= 3;
					}
					if (enemy.hp <= 30 && enemy.hp > 20){
						this.x -= 4;
					}
					if (enemy.hp <= 20){
						this.x -= 0;
					}
				}
				else {
					if (enemy.hp >= 40){
						this.x += 2;
					}
					if (enemy.hp < 40 && enemy.hp > 30){
						this.x += 3;
					}
					if (enemy.hp <= 30 && enemy.hp > 20){
						this.x += 4;
					}
					if (enemy.hp <= 20){
						this.x -= 0;
					}
				}
				if (this.x < 25){
					left = 0;
				}
				if (this.x > 790){
					left = 1;
				}
				if (this.y > game.height + 200) {  // убийство за экраном
					this.kill();
				}
				if (this.y > stop){
					this.body.velocity.y = 1;
				}
				
				var enemyBullet = Enemy6evilBullets.getFirstExists(false);				
				if (enemy.hp > 20){
					if (game.time.now > reloading && this.alive){
						if (enemyBullet){
							enemyBullet.damageAmount = this.damageAmount;
							enemyBullet.reset(this.x, this.y);
							enemyBullet.body.velocity.y = 250;
							reloading = game.time.now + 50;
							longreloaing += 1;
							if (longreloaing > 50){
								reloading = game.time.now + 600;
								longreloaing = 0;
							}
						}
					}
				} else {
					if (game.time.now > reloading && this.alive){
						if (enemyBullet){
							enemyBullet.damageAmount = this.damageAmount - 3.5;
							enemyBullet.reset(game.rnd.integerInRange(5,795), -10);
							enemyBullet.body.velocity.y = 250;
							reloading = game.time.now + 50;
						}	
					}
				}
			}
		}
	}
	
	function launchEnemy8() {                             // коробко
		if (!player.alive){
			return;
		} 
		var point = 30;
		var hp = 1000;
		var direction = 1;
		var FireBullet = 0;
		var enemy = Enemy8.getFirstExists(false);
		if (enemy) {
			direction = game.rnd.integerInRange(0, 1);	
			enemy.damageAmount = 15;
			enemy.collide = 50;		
			enemy.hp = hp;			
			if (direction === 1){
				enemy.reset(25, - 20);
				enemy.update = function(){
					if (this.alive){
						this.loadTexture('enemy8left', 0);
						if (game.time.now > FireBullet){
							bullet = Enemy8.getFirstExists(false);
							if (bullet){
								bullet.loadTexture('enemy8left', 0);
								bullet.scale.x = 0.4;
								bullet.scale.y = 0.4;
								bullet.hp = 1.5;
								bullet.collide = 15;
								bullet.score = 0;
								bullet.reset(enemy.x + 10, enemy.y);
								bullet.body.velocity.x = 100 + LevelSpeed;
								bullet.update = function(){
									if (bullet){
										if (bullet.x > 820) {  // убийство за экраном
											bullet.kill();
										}
									}
								}
								FireBullet = game.time.now + game.rnd.integerInRange(700, 1500);
							}
						}
						if (this.y > game.height + 200) {  // убийство за экраном
							this.kill();
						}
					}
				}
			} else {
				enemy.reset(775, - 20);
				enemy.update = function(){
					if (this.alive){
						if (game.time.now > FireBullet){
							bullet = Enemy8.getFirstExists(false);
							if (bullet){
								bullet.scale.x = 0.4;
								bullet.scale.y = 0.4;
								bullet.hp = 1;
								bullet.collide = 15;
								bullet.score = 0;
								bullet.reset(enemy.x - 10, enemy.y);
								bullet.body.velocity.x = -100 - LevelSpeed;
								bullet.update = function(){
									if (bullet){
										if (bullet.x < -20) {  // убийство за экраном
											bullet.kill();
										}
									}
								}
								FireBullet = game.time.now + game.rnd.integerInRange(700, 1500);	
							}
						}
					}
					if (this.y > game.height + 200) {  // убийство за экраном
						this.kill();
					}
				}
			}
			enemy.body.velocity.y = 100;
		}
	}
	
	function launchEnemy9() {                             // коробко-пуляка
		if (!player.alive){
			return;
		} 
		var point = 30;
		var hp = 15;
		var FireBullet = 0;
		var Shoot = 0;
		var direction = 1;
		var enemy = Enemy9.getFirstExists(false);
		if (enemy) {
			enemy.damageAmount = 15;
			enemy.collide = 25;	
			enemy.hp = hp;	
			enemy.score = point;	
			enemy.reset(game.rnd.integerInRange(40, game.width - 40), -20);
			enemy.body.velocity.y = 75;
			enemy.update = function(){
				if (this.y > 600){
					direction = 0;
				}
				if (direction === 0){
					enemy.body.velocity.y = -50;
				}
				enemyBullet = Enemy6evilBullets.getFirstExists(false);
				if (game.time.now > Shoot){
					if (enemyBullet && this.alive){
						enemyBullet.reset(this.x, this.y + this.height / 2);
						enemyBullet.damageAmount = this.damageAmount;
						var angle = game.physics.arcade.moveToObject(enemyBullet, player, 200);
						enemyBullet.angle = game.math.radToDeg(angle); 
						Shoot = game.time.now + 1000;
					}
				}
				if (this.y < -50){
					this.kill();
				}
			}		
		}
	}
	
	function launchEnemy10() {                            // босс 3
		if (!player.alive){
			return;
		} 
		var point = 450;
		var hp = 9000;
		var GodMode = 1;
		var Shoot = 0;
		var Shoot2 = 0;
		var teleport = 1;
		var reloading = 0;
		var longreloaing = 0;
		var Victory = 1;
		var enemy = Enemy10.getFirstExists(false);
		if (enemy) {
			enemy.damageAmount = 50;
			enemy.collide = 1000;	
			enemy.hp = hp;	
			enemy.score = point;	
			enemy.reset(400, -75);
			enemy.body.velocity.y = 100;
			var lamb = lambs.getFirstExists(false);
			enemy.update = function(){
				if (!player.alive){
					enemy.body.velocity.y = -10;
					if (lamb){
						lamb.update = function(){ 
							lamb.body.velocity.y = -10;
						}
					}
					this.loadTexture('enemy10', 0);
					return;
				}
				if (this.y > 75){
					enemy.body.velocity.y = 0;
				}
				if (player.x < 300){
					 this.loadTexture('enemy10leftdown', 0);
				} 
				if (player.x > 300 && player.x < 500){
					 this.loadTexture('enemy10', 0);
				} 
				if (player.x > 500){
					 this.loadTexture('enemy10rightdown', 0);
				} 
				if (player.x < 300 && player.y < this.y + 50){
					 this.loadTexture('enemy10left', 0);
				} 
				if (player.x > 500 && player.y < this.y + 50){
					 this.loadTexture('enemy10right', 0);
				} 
				if (this.hp <= 8600 && GodMode === 1){
					var bonusEat = bonuses.getFirstExists(false);
					bonusEat.reset(this.body.x + 77, this.body.y + 15);
					bonusEat.alpha = 0.7;
					bonusEat.play('bonusEating', 30, false, true);
					lamb.reset(this.x, this.y - 75);
					GodMode = 0;
				}
				if (this.body.velocity.y === 0 && this.hp > 8800){ 
					enemyBullet = Enemy6evilBullets.getFirstExists(false);
					if (game.time.now > Shoot){
						if (enemyBullet && this.alive){
							enemyBullet.reset(this.x, this.y - 40);
							enemyBullet.damageAmount = this.damageAmount - 30;
							var angle = game.physics.arcade.moveToObject(enemyBullet, player, 200);
							enemyBullet.angle = game.math.radToDeg(angle); 
							Shoot = game.time.now + 250;
						}
					}
				}
			    if (this.body.velocity.y === 0 && this.hp <= 8800 && this.hp > 8700){
					if (game.time.now > Shoot2){
						game.time.events.add(500, launchEnemy8);
						Shoot2 = game.time.now + 1500;
					}
				} 
				if (this.body.velocity.y === 0 && this.hp <= 8700 && this.hp > 8600){	
					enemyBullet = Enemy6evilBullets.getFirstExists(false);
					if (game.time.now > Shoot){
						if (enemyBullet && this.alive){
							enemyBullet.reset(this.x, this.y - 40);
							enemyBullet.damageAmount = this.damageAmount - 30;
							var angle = game.physics.arcade.moveToObject(enemyBullet, player, 200);
							enemyBullet.angle = game.math.radToDeg(angle); 
							Shoot = game.time.now + 1250;
						}
					}
				}
				if (this.body.velocity.y === 0 && this.hp <= 8600 && this.hp > 8450){
					if (teleport === 1){
						var bonusEat = bonuses.getFirstExists(false);
						bonusEat.reset(player.body.x + 77, player.body.y + 15);
						bonusEat.alpha = 0.7;
						bonusEat.play('bonusEating', 30, false, true);
					}
					player.y = 560;
					if (teleport === 1){
						bonusEat.reset(player.body.x + 77, player.body.y + 15);
						bonusEat.alpha = 0.7;
						bonusEat.play('bonusEating', 30, false, true);
						teleport = 0;
					}
					enemyBullet = Enemy6evilBullets.getFirstExists(false);
					if (game.time.now > Shoot){
						if (enemyBullet && this.alive){
							enemyBullet.reset(this.x, this.y - 40);
							enemyBullet.damageAmount = this.damageAmount - 40;
							var angle = game.physics.arcade.moveToObject(enemyBullet, player, 400);
							enemyBullet.angle = game.math.radToDeg(2); 
							Shoot = game.time.now + 50;
							longreloaing += 1;
							if (longreloaing > 25){
								Shoot = game.time.now + 550;
								longreloaing = 0;
							}
						}			
					}	
				} 
				if (this.body.velocity.y === 0 && this.hp <= 8450){
					enemy.body.velocity.y = -10;
					if (lamb){
						lamb.update = function(){ 
							lamb.body.velocity.y = -25;
						}
					}
				} 
				if (this.y < -100){
					if (Victory === 1){
						Level = 4;
						Victory = 0;
					}
					this.kill();
					lamb.kill();
				}
			}
		}
	}	
		
	function launchChest() {                              // сундук
		if (!player.alive){
			return;
		} 	
		var chest = Chests.getFirstExists(false);
		if (chest){
			chest.score = 5;
			chest.hp = 5;
			chest.collide = 0.5;
			chest.reset(400, -20);
			chest.body.velocity.y = 100;
		}
	}
	
	function launchHealChest() {                          // лечилка
		if (!player.alive){
			return;
		} 	
		var HealChest = HealChests.getFirstExists(false);
		if (HealChest){
			HealChest.score = 5;
			HealChest.hp = 5;
			HealChest.collide = 1;
			HealChest.reset(400, -20);
			HealChest.body.velocity.y = 100;
		}
	}
	
	function launchBonusHealChest() {                     // бонусы из лечилки
		var enemy = Bonus6.getFirstExists(false);
		if (enemy) {
			enemy.damageAmount = -6; 
			enemy.reset(XBonus, YBonus);
			enemy.body.velocity.y = 100;
		} 
		var enemy = Bonus6.getFirstExists(false);
		if (enemy) {
			enemy.damageAmount = -6; 
			enemy.reset(XBonus + 20, YBonus);
			enemy.body.velocity.y = 100;
		} 
		var enemy = Bonus6.getFirstExists(false);
		if (enemy) {
			enemy.damageAmount = -6; 
			enemy.reset(XBonus + 40, YBonus);
			enemy.body.velocity.y = 100;
		} 
		var enemy = Bonus6.getFirstExists(false);
		if (enemy) {
			enemy.damageAmount = -6; 
			enemy.reset(XBonus, YBonus + 20);
			enemy.body.velocity.y = 100;
		} 
		var enemy = Bonus6.getFirstExists(false);
		if (enemy) {
			enemy.damageAmount = -6; 
			enemy.reset(XBonus + 20, YBonus + 20);
			enemy.body.velocity.y = 100;
		} 		
		var enemy = Bonus6.getFirstExists(false);
		if (enemy) {
			enemy.damageAmount = -6; 
			enemy.reset(XBonus + 40, YBonus + 20);
			enemy.body.velocity.y = 100;
		} 	
	}
	
	function launchBonusChest() {                         // бонусы из сундука
		var enemy = Bonus2.getFirstExists(false);
		if (enemy) {
			enemy.damageAmount = -2;	
			enemy.reset(XBonus, YBonus);
			enemy.body.velocity.y = 100;
		} 		
		var enemy = Bonus1.getFirstExists(false);
		if (enemy) {
			enemy.damageAmount = -1;
			enemy.reset(XBonus + 20, YBonus);
			enemy.body.velocity.y = 100;
		} 				
		var enemy = Bonus3.getFirstExists(false);	
		if (enemy) {
			enemy.damageAmount = -3;
			enemy.reset(XBonus + 40, YBonus);
			enemy.body.velocity.y = 100;
		} 			
		var enemy = Bonus4.getFirstExists(false);	
		if (enemy) {
			enemy.damageAmount = -4;
			enemy.reset(XBonus, YBonus + 20);
			enemy.body.velocity.y = 100;
		} 	
		var enemy = Bonus5.getFirstExists(false);	
		if (enemy) {
			enemy.damageAmount = -5;	
			enemy.reset(XBonus + 20, YBonus + 20);
			enemy.body.velocity.y = 100;
		} 				
		var enemy = Bonus6.getFirstExists(false);
		if (enemy) {
			enemy.damageAmount = -6; 
			enemy.reset(XBonus + 40, YBonus + 20);
			enemy.body.velocity.y = 100;
		} 	
	}
	
	function launchBonus() {	                          // бонус
		var BonusType = game.rnd.integerInRange(0,5);
		if (BonusType === 0) {  // BulletSpeed
			var enemy = Bonus2.getFirstExists(false);
			enemy.damageAmount = -2;	
		}
		if (BonusType === 1) {   // SpeedChar
			var enemy = Bonus1.getFirstExists(false);
			enemy.damageAmount = -1;
		}		
		if (BonusType === 2) {   
			var enemy = Bonus3.getFirstExists(false);
			enemy.damageAmount = -3;
		}		
		if (BonusType === 3) {   
			var enemy = Bonus4.getFirstExists(false);
			enemy.damageAmount = -4;
		}	
		if (BonusType === 4) {  
			var enemy = Bonus5.getFirstExists(false);
			enemy.damageAmount = -5;
		}	
		if (BonusType === 5) {  
			var enemy = Bonus6.getFirstExists(false);
			enemy.damageAmount = -6;
		}			
		if (enemy) {
			enemy.reset(XBonus, YBonus);
			enemy.body.velocity.y = 100;
			enemy.body.x = XBonus;
			enemy.body.y = YBonus;
		} 
	}
	
 	function shipCollide(player, enemy) {                 // событие "Столкновение"
		if (enemy.collide === 1000){
			var explosion = explosions.getFirstExists(false);
			explosion.reset(player.body.x + player.body.halfWidth, player.body.y + player.body.halfHeight);
			explosion.body.velocity.y = enemy.body.velocity.y;
			explosion.alpha = 0.7;
			explosion.play('explosion', 30, false, true);	
			player.kill();
			return;	
		}
		if (enemy.damageAmount === -1){
			SpeedChar = SpeedChar + 15;
			CharSpeedText.render();
			var bonusEat = bonuses.getFirstExists(false);
			bonusEat.reset(player.body.x + player.body.halfWidth, player.body.y + player.body.halfHeight);
			bonusEat.alpha = 0.7;
			bonusEat.play('bonusEating', 30, false, true);
			enemy.kill(); 
			BonusType = 0;
			return;
		}		
		if (enemy.damageAmount === -2){
			BulletSpeedChar = BulletSpeedChar + 15;
			CharBulletSpeedText.render();
			var bonusEat = bonuses.getFirstExists(false);
			bonusEat.reset(player.body.x + player.body.halfWidth, player.body.y + player.body.halfHeight);
			bonusEat.alpha = 0.7;
			bonusEat.play('bonusEating', 30, false, true);
			enemy.kill(); 
			BonusType = 0;
			return;
		}		
		if (enemy.damageAmount === -3){
			BulletSpacingChar = BulletSpacingChar + 15;
			CharReloadingText.render();
			var bonusEat = bonuses.getFirstExists(false);
			bonusEat.reset(player.body.x + player.body.halfWidth, player.body.y + player.body.halfHeight);
			bonusEat.alpha = 0.7;
			bonusEat.play('bonusEating', 30, false, true);
			enemy.kill(); 
			BonusType = 0;
			return;
		}	
		if (enemy.damageAmount === -4){
			PowerChar = PowerChar + 0.2;
			CharPowerText.render();
			var bonusEat = bonuses.getFirstExists(false);
			bonusEat.reset(player.body.x + player.body.halfWidth, player.body.y + player.body.halfHeight);
			bonusEat.alpha = 0.7;
			bonusEat.play('bonusEating', 30, false, true);
			enemy.kill(); 
			BonusType = 0;
			return;
		}	
		if (enemy.damageAmount === -5){
			if (OP < 5){
				OP += 1;
			}
			var bonusEat = bonuses.getFirstExists(false);
			bonusEat.reset(player.body.x + player.body.halfWidth, player.body.y + player.body.halfHeight);
			bonusEat.alpha = 0.7;
			bonusEat.play('bonusEating', 30, false, true);
			enemy.kill(); 
			OPText.render();
			BonusType = 0;
			return;
		}	
		if (enemy.damageAmount === -6){
			player.damage(-50);
			if (player.health > 200){
				player.health = 200;
			}
			var bonusEat = bonuses.getFirstExists(false);
			bonusEat.reset(player.body.x + player.body.halfWidth, player.body.y + player.body.halfHeight);
			bonusEat.alpha = 0.7;
			bonusEat.play('bonusEating', 30, false, true);
			enemy.kill(); 
			hp.render();
			BonusType = 0;
			return;
		}	
		var explosion = explosions.getFirstExists(false);
		explosion.reset(enemy.body.x + enemy.body.halfWidth, enemy.body.y + enemy.body.halfHeight);
		explosion.body.velocity.y = enemy.body.velocity.y;
		explosion.alpha = 0.7;
		explosion.play('explosion', 30, false, true);	
		player.damage(enemy.collide);
		hp.render();
		if (ComboText){
			ComboText.visible = false;
		}
		Combo = 0;
		enemy.kill(); 	
		if (!player.alive){  
		//	gameover.play();
		}
	}
	
	function hitEnemy(enemy, bullet) {                    // попадание по врагу
		var explosion = explosions.getFirstExists(false);
		var weaponLevel = weaponLevel;
		enemy.hp = enemy.hp - power;
		if (enemy.hp <= 0) {
			var Random;
			XBonus = enemy.body.x;
			YBonus = enemy.body.y;
			explosion.reset(bullet.body.x + bullet.body.halfWidth, bullet.body.y + bullet.body.halfHeight);
			explosion.body.velocity.y = enemy.body.velocity.y;
			explosion.alpha = 0.7;
			explosion.play('explosion', 30, false, true);	
			if (enemy.score === 0){
				enemy.kill();
				return;
			}
			score += enemy.score;
			if (Combo > 1){
				ComboText.visible = false;
			}
			Combo += 1;
			if (Combo === 25){
				Monster.play();
			}
			if (Combo === 50){
				UltraCombo.play();
			}
			if (Combo === 75){
				ULTRA.play();
			}
			if (MaxCombo < Combo){
				MaxCombo = Combo;
			}
			if (Combo > 1){
				ComboText = game.add.text(enemy.body.x, enemy.body.y, 'x' + Combo, { font: '20px Arial', fill: '#FFFFFF' });
				ComboText.render = function () {
					ComboText.text = 'x' + Combo;
				}	
				ComboText.render();
			}
			ComboVisible = game.time.now + 750;
			MaxComboShow.render();
			Evil += 1;
			enemyCount.render();
			if (enemy.collide === 1){
				XBonus = enemy.body.x;
				YBonus = enemy.body.y;
				enemy.kill();
				launchBonusHealChest();
				return;
			}		
			if (enemy.collide === 0.5){
				XBonus = enemy.body.x;
				YBonus = enemy.body.y;
				enemy.kill();
				launchBonusChest();
				return;
			}		
			enemy.kill();
			Random = game.rnd.integerInRange(0, 9);	// шанс выпадения бонуса
			if (enemy.collide === 1000){
				game.time.events.add(100, launchBonus);
				game.time.events.add(300, launchBonus);
				game.time.events.add(500, launchBonus);
				game.time.events.add(700, launchBonus);
				game.time.events.add(900, launchBonus);
			}
			if (enemy.score === 250){  // переход на 2 уровень
				Level = 2;
			}
			if (enemy.score ===	300){
				Level = 3;
			}
			if (Random === 5){  
				game.time.events.add(100, launchBonus);
			}  
		}
		if (player.weaponLevel === 5) {
			scoreText.render();  
			return;
		} 
	    if (score > 500 && score < 2500) {
			player.weaponLevel = 2;
		}
		if (score > 2500 && score < 7500) {
			player.weaponLevel = 3;
		} 
		if (score > 7500) {
			player.weaponLevel = 4;
		} 
		scoreText.render(); 
		bullet.kill();		 
	}
	
	function enemyHitsPlayer(player, bullet) {            // попадание по игроку вражеской пулей
		if (Invisible === 1) {
			return;
		}
		if (ComboText){
			ComboText.visible = false;
		}
		Combo = 0;
		var explosion = explosions.getFirstExists(false);
		explosion.reset(player.body.x + player.body.halfWidth, player.body.y + player.body.halfHeight);
		explosion.alpha = 0.7;
		explosion.play('explosion', 30, false, true);
		bullet.kill();
		player.damage(bullet.damageAmount);
		hp.render()
	}   
	
	function render() {
	}
	}
    </script>

    </body>
</html>