<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>Chess</title>
	<link href="./css/jquery-ui.css" rel="stylesheet">
	<link href="./css/bootstrap-3.3.7.onlymodal.min.css" rel="stylesheet">
	<style>
	.cell {
	  border-top: 1px solid white;
	  border-left: 1px solid white;
	}

	.black-cell {
		background-color: #4b64da20;
		/*background-color: #d0dff4;*/
	}

	.white-cell {
		/*background-color: #4b64dab8;*/
		background-color: #d0dff4;
	}

	.center {
	  display: block;
	  margin-left: auto;
	  margin-right: auto;
	  width: 50%;
	}

	.active-cell {
	  border-top: 3px solid white;
	  border-left: 3px solid white;
	  background: orange !important;
	  font-weight: normal;
	  color: #ffffff;	
	}

	</style>
</head>
<body style="">


	<div>
		<img id="background" style="display: block; margin-left: auto; margin-right: auto; height: 698px;" src="images/board.png">
	</div>

	<div id="optionModal" class="modal" tabindex="-1" role="dialog">
	    <div class="modal-dialog modal-lg" style="width:600px;" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title increase-font-size">Welcome to Chess World!</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body increase-font-size">

	            	<div>Please choose starting location. <br>Your team is in white color.
	            	</div>

	            </div>
	            <div class="modal-footer">
	                <!-- <button id='save_button' type="button" class="btn btn-primary increase-font-size">Save changes</button> -->
	                <button type="button" class="btn btn-secondary increase-font-size" data-dismiss="modal">Close</button>
	            </div>
	        </div>
	    </div>
	</div>




<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/js/jquery-2.js"></script>
<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/js/jquery.ui.touch-punch.min.js"></script>
<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/js/reposition-3.js"></script>

<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="<?= yii\helpers\Url::base() ?>/js/bootstrap-3.3.2.min.js"></script>

