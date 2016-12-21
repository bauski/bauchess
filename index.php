<?php
    // https://github.com/jhlywa/chess.js/blob/master/README.md
    // http://chessboardjs.com/examples#5002

    // Puzzle database of FEN + PGN + tags
    // If user move != PGN than give them another chance
    // User database of puzzles done correctly, puzzles done wrong, puzzles playlists
    // Playlists database of puzzles linked to users
?>
<html>
    <head>
        <title>
            Chess Puzzles
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/chessboard.css">
        <link rel="stylesheet" href="css/bauchess.css">
        <script src="js/chess.js"></script>
        <script src="js/jquery.js"></script>
        <script src="js/chessboard.js"></script>
        <script>
            $(function() {
                var board,
                game = new Chess(),
                statusEl = $('#status'),
                fenEl = $('#fen'),
                pgnEl = $('#pgn');

                // do not pick up pieces if the game is over
                // only pick up pieces for the side to move
                var onDragStart = function(source, piece, position, orientation) {
                    if (game.game_over() === true || (game.turn() === 'w' && piece.search(/^b/) !== -1) || (game.turn() === 'b' && piece.search(/^w/) !== -1)) {
                        return false;
                    }
                };

                var onDrop = function(source, target) {
                    // see if the move is legal
                    var move = game.move({
                    from: source,
                    to: target,
                    promotion: 'q' // NOTE: always promote to a queen for example simplicity
                });

                // illegal move
                if (move === null) {
                    return 'snapback';
                }

                updateStatus();
                };

                // update the board position after the piece snap
                // for castling, en passant, pawn promotion
                var onSnapEnd = function() {
                    board.position(game.fen());
                };

                var updateStatus = function() {
                    var status = '';

                    var moveColor = 'White';
                    if (game.turn() === 'b') {
                        moveColor = 'Black';
                    }

                    // if game is checkmate
                    if (game.in_checkmate() === true) {
                        status = 'Game over, ' + moveColor + ' is in checkmate.';
                    // else if game is draw
                    } else if (game.in_draw() === true) {
                        status = 'Game over, drawn position';
                    // else game is going
                    } else {
                        status = moveColor + ' to move';
                        // check?
                        if (game.in_check() === true) {
                          status += ', ' + moveColor + ' is in check';
                        }
                    }

                    statusEl.html(status);
                    fenEl.val(game.fen());
                    pgnEl.val(game.pgn());
                };

                var cfg = {
                    draggable: true,
                    position: 'start',
                    onDragStart: onDragStart,
                    onDrop: onDrop,
                    onSnapEnd: onSnapEnd,
                    position: '6k1/p1b3p1/4p2r/1Pp4b/3pPp1q/1P1P1P2/2P1QR2/R5KN b - - 0 1'
                };
                board = ChessBoard('board', cfg);

                updateStatus();

                board.resize();
                $(window).resize(board.resize);
            });
        </script>
    </head>
    <body>
        <div class="nav">
            <div class="container">
                <a href="#">
                    Login
                </a>
                <a href="#">
                    Puzzle Search
                </a>
                <a href="#">
                    Puzzle Sets
                </a>
            </div>
        </div>
        <div class="main">
            <div class="container">
                <h1>
                    Puzzle #1
                </h1>
                <div id="status"></div>
                <div id="board"></div>
                <div class="fen">
                    <input id="fen"></input>
                </div>
                <button>
                    Give Up
                </button>
                <button>
                    Reset
                </button>
                <button>
                    Next
                </button>
            </div>
        </div>
    </body>
</html>
