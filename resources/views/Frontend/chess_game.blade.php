<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Online Chess Game</title>
  <link rel="stylesheet" href="/frontend/chess/chessboard.css">
  <link rel="stylesheet" href="https://unpkg.com/chessboardjs@1.0.0/www/css/chessboard-1.0.0.min.css" />
  <style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f4f9; margin: 0; padding: 0; }
    .container { display: flex; flex-direction: row; justify-content: center; align-items: flex-start; padding: 30px; gap: 30px; }
    .controls { text-align: center; margin: 20px 0; }
    .controls button { background-color: #4CAF50; border: none; color: white; padding: 12px 24px; margin: 0 8px; font-size: 16px; cursor: pointer; border-radius: 5px; transition: background 0.3s ease; }
    .controls button:hover { background-color: #45a049; }
    #gameStatus { text-align: center; font-size: 18px; font-weight: bold; color: #222; margin-bottom: 20px; }
    #board { width: 550px; box-shadow: 0 0 12px rgba(0,0,0,0.2); }
    .history { background-color: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); padding: 15px 20px; width: 200px; height: 400px; overflow-y: auto; }
    .history h3 { margin-top: 0; text-align: center; font-size: 18px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
    .move-list { list-style: none; padding: 0; font-size: 16px; }
    .move-list li { padding: 4px 0; border-bottom: 1px dashed #eee; }
    @media (max-width: 768px) { .container { flex-direction: column; align-items: center; } .history { width: 90%; max-width: 300px; height: auto; margin-top: 20px; } }
  </style>
</head>
<body>

<div class="controls">
  <button onclick="resetGame()">Reset Game</button>
  <div id="playerRole" style="text-align: center; font-size: 20px; font-weight: bold; margin-bottom: 20px;"></div>
</div>

<div id="gameStatus"></div>

<div class="container">
  <div id="boardContainer" style="position: relative; width: 550px;">
    <div id="board"></div>
    <img src="/frontend/tcul_img/home/ArcherKids-logo.png" alt="Watermark Logo" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.4; pointer-events: none; width: 300px;" />
  </div>
  
  <div class="history">
    <h3>Move History</h3>
    <ul id="moveHistory" class="move-list">
      @foreach($game->moves as $index => $move)
        @php $color = ($index % 2 === 0) ? 'White' : 'Black'; @endphp
        <li>{{ $move->move_number }}. ({{ $color }}) {{ $move->move_notation }}</li>
      @endforeach
    </ul>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="/frontend/chess/chess.min.js"></script>
<script src="/frontend/chess/chessboard.js"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>

<script>
let board = null;

// Initialize Chess instance
let lastFen = @json($game->moves->last()->fen ?? null);
let game = lastFen ? new Chess(lastFen) : new Chess();

const playerToken = @json($playerToken);
const playerColor = @json($orientation);
const player1Token = @json($game->player1_token);
const player2Token = @json($game->player2_token);

let lastMoveNumber = @json($game->moves->last()->move_number ?? 0);
const historyEl = document.getElementById("moveHistory");

// Update game status display
function updateGameStatus() {
    const statusEl = document.getElementById('gameStatus');
    let status = '';
    if (game.game_over()) {
        if (game.in_checkmate()) status = `Checkmate! ${game.turn() === 'w' ? 'Black' : 'White'} wins.`;
        else if (game.in_stalemate()) status = "Stalemate!";
        else if (game.in_threefold_repetition()) status = "Draw by repetition!";
        else if (game.insufficient_material()) status = "Draw by insufficient material!";
        else status = "Game over!";
    } else {
        status = `${game.turn() === 'w' ? 'White' : 'Black'} to move.`;
    }
    statusEl.textContent = status;
}

// Display player role
function updatePlayerRole() {
    const roleEl = document.getElementById('playerRole');
    if (playerColor === 'white') { roleEl.textContent = 'You are playing as White'; roleEl.style.color = 'blue'; }
    else if (playerColor === 'black') { roleEl.textContent = 'You are playing as Black'; roleEl.style.color = 'green'; }
    else { roleEl.textContent = 'You are watching as a Spectator'; roleEl.style.color = 'gray'; }
}

// Handle piece drag
function onDragStart(source, piece) {
    if (game.game_over()) return false;
    if (playerColor === 'spectator') return false;
    if ((playerColor === 'white' && piece.startsWith('b')) || (playerColor === 'black' && piece.startsWith('w'))) return false;
}

// Handle piece drop
function onDrop(source, target) {
    if ((playerColor === 'white' && game.turn() !== 'w') || (playerColor === 'black' && game.turn() !== 'b')) return 'snapback';

    const move = game.move({ from: source, to: target, promotion: 'q' });
    if (!move) return 'snapback';

    // Send move to backend
    fetch("{{ route('games.move', $game->id) }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({
            from: source,
            to: target,
            notation: move.san,
            fen: game.fen(),
            player_token: playerToken
        })
    }).catch(err => console.error(err));

    updateGameStatus();
}

// Snap piece to correct position
function onSnapEnd() {
    board.position(game.fen());
}

// Add move to move history
function addMoveToHistory(notation, moveNumber, color) {
    let li = document.createElement("li");
    li.textContent = `${moveNumber}. (${color}) ${notation}`;
    historyEl.appendChild(li);
    historyEl.scrollTop = historyEl.scrollHeight;
}

// Initialize the board
function initBoard() {
    board = Chessboard('board', {
        draggable: true,
        position: game.fen(),
        orientation: playerColor,
        pieceTheme: '/frontend/chess/chesspieces/{piece}.png',
        showNotation: true,
        onDragStart: onDragStart,
        onDrop: onDrop,
        onSnapEnd: onSnapEnd
    });
}

// Reset game
function resetGame() {
    game.reset();
    board.start();
    document.getElementById('moveHistory').innerHTML = '';
    lastMoveNumber = 0;
    updateGameStatus();
}

// --- Real-time updates using Echo ---
const echo = new Echo({
    broadcaster: 'pusher',
    key: 'chessgamekey',
    wsHost: '192.168.1.100',
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
    encrypted: false
});

echo.channel(`game.${@json($game->id)}`)
    .listen('MoveMade', (e) => {
        // Skip if already on this device
        if (e.player_token === playerToken) return;

        // Load the FEN
        game.load(e.fen);
        board.position(e.fen);
        updateGameStatus();

        const color = (e.player_token === player1Token) ? 'White' : 'Black';
        addMoveToHistory(e.move_notation, e.move_number, color);
        lastMoveNumber = e.move_number;
    });

// Initialize everything
initBoard();
updateGameStatus();
updatePlayerRole();
</script>

</body>
</html>