<script type="text/javascript">
	window.$ = window.jQuery = window.jQuery_default;
	window.starting_position_selected = 0;
	window.starting_x = 0;
	window.starting_y = 6;
	window.user_id = <?= $user_id ?>;
	window.nMyPieces = 0;


	$( function (){
		function chooseStartingLocation() {

			window.startIn--;
			$('#toast').html('Starting in '+window.startIn);

			if(window.startIn <= 0){
				$('#toast').html('');
				clearInterval(window.gameStartTimer);
				window.starting_position_selected = 1;

				for (var y = 1; y >= 0; y--) {
					for (var x = 7; x >= 0; x--) {
						// send the starting locations to the server
						var piece = cell(starting_x + x, starting_y + y).find('.draggable');
						$.post("<?= yii\helpers\Url::base() ?>?r=moves%2Fcreate",
						{ 
						    'r': "moves/create",
						    '_csrf': '<?= Yii::$app->request->csrfToken ?>',
						    'Moves[from_x]': '',
						    'Moves[from_y]': '',
						    'Moves[to_x]': starting_x + x,
						    'Moves[to_y]': starting_y + y,
						    'type_id': piece.data('typeId'),
						    'Moves[to_piece_id]': "",
						 }
						);

					}
				}

			}
		}

		window.cell_amount = 8 * 3;
		window.last_move_id = <?= $last_move_id ?>;
		window.starting_x = 0;
		window.starting_y = 6;

		// loadCells(cell_amount, cell_amount)
		loadCells(0,0)
		function loadCells(x1, y1){
			var w = window.innerWidth;
			var h = window.innerHeight;
			var piece = "";
			cell_width = (w < h ? w : h)*3 / (cell_amount + 1);


			for (var y = y1; y <= y1+cell_amount-1; y++) {	
				var row = '';
				for (var x= x1; x <= x1+cell_amount-1; x++) {
					row += '<div id="cell-'+x+'-'+y+'" data-x="'+x+'" data-y="'+y+'" style="" class="droppable '+((y+x)%2 == 0 ? 'black-cell' : 'white-cell')+' cell"></div>';
				}

				$('body').append(row);
			}

			repositionElements()

			$('body').append('<div id="toast" class="increase-font-size" style="background-color: aliceblue; position: fixed; bottom: 10%; padding: 10px; font-family: sans-serif; font-weight: bold; color: #235a81;">Starting in 10</div>')
			if(w < h){
				$('#optionModal > div.modal-dialog.modal-lg').width('900px');
				$('.increase-font-size').css('font-size', '60px');
			}
		}

			<?php foreach ($pieces as $p) { ?>
				cell(<?= $p['x'].','.$p['y'] ?>).html(generatePiece(<?= $p['type_id'].','.$p['id'].','.$p['player_id'] ?>));
		    <?php } ?>

			initDraggable();


		    if(window.nMyPieces <= 1){
				positionMypieces();
				jQuery3_3_1('#optionModal').modal({ keyboard: false }).on('hidden.bs.modal', function () {
				    // when modal is closed, start 10 second timer to start game
				    window.startIn = 10;
					$('#toast').html('Starting in '+window.startIn);
					window.gameStartTimer = setInterval(chooseStartingLocation, 1000);
				});
		    }else
				window.starting_position_selected = 1;


			$( ".droppable" ).droppable({
			  activeClass: "active-cell",
			  hoverClass: "ui-state-hover",
			  accept: ":not(.ui-sortable-helper)",
			  scroll: false,
			  drop: function( event, ui ) {
			  	if(starting_position_selected){
					$.post("<?= yii\helpers\Url::base() ?>?r=moves%2Fcreate",
					{ 
					    'r': "moves/create",
					    '_csrf': '<?= Yii::$app->request->csrfToken ?>',
					    'Moves[from_x]': $( ui.draggable).parent().data('x'),
					    'Moves[from_y]': $( ui.draggable).parent().data('y'),
					    'Moves[to_x]': $( this ).data('x'),
					    'Moves[to_y]': $( this ).data('y'),
					    'type_id': $( ui.draggable).data('typeId'),
					    'Moves[to_piece_id]': $( this ).find('.opponent').data('pieceId'),
					 }
					);

					$( this ).html('');
					// $( "<li></li>" ).text( ui.draggable.text() ).appendTo( this );
					$( ui.draggable).appendTo( this );
			  	}else{
			  		x_diff = $( this ).data('x') - $( ui.draggable).parent().data('x');
			  		y_diff = $( this ).data('y') - $( ui.draggable).parent().data('y');

					// remove existing pieces
					for (var y = 1; y >= 0; y--) {
						for (var x = 7; x >= 0; x--) {
							// remove existing pieces
							cell(starting_x + x, starting_y + y).html('');
						}
					}

					window.starting_x += x_diff;
					window.starting_y += y_diff;

					// add pieces to new location
					positionMypieces();


			  	}

			  },
/* 			  out: function( event, ui ) {
				$( this )
				  .remove
			  } */
			});

				setInterval(getUpdates, 1000);

				function getUpdates(){
					$.get("<?= yii\helpers\Url::base() ?>",
					{ 
					    'X-Requested-With': "XMLHttpRequest",
					    'r': "moves/updates",
					    '_csrf': '<?= Yii::$app->request->csrfToken ?>',
					    'last_move_id': last_move_id
					 }
					).done(function( data ) {
						var dt = JSON.parse(data);

						for (var i = 0; i <= dt.length - 1; i++) {
							var d = dt[i];
							last_move_id = d['id'];
							var to = cell( d['to_x'], d['to_y'] );
							var from = cell( d['from_x'], d['from_y'] );

							if(d['player_id'] != window.user_id){
								if(from.find('.draggable').length){
									
									to.html(from.find('.draggable'));
									from.html('');
								}else{
									to.html(generatePiece(d['type_id'], d['piece_id'], d['player_id']));
									from.html('');
								}
							}
						}

						console.log(data);
					 });
				}

				// window.getUpdates = getUpdates;

			    document.body.style.backgroundColor = "#f9f8fd";
			    initialize_reposition(720, 1084);

			    function cell(x, y){
			    	return $('#cell-'+x+'-'+y);
			    }
		

				function positionMypieces(){
					for (var y = 1; y >= 0; y--) {
						for (var x = 7; x >= 0; x--) {
							var type_id = x + 1 + (y * 8);

							cell(starting_x + x, starting_y + y).html(generatePiece(type_id, 0, 0));
						}
					}

					initDraggable();
				}

				function generatePiece(type_id, piece_id, player_id){
					player_id = (player_id == window.user_id ? 0 : player_id);
					color = (player_id == 0 ? "white" : "black");

					if(player_id == 0)
						window.nMyPieces++;

					if(type_id == 3 || type_id == 6)
						piece = "bishop";
					else if(type_id == 4)
						piece = "king";
					else if(type_id == 2 || type_id == 7)
						piece = "knight";
					else if(type_id > 8)
						piece = "pawn";
					else if(type_id == 5)
						piece = "queen";
					else if(type_id == 1 || type_id == 8)
						piece = "rook";

					return '<div style="" data-piece="'+piece+'" data-color="'+color+'" data-type-id="'+type_id+'" data-piece-id="'+piece_id+'" data-player-id="'+player_id+'" class="'+(color == 'white' ? 'draggable' : 'opponent')+'"><img style="height: '+cell_width+'px; width: '+cell_width+'px;" src="images/'+piece+'-'+color+'.png" alt=""></div>';
				}

			    function canMove(targetCell, sourceColor, whenEmptyOnly = 0, toAttackOnly = 0){
			    	var color = targetCell.children().data("color");
			    	var colorIsEmpty = typeof color  == 'undefined';
			    	var canMove = false;

			    	if( !starting_position_selected )
			    		canMove = true;

			    	// color needs to be empty
			    	if( whenEmptyOnly ){
			    		if( colorIsEmpty )
				    		canMove = true;
			    	}else if( toAttackOnly ) { // when opposite color
			    		if( !colorIsEmpty && color != sourceColor )
				    		canMove = true;			    		
			    	}else if( color != sourceColor ) // when not same color
			    		canMove = true;

			    	// if can move, 	allow the drop
			    	if( canMove )
				    	targetCell.droppable({ disabled: false });

			    	return canMove;
			    }

				function initDraggable(){
					$( ".draggable" ).draggable({
					  appendTo: "body",
					  helper: "clone",
					  start: function( event, ui ) {

					  	$( ".droppable" ).droppable({
						  disabled: true
						});

						var piece = event.target.dataset.piece;
						var sourceColor = event.target.dataset.color;
						var parent = event.target.parentNode;
						var x = parent.dataset.x * 1;
						var y = parent.dataset.y * 1;
						var x_min = 0;
						var x_max = cell_amount - 1;
						var y_min = 0;
						var y_max = cell_amount - 1;

					  	// if pawn
					  	if(piece == "pawn"){
						  	// can move up, when empty
						  	canMove( cell(x, y-1), sourceColor, 1)

						  	// can move right, when empty
						  	canMove( cell(x+1, y), sourceColor, 1)

						  	// can move down, when empty
						  	canMove( cell(x, y+1), sourceColor, 1)

						  	// can move left, when empty
						  	canMove( cell(x-1, y), sourceColor, 1)

							// can move upper left, to attack
						  	canMove( cell(x-1, y-1), sourceColor, 0, 1)

							// can move upper right, to attack
						  	canMove( cell(x+1, y-1), sourceColor, 0, 1)

							// can move lower left, to attack
						  	canMove( cell(x-1, y+1), sourceColor, 0, 1)

							// can move lower right, to attack
						  	canMove( cell(x+1, y+1), sourceColor, 0, 1)
					  	}

					  	// if knight
					  	if(piece == "knight" || !starting_position_selected){
							// can move upper left
						  	canMove( cell(x-1, y-2), sourceColor)

							// can move upper right
						  	canMove( cell(x+1, y-2), sourceColor)

							// can move lower left
						  	canMove( cell(x-1, y+2), sourceColor)

							// can move lower right
						  	canMove( cell(x+1, y+2), sourceColor)

							// can move right top
						  	canMove( cell(x+2, y-1), sourceColor)

							// can move right bottom
						  	canMove( cell(x+2, y+1), sourceColor)

							// can move left top
						  	canMove( cell(x-2, y+1), sourceColor)

							// can move left bottom
						  	canMove( cell(x-2, y-1), sourceColor)
					  	}

					  	// if rook or queen
					  	if(piece == "rook" || piece == "queen" || !starting_position_selected){
							// can move up
							for (var i = 1; y-i >= y_min && i <= 8; i++) {
								if( !canMove( cell(x, y-i), sourceColor) )
									break;
							}

							// can move down
							for (var i = 1; y+i <= y_max && i <= 8; i++) {
								if( !canMove( cell(x, y+i), sourceColor) )
									break;
							}

							// can move right
							for (var i = 1; x+i <= x_max && i <= 8; i++) {
								if( !canMove( cell(x+i, y), sourceColor) )
									break;
							}

							// can move left
							for (var i = 1; x-i >= x_min && i <= 8; i++) {
								if( !canMove( cell(x-i, y), sourceColor) )
									break;
							}

					  	}

					  	// if bishop or queen
					  	if(piece == "bishop" || piece == "queen" || !starting_position_selected){
							// can move upper-right
							for (var i = 1; y-i >= y_min && x+i <= x_max && i <= 8; i++) {
								if( !canMove( cell(x+i, y-i), sourceColor) )
									break;
							}

							// can move lower-right
							for (var i = 1; y+i <= y_max && x+i <= x_max && i <= 8; i++) {
								if( !canMove( cell(x+i, y+i), sourceColor) )
									break;
							}

							// can move lower-left
							for (var i = 1; y+i <= y_max && x-i >= x_min && i <= 8; i++) {
								if( !canMove( cell(x-i, y+i), sourceColor) )
									break;
							}

							// can move upper-left
							for (var i = 1; y-i >= y_min && x-i >= x_min && i <= 8; i++) {
								if( !canMove( cell(x-i, y-i), sourceColor) )
									break;
							}

					  	}

					  	// if king
					  	if(piece == "king"){
						  	// can move up
						  	canMove( cell(x, y-1), sourceColor)

						  	// can move right
						  	canMove( cell(x+1, y), sourceColor)

						  	// can move down
						  	canMove( cell(x, y+1), sourceColor)

						  	// can move left
						  	canMove( cell(x-1, y), sourceColor)

							// can move upper left
						  	canMove( cell(x-1, y-1), sourceColor)

							// can move upper right
						  	canMove( cell(x+1, y-1), sourceColor)

							// can move lower left
						  	canMove( cell(x-1, y+1), sourceColor)

							// can move lower right
						  	canMove( cell(x+1, y+1), sourceColor)
					  	}



					  }
					});
				}


		
	});





	function repositionElements() {
		window.strobeTopLeft = null;
		window.strobeBottomRight = null;

		for (var y = 0; y <= cell_amount-1; y++) {
			for (var x= 0; x <= cell_amount-1; x++) {

				repositionElement("cell-"+x+"-"+y, (x/cell_amount)+0/754, (((y+0)*45.5)/(cell_amount*25.5938))-291.1333279079861/754, (x+1)/cell_amount, ((y+1)*1.1)/(200), 0, 1, x, y);
			}
		}
	}

</script>


</body>
</html>
