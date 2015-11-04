<!doctype html>
<html lang="ja">
    <head>
	<meta charset="UTF-8">
	<title>Threejsの練習</title>
	<style>
	 *{margin:0px; padding: 0px;}
	</style>
	<script src="./bower/bower_components/three/index.js"></script>
	<script src="/three/js/renderers/CanvasRenderer.js"></script>
    </head>
    <body>
	<script>
	 // ページをすべて読み込んだあとに関数の中を実行
	 window.addEventListener("load",function() {

	   /*-------------------------------------------------------
	   ここから変数の宣言と定義
	   -------------------------------------------------------*/

	   // レンダラ、シーン、カメラ、ジオメトリ、マテリアル、メッシュ用の変数を宣言
	   var renderer, scene, camera, geometry, material, mesh;

	   // 描画領域をHTMLに確保するためのコンテナ用変数
	   var container;

	   // 描画領域の幅と高さをウィンドウの幅と高さに設定
	   var canvasWidth  = window.innerWidth;
	   var canvasHeight = window.innerHeight;
	   // layer count
	   var layer_c = 15;
	   // vertex count
	   var vertex_c = 50;


	   /*-------------------------------------------------------
	   ここからレンダラ（描画領域）の生成と設定
	   -------------------------------------------------------*/

	   // レンダラの生成
	   renderer = new THREE.WebGLRenderer( { alpha: true, antialias: true } );
	   // WebGLをサポートしていない場合（何も表示されない場合）は上の行をコメントアウトして下の行のコメントアウトを外してください
	   //renderer = new THREE.CanvasRenderer({ alpha: true } );

	   // レンダラの大きさをウィンドウの幅と高さに設定
	   renderer.setSize( canvasWidth, canvasHeight );

	   // レンダラの背景色を白に、透明度を100%に設定
	   renderer.setClearColor( 0x424242, 0 );

	   // div要素をつくってHTMLのbody要素内に追加し、その中にレンダラがもつDOM要素を追加
	   container = document.createElement( 'div' );
	   container.style.backgroundColor = "#424242";
	   document.body.appendChild( container );
	   container.appendChild( renderer.domElement );

	   /*-------------------------------------------------------
	   ここからシーンの生成
	   -------------------------------------------------------*/

	   // シーンの生成
	   scene = new THREE.Scene();
	   var axisHelper = new THREE.AxisHelper(800);
//	   scene.add(axisHelper);

	   /*-------------------------------------------------------
	   ここからカメラの生成と設定
	   -------------------------------------------------------*/

	   // 画角20度、画面アスペクト比はウィンドウのアスペクト比と同様、手前1、後方10000までを描画できる
	   // 投資投影カメラ（遠近法的に映る）を生成
	   camera = new THREE.PerspectiveCamera( 20, canvasWidth / canvasHeight, 1 , 10000 );

	   // カメラの位置を座標(x,y,z)=(0,500,1000)に設定
	   camera.position.set( 0, 500, 8000 );

	   // カメラの向きを座標の原点(x,y,z)=(0,0,0)に設定
	   camera.lookAt( { x:0, y:0, z:0 } );

	   /*-------------------------------------------------------
	   ここからジオメトリの生成
	   -------------------------------------------------------*/

	   // 半径150、経度分割数と緯度分割数が32の球体ジオメトリを生成
	   geometry = new THREE.SphereGeometry( 30, 10, 10 );
	   circle = new THREE.CircleGeometry(80, 32);
	   globe = new THREE.SphereGeometry( 5, 20, 20 );
	   lineGeo = new THREE.BufferGeometry();

	   lineGeoes = [];
	   lineGeoX = [];
	   lineGeoY = [];
	   lineGeoZ = [];

	   for (var i =0; i < vertex_c; i++) {
	     lineGeo = new THREE.Geometry();
//	     lineGeo.vertices.push(new THREE.Vector3(0,0,0));
	     x = (150 - Math.random() * 300);
	     y = (150 - Math.random() * 300);
	     z = (150 - Math.random() * 300);

//	     lineGeo.vertices.push(new THREE.Vector3(0, 0, 0));
//	     lineGeo.vertices.push(new THREE.Vector3(x, y, z));
//	     lineGeo.vertices.push(new THREE.Vector3(0,0, 0));

	     lineGeoX.push(x);
	     lineGeoY.push(y);
	     lineGeoZ.push(z);
	     lineGeoes.push(lineGeo);
	   }

	   /*-------------------------------------------------------
	   ここからマテリアルの生成
	   -------------------------------------------------------*/

	   // hexコードff00ffの色をもち、ワイヤーフレームを有効にしたマテリアルの生成
	   // BasicMaterialは光源を必要としない
	   material = new THREE.MeshBasicMaterial( { color: 0xffba44, wireframe: true } );
	   layer_material = new THREE.MeshBasicMaterial( { color: 0x50ed58, wireframe: true } );
	   line_material = new THREE.LineBasicMaterial({ color: 0xffffff, opacity: 0.3, transparent: true, linewidth: 0.2 });

	   /*-------------------------------------------------------
	   ここからメッシュの生成
	   -------------------------------------------------------*/

	   // ジオメトリとマテリアルを渡してメッシュを生成
	   layer_sphere = new THREE.Mesh( geometry, layer_material );
	   mesh2 = new THREE.Mesh( circle, material );
	   mesh3 = new THREE.Mesh ( globe, material );
	   mesh4 = mesh3.clone();

	   // 層球の設定
	   layer_sphere.position.set(-800, 0, 0);
	   
	   layer_spheres = [];
	   layer_node = [];

	   meshes = [];
	   lines = [];

	   for (var i = 0; i<vertex_c; i++) {
	     line = new THREE.Line( lineGeoes[i], line_material );
	     line.position.set(0, 0, 0);
	     lines.push(line);
	    }
	   var radius = 800;
	   for(var i=-10; i<layer_c; i++){
	     // layer球の設定
	     layer = layer_sphere.clone();
/*
	     layer_x = 500*i - Math.random() * 100;
	     layer_y = 500 - Math.random() * 1000;
	     layer_z = 300 - Math.random() * 600;
	     layer.position.set(layer_x, layer_y, layer_z);
	     layer_spheres.push(layer);
*/

	     var phi = Math.acos( -1 + ( 2 * i ) / layer_c );
	     var theta = Math.sqrt( layer_c * Math.PI ) * phi;

	     layer.position.set(
	       layer_x = radius * Math.cos( theta ) * Math.sin( phi ),
	       layer_y = radius * Math.sin( theta ) * Math.sin( phi ),
	       layer_z = radius * Math.cos( phi )
	     );
	     layer_spheres.push(layer);
	     
	     for ( var j=0; j<vertex_c; j++) {
	       // layer球ごとのノードの設定
	       mesh_clone = mesh3.clone();
	       mesh_clone.position.x = (150 - Math.random() * 300 + layer_x); 
	       mesh_clone.position.y = (150 - Math.random() * 300 + layer_y);
	       mesh_clone.position.z = (150 - Math.random() * 300 + layer_z); 
	       lineGeoes[j].vertices.push(mesh_clone.position);

	       layer_node.push(mesh_clone);
	     }
	   }

	   for ( var i=0; i<vertex_c; i++ ) {
	     mesh_clone = mesh3.clone();

	     mesh_clone.position.x = lineGeoX[i] - 800;
	     mesh_clone.position.y = lineGeoY[i];
	     mesh_clone.position.z = lineGeoZ[i];


	     meshes.push(mesh_clone);
	   }

	   /*-------------------------------------------------------
	   ここから描画
	   -------------------------------------------------------*/

	   // メッシュをシーンに追加
//	   scene.add( layer_sphere );
	   for(var i=0; i<layer_spheres.length; i++)scene.add(layer_spheres[i]);
	   for(var i=0; i<layer_node.length; i++)scene.add(layer_node[i]);

//	   scene.add( mesh2 );
//	   scene.add( mesh3 );
//	   scene.add( mesh4 );

	   for(var i = 0; i<vertex_c; i++){
	     scene.add(lines[i]);
//	     scene.add(meshes[i]);
	   } 

	   
	   // レンダラにシーンとカメラを渡してブラウザの画面に描画
	   renderer.render( scene, camera );
	   var theta = 0;
	   
	   var v_index = 0;
	   var rotationAnimation = function(){

	     camera.position.set(4000 * Math.sin( theta ), 4000, 4000 * Math.cos(theta));
	     camera.lookAt({ x:0, y:0, z:0 });
	     renderer.render(scene, camera);

	     theta += 0.01;
	     theta %= 2 * Math.PI;


	     /*
	     if (mesh.position.x < 130) mesh.position.x += 1;
	     console.log(meshes[v_index]);
	     x_flag = meshes[v_index].position.x == lineGeoX[v_index];
	     y_flag = meshes[v_index].position.y == lineGeoY[v_index];
	     z_flag = meshes[v_index].position.z == lineGeoZ[v_index];

	     if (!x_flag && !y_flag && !z_flag){
	       meshes[v_index].position.x += (!x_flag) ? 1 : 0;
	       meshes[v_index].position.y += (!y_flag) ? 1 : 0;
	       meshes[v_index].position.z += (!z_flag) ? 1 : 0;
	     }else{
	       v_index += 1;
	     }

	     renderer.render(scene, camera);
	     */
	     requestAnimationFrame(rotationAnimation);
	   }
	   rotationAnimation();

	 });

	</script>
    </body>
</html>
