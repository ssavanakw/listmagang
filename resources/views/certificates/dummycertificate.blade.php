<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Chess — 2 Player (Castling, En-passant, Promotion) — Fixed</title>
<style>
  :root{
    --light:#f0d9b5;
    --dark:#b58863;
    --accent:#2563eb;
    --bg:#071026;
    --panel:#0b1220;
  }
  html,body{height:100%;margin:0;font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial;}
  body{background:linear-gradient(180deg,#071025 0%, #071226 50%), var(--bg); color:#e6eef8; display:flex; align-items:center; justify-content:center; padding:28px;}
  .container{display:grid; grid-template-columns: 560px 340px; gap:20px; align-items:start; width:100%; max-width:980px;}
  .board-wrap{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); padding:18px; border-radius:12px; box-shadow:0 8px 30px rgba(2,6,23,0.6); }
  .board{width:512px; height:512px; display:grid; grid-template-columns:repeat(8,1fr); grid-template-rows:repeat(8,1fr); border-radius:8px; overflow:hidden; }
  .square{width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:36px; cursor:pointer; user-select:none; position:relative; transition:background .12s;}
  .square.light{background:var(--light); color:#222;}
  .square.dark{background:var(--dark); color:#fff;}
  .square.selected{outline:4px solid rgba(37,99,235,0.18); box-shadow:inset 0 0 0 2px rgba(37,99,235,0.06);}
  .square.move-target::after{content:""; width:14px; height:14px; border-radius:50%; position:absolute; z-index:2; }
  .square.move-target.capture::after{ width:36px; height:36px; border-radius:6px; opacity:0.9; top:calc(50% - 18px); left:calc(50% - 18px);}
  .square.move-target:not(.capture)::after{ background:rgba(37,99,235,0.95); top:calc(50% - 7px); left:calc(50% - 7px); }
  .square.move-target.capture{ background:linear-gradient(180deg, rgba(255,150,150,0.95), rgba(255,80,80,0.95)); }
  .info{background:var(--panel); padding:16px; border-radius:12px; min-height:280px; width:320px; box-shadow:0 6px 20px rgba(2,6,23,0.6);}
  h1{margin:0 0 8px 0; font-size:18px;}
  .turn{display:flex; align-items:center; gap:10px; margin-bottom:10px;}
  .badge{padding:6px 10px; border-radius:8px; font-weight:600; background:rgba(255,255,255,0.03); color:#dbeafe;}
  .controls{display:flex; gap:8px; margin-top:10px;}
  .btn{padding:8px 10px; border-radius:8px; cursor:pointer; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.03); color:inherit;}
  .log{margin-top:12px; background:rgba(255,255,255,0.02); padding:8px; border-radius:8px; max-height:300px; overflow:auto; font-family:monospace; font-size:13px;}
  .coord{position:absolute; font-size:11px; opacity:0.6; top:6px; left:6px;}
  .status{margin-top:8px; font-weight:700;}
  .small{font-size:13px; opacity:0.85;}
  footer{margin-top:12px; opacity:0.7; font-size:12px;}
  .highlight-check{box-shadow:0 0 0 4px rgba(255,80,80,0.12) inset;}
  /* promotion modal */
  .modal-backdrop{position:fixed; inset:0; background:rgba(2,6,23,0.6); display:flex; align-items:center; justify-content:center; z-index:60;}
  .promo-modal{background:linear-gradient(180deg,#0e1724,#071026); border-radius:12px; padding:14px; width:300px; box-shadow:0 10px 40px rgba(2,6,23,0.7); text-align:center; color:#e6eef8;}
  .promo-row{display:flex; gap:8px; justify-content:center; margin-top:10px;}
  .promo-button{padding:8px 10px; border-radius:8px; cursor:pointer; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.04); min-width:56px;}
  @media (max-width:960px){ .container{grid-template-columns:1fr; } .board-wrap{margin:0 auto;} .info{width:100%;} }
</style>
</head>
<body>
  <div class="container" role="application" aria-label="Chess game">
    <div class="board-wrap" aria-hidden="false">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
        <div>
          <h1>Chess — 2 Pemain (Fixed)</h1>
          <div class="small">Castling, En-passant, Promosi dengan pilihan. Perbaikan bug logika en-passant/simulasi.</div>
        </div>
        <div style="text-align:right">
          <div class="small">Local Play — Single file</div>
          <div style="font-size:12px; opacity:.8">Klik kotak untuk memilih → klik tujuan</div>
        </div>
      </div>
      <div id="board" class="board" aria-label="Chess board"></div>
    </div>

    <div class="info" aria-live="polite">
      <div class="turn">
        <div class="badge" id="turnBadge">White to move</div>
        <div id="checkBadge" style="color:#ff8a8a; font-weight:700;"></div>
      </div>

      <div class="controls">
        <button id="resetBtn" class="btn">Reset</button>
        <button id="flipBtn" class="btn">Flip Board</button>
        <button id="undoBtn" class="btn">Undo</button>
      </div>

      <div class="status" id="statusText">Game ready</div>

      <div class="log" id="moveLog" aria-live="polite"></div>

      <footer>Rules: castling, en-passant, promotion choice. (No clocks, no PGN export).</footer>
    </div>
  </div>

  <!-- Promotion modal -->
  <div id="promoModal" class="modal-backdrop" style="display:none;">
    <div class="promo-modal" role="dialog" aria-modal="true">
      <div style="font-weight:700; font-size:15px;">Pilih promosi</div>
      <div class="small" style="opacity:.9; margin-top:6px;">Promote pawn menjadi:</div>
      <div class="promo-row" id="promoOptions"></div>
      <div style="margin-top:10px; font-size:12px; opacity:.7;">Klik untuk memilih</div>
    </div>
  </div>

<script>
/*
  Fixed Chess implementation (single-file)
  - Major fix: pass enPassantTarget into move-generation/simulation so check/legalty are correct.
  - Supports: castling, en-passant, promotion choice, undo.
*/

const PIECE_SYMBOLS = {
  'p': {'w':'♙','b':'♟'},
  'r': {'w':'♖','b':'♜'},
  'n': {'w':'♘','b':'♞'},
  'b': {'w':'♗','b':'♝'},
  'q': {'w':'♕','b':'♛'},
  'k': {'w':'♔','b':'♚'}
};

const boardElem = document.getElementById('board');
const turnBadge = document.getElementById('turnBadge');
const statusText = document.getElementById('statusText');
const moveLog = document.getElementById('moveLog');
const resetBtn = document.getElementById('resetBtn');
const flipBtn = document.getElementById('flipBtn');
const checkBadge = document.getElementById('checkBadge');
const promoModal = document.getElementById('promoModal');
const promoOptions = document.getElementById('promoOptions');
const undoBtn = document.getElementById('undoBtn');

let board = [];
let selected = null;
let legalMoves = [];
let turn = 'w';
let flipped = false;
let enPassantTarget = null; // {r,c} or null
let history = [];
let moveNumber = 1;

const inBounds = (r,c) => r>=0 && r<8 && c>=0 && c<8;
const cloneBoard = (bd) => bd.map(row => row.map(cell => cell ? {...cell} : null));
const coordToAlgebraic = (r,c) => String.fromCharCode(97+c) + (8-r);

// --- Initialization ---
function resetBoard(){
  board = Array.from({length:8}, ()=>Array(8).fill(null));
  for(let c=0;c<8;c++){ board[1][c] = {type:'p', color:'b', hasMoved:false}; board[6][c] = {type:'p', color:'w', hasMoved:false}; }
  const place = (r,c,t,col)=> board[r][c] = {type:t, color:col, hasMoved:false};
  place(0,0,'r','b'); place(0,1,'n','b'); place(0,2,'b','b'); place(0,3,'q','b'); place(0,4,'k','b'); place(0,5,'b','b'); place(0,6,'n','b'); place(0,7,'r','b');
  place(7,0,'r','w'); place(7,1,'n','w'); place(7,2,'b','w'); place(7,3,'q','w'); place(7,4,'k','w'); place(7,5,'b','w'); place(7,6,'n','w'); place(7,7,'r','w');

  selected = null; legalMoves = []; turn = 'w'; flipped = false;
  enPassantTarget = null; history = []; moveNumber = 1;
  moveLog.innerHTML = '';
  statusText.textContent = 'Game reset. White starts.';
  checkBadge.textContent = '';
  updateUI();
}

// --- Find king ---
function getKingPos(bd, color){
  for(let r=0;r<8;r++) for(let c=0;c<8;c++){
    const p = bd[r][c];
    if(p && p.type==='k' && p.color===color) return {r,c};
  }
  return null;
}

// --- Generate moves ---
// generateAllMoves(bd, color, epTarget) -> returns pseudo-legal moves (not filtered for leaving king in check)
function generateAllMoves(bd, color, epTarget = null){
  const moves = [];
  for(let r=0;r<8;r++){
    for(let c=0;c<8;c++){
      const p = bd[r][c];
      if(p && p.color===color){
        const ms = generateMovesForPiece(bd, r, c, true, epTarget);
        ms.forEach(m => moves.push({...m, from:{r,c}}));
      }
    }
  }
  return moves;
}

// generateMovesForPiece(bd, r, c, pseudoOnly=false, epTarget=null)
function generateMovesForPiece(bd, r, c, pseudoOnly=false, epTarget=null){
  const p = bd[r][c];
  if(!p) return [];
  const moves = [];
  const dir = p.color === 'w' ? -1 : 1;
  const add = (rr,cc, opts={capture:false, enPassant:false, castle: null})=>{
    if(!inBounds(rr,cc)) return;
    const target = bd[rr][cc];
    if(!target || target.color !== p.color) moves.push({r:rr, c:cc, capture: !!target || !!opts.capture, enPassant: !!opts.enPassant, castle: opts.castle || null});
  };

  switch(p.type){
    case 'p':
      // forward 1
      const one = r + dir;
      if(inBounds(one,c) && !bd[one][c]) add(one,c,{capture:false});
      // double from start
      const startRow = p.color==='w'?6:1;
      const two = r + 2*dir;
      if(r===startRow && inBounds(two,c) && !bd[one][c] && !bd[two][c]) add(two,c,{capture:false});
      // captures
      for(const dc of [-1,1]){
        const rr = r + dir, cc = c + dc;
        if(inBounds(rr,cc) && bd[rr][cc] && bd[rr][cc].color !== p.color) add(rr,cc,{capture:true});
      }
      // en-passant using epTarget (passed in)
      if(epTarget){
        for(const dc of [-1,1]){
          const rr = r + dir, cc = c + dc;
          if(inBounds(rr,cc) && epTarget.r === rr && epTarget.c === cc){
            add(rr,cc,{capture:true, enPassant:true});
          }
        }
      }
      break;

    case 'n':
      for(const [dr,dc] of [[-2,-1],[-2,1],[-1,-2],[-1,2],[1,-2],[1,2],[2,-1],[2,1]]){
        const rr=r+dr, cc=c+dc;
        if(!inBounds(rr,cc)) continue;
        if(!bd[rr][cc] || bd[rr][cc].color !== p.color) add(rr,cc,{capture:!!bd[rr][cc]});
      }
      break;

    case 'b':
      for(const [dr,dc] of [[-1,-1],[-1,1],[1,-1],[1,1]]){
        let rr=r+dr, cc=c+dc;
        while(inBounds(rr,cc)){
          if(!bd[rr][cc]) { add(rr,cc,{capture:false}); rr+=dr; cc+=dc; continue; }
          if(bd[rr][cc].color !== p.color) add(rr,cc,{capture:true});
          break;
        }
      }
      break;

    case 'r':
      for(const [dr,dc] of [[-1,0],[1,0],[0,-1],[0,1]]){
        let rr=r+dr, cc=c+dc;
        while(inBounds(rr,cc)){
          if(!bd[rr][cc]) { add(rr,cc,{capture:false}); rr+=dr; cc+=dc; continue; }
          if(bd[rr][cc].color !== p.color) add(rr,cc,{capture:true});
          break;
        }
      }
      break;

    case 'q':
      for(const [dr,dc] of [[-1,0],[1,0],[0,-1],[0,1],[-1,-1],[-1,1],[1,-1],[1,1]]){
        let rr=r+dr, cc=c+dc;
        while(inBounds(rr,cc)){
          if(!bd[rr][cc]) { add(rr,cc,{capture:false}); rr+=dr; cc+=dc; continue; }
          if(bd[rr][cc].color !== p.color) add(rr,cc,{capture:true});
          break;
        }
      }
      break;

    case 'k':
      for(const [dr,dc] of [[-1,0],[1,0],[0,-1],[0,1],[-1,-1],[-1,1],[1,-1],[1,1]]){
        const rr=r+dr, cc=c+dc;
        if(!inBounds(rr,cc)) continue;
        if(!bd[rr][cc] || bd[rr][cc].color !== p.color) add(rr,cc,{capture:!!bd[rr][cc]});
      }
      // Castling logic — use p.hasMoved and rook.hasMoved, and ensure not in check and not passing attacked squares.
      if(!p.hasMoved && !isInCheck(bd, p.color, epTarget)){
        // king-side attempt: rook at c+3 (initial layout assumption)
        // ensure squares between empty and not attacked
        // king from c to c+2
        if(c+3 < 8){
          const rook = bd[r][c+3];
          if(rook && rook.type==='r' && rook.color===p.color && !rook.hasMoved){
            if(!bd[r][c+1] && !bd[r][c+2]){
              // simulate king moving to c+1 and c+2 — check if attacked
              const pass1 = simulateAndCheckAttack(bd, r, c, r, c+1, p.color, epTarget);
              const pass2 = simulateAndCheckAttack(bd, r, c, r, c+2, p.color, epTarget);
              if(!pass1 && !pass2){
                add(r, c+2, {capture:false, castle:'king'});
              }
            }
          }
        }
        // queen-side: rook at c-4
        if(c-4 >= 0){
          const rook = bd[r][c-4];
          if(rook && rook.type==='r' && rook.color===p.color && !rook.hasMoved){
            if(!bd[r][c-1] && !bd[r][c-2] && !bd[r][c-3]){
              const pass1 = simulateAndCheckAttack(bd, r, c, r, c-1, p.color, epTarget);
              const pass2 = simulateAndCheckAttack(bd, r, c, r, c-2, p.color, epTarget);
              if(!pass1 && !pass2){
                add(r, c-2, {capture:false, castle:'queen'});
              }
            }
          }
        }
      }
      break;
  }

  // If not pseudoOnly: filter moves that leave king in check (simulate each move using appropriate epTarget for the copy)
  if(!pseudoOnly){
    const legal = [];
    for(const m of moves){
      const copy = cloneBoard(bd);
      const piece = copy[r][c];
      // perform move on copy taking en-passant & castling into account
      let epForCopy = null;
      if(m.enPassant){
        // move pawn, remove captured pawn behind landing square
        copy[m.r][m.c] = piece;
        copy[r][c] = null;
        const capRow = m.r + (piece.color === 'w' ? 1 : -1);
        copy[capRow][m.c] = null;
        // after en-passant there is no en-passant target
        epForCopy = null;
        if(copy[m.r][m.c]) copy[m.r][m.c].hasMoved = true;
      } else if(m.castle){
        // move king
        copy[m.r][m.c] = piece;
        copy[r][c] = null;
        if(m.castle === 'king'){
          const rook = copy[r][c+3];
          copy[r][c+1] = rook;
          copy[r][c+3] = null;
          if(copy[r][c+1]) copy[r][c+1].hasMoved = true;
        } else {
          const rook = copy[r][c-4];
          copy[r][c-1] = rook;
          copy[r][c-4] = null;
          if(copy[r][c-1]) copy[r][c-1].hasMoved = true;
        }
        if(copy[m.r][m.c]) copy[m.r][m.c].hasMoved = true;
        epForCopy = null;
      } else {
        const target = copy[m.r][m.c];
        copy[m.r][m.c] = piece;
        copy[r][c] = null;
        // handle pawn double-step creating ep target for subsequent move in copy
        if(piece.type === 'p' && Math.abs(m.r - r) === 2){
          epForCopy = {r: Math.floor((m.r + r)/2), c: m.c};
        } else {
          epForCopy = null;
        }
        if(copy[m.r][m.c]) copy[m.r][m.c].hasMoved = true;
        // promotion: to queen for legality testing
        if(piece.type === 'p'){
          if((piece.color === 'w' && m.r === 0) || (piece.color === 'b' && m.r === 7)){
            copy[m.r][m.c] = {type:'q', color: piece.color, hasMoved:true};
          }
        }
      }

      // Now check if king is attacked in copy. Pass epForCopy to generateAllMoves so en-passant in copy respected.
      const kingPos = getKingPos(copy, piece.color);
      if(!kingPos) continue;
      const opp = piece.color === 'w' ? 'b' : 'w';
      const oppMoves = generateAllMoves(copy, opp, epForCopy);
      const attacked = oppMoves.some(mm => mm.r===kingPos.r && mm.c===kingPos.c);
      if(!attacked) legal.push(m);
    }
    return legal;
  }

  return moves;
}

// simulate moving king to (tr,tc) and check if that square would be attacked (use epTarget passed)
function simulateAndCheckAttack(bd, r, c, tr, tc, color, epTarget){
  const copy = cloneBoard(bd);
  const king = copy[r][c];
  copy[tr][tc] = king;
  copy[r][c] = null;
  // note: epTarget remains as passed
  const opp = color === 'w' ? 'b' : 'w';
  const oppMoves = generateAllMoves(copy, opp, epTarget);
  return oppMoves.some(mm => mm.r===tr && mm.c===tc);
}

// isInCheck with epTarget awareness
function isInCheck(bd, color, epTarget = null){
  const kingPos = getKingPos(bd, color);
  if(!kingPos) return false;
  const opp = color === 'w' ? 'b' : 'w';
  const oppMoves = generateAllMoves(bd, opp, epTarget);
  return oppMoves.some(m => m.r===kingPos.r && m.c===kingPos.c);
}

// --- UI Rendering ---
function updateUI(){
  boardElem.innerHTML = '';
  const display = [];
  for(let r=0;r<8;r++) for(let c=0;c<8;c++) display.push({r,c});
  if(flipped) display.reverse();

  display.forEach(cell => {
    const r = cell.r, c = cell.c;
    const sq = document.createElement('div');
    const isLight = (r + c) % 2 === 0;
    sq.className = 'square ' + (isLight ? 'light' : 'dark');
    sq.dataset.r = r; sq.dataset.c = c;
    sq.setAttribute('role','button');
    sq.setAttribute('aria-label', `square ${coordToAlgebraic(r,c)}`);

    const coord = document.createElement('div');
    coord.className = 'coord';
    coord.textContent = coordToAlgebraic(r,c);
    sq.appendChild(coord);

    const p = board[r][c];
    if(p){
      const span = document.createElement('div');
      span.textContent = PIECE_SYMBOLS[p.type][p.color];
      span.style.pointerEvents = 'none';
      sq.appendChild(span);
    }

    if(selected && selected.r===r && selected.c===c) sq.classList.add('selected');

    if(legalMoves.some(m=>m.r===r && m.c===c)){
      sq.classList.add('move-target');
      const isCap = !!board[r][c] && board[r][c].color !== (turn);
      if(isCap) sq.classList.add('capture');
      const lm = legalMoves.find(m=>m.r===r && m.c===c);
      if(lm && lm.enPassant && !board[r][c]) sq.classList.add('capture');
    }

    const wK = getKingPos(board,'w'), bK = getKingPos(board,'b');
    if(wK && wK.r===r && wK.c===c && isInCheck(board,'w', enPassantTarget)) sq.classList.add('highlight-check');
    if(bK && bK.r===r && bK.c===c && isInCheck(board,'b', enPassantTarget)) sq.classList.add('highlight-check');

    sq.addEventListener('click', ()=> onSquareClick(r,c));
    boardElem.appendChild(sq);
  });

  turnBadge.textContent = (turn==='w' ? 'White to move' : 'Black to move');
  checkBadge.textContent = isInCheck(board, turn, enPassantTarget) ? 'CHECK!' : '';
}

// --- Interaction ---
function onSquareClick(r,c){
  const piece = board[r][c];

  // if selected and clicked legal move -> execute
  const lm = legalMoves.find(m => m.r===r && m.c===c);
  if(selected && lm){
    doMove(selected.r, selected.c, r, c, lm);
    selected = null; legalMoves = [];
    updateUI();
    return;
  }

  // select own piece
  if(piece && piece.color === turn){
    selected = {r,c};
    legalMoves = generateMovesForPiece(board, r, c, false, enPassantTarget);
    if(legalMoves.length===0) statusText.textContent = 'Tidak ada langkah legal untuk bidak ini.';
    else statusText.textContent = `Selected ${piece.type.toUpperCase()} ${coordToAlgebraic(r,c)} — ${legalMoves.length} move(s)`;
    updateUI();
    return;
  }

  // deselect
  selected = null; legalMoves = [];
  updateUI();
}

// --- Move execution ---
function doMove(fr,fc,tr,tc, moveMeta){
  const piece = board[fr][fc];
  if(!piece) return;

  // history snapshot BEFORE move
  const historyEntry = {
    boardBefore: cloneBoard(board),
    enPassantBefore: enPassantTarget ? {...enPassantTarget} : null,
    move: {from:{r:fr,c:fc}, to:{r:tr,c:tc}, meta: moveMeta ? {...moveMeta} : null}
  };

  let notation = '';

  if(moveMeta && moveMeta.enPassant){
    // en-passant: remove captured pawn behind landing square
    const capRow = tr + (piece.color === 'w' ? 1 : -1);
    const captured = board[capRow][tc];
    board[capRow][tc] = null;
    board[tr][tc] = piece;
    board[fr][fc] = null;
    piece.hasMoved = true;
    enPassantTarget = null; // clear after capture
    notation = `${String.fromCharCode(97+fc)}x${coordToAlgebraic(tr,tc)} e.p.`;
    history.push(historyEntry);
    appendMoveLog(notation);
    finalizeAfterMove();
    return;
  }

  if(moveMeta && moveMeta.castle){
    // castle
    board[tr][tc] = piece;
    board[fr][fc] = null;
    if(moveMeta.castle === 'king'){
      const rookFromC = fc + 3;
      const rookToC = fc + 1;
      const rook = board[fr][rookFromC];
      board[fr][rookToC] = rook;
      board[fr][rookFromC] = null;
      if(rook) rook.hasMoved = true;
      notation = 'O-O';
    } else {
      const rookFromC = fc - 4;
      const rookToC = fc - 1;
      const rook = board[fr][rookFromC];
      board[fr][rookToC] = rook;
      board[fr][rookFromC] = null;
      if(rook) rook.hasMoved = true;
      notation = 'O-O-O';
    }
    piece.hasMoved = true;
    enPassantTarget = null;
    history.push(historyEntry);
    appendMoveLog(notation);
    finalizeAfterMove();
    return;
  }

  // normal or capture
  const target = board[tr][tc];
  const isCapture = !!target;
  board[tr][tc] = piece;
  board[fr][fc] = null;

  // pawn double-step sets enPassantTarget for opponent
  if(piece.type === 'p' && Math.abs(tr - fr) === 2){
    enPassantTarget = {r: Math.floor((tr + fr)/2), c: tc};
  } else {
    enPassantTarget = null;
  }

  // promotion
  if(piece.type === 'p' && ((piece.color === 'w' && tr === 0) || (piece.color === 'b' && tr === 7))){
    piece.hasMoved = true;
    updateUI();
    promptPromotion(piece.color, tr, tc, (newType)=>{
      board[tr][tc] = {type: newType, color: piece.color, hasMoved:true};
      notation = getMoveNotation(piece, fr,fc,tr,tc, isCapture, moveMeta) + `=${newType.toUpperCase()}`;
      history.push(historyEntry);
      appendMoveLog(notation);
      finalizeAfterMove();
    });
    return; // wait for promo choice
  } else {
    piece.hasMoved = true;
    notation = getMoveNotation(piece, fr,fc,tr,tc, isCapture, moveMeta);
  }

  history.push(historyEntry);
  appendMoveLog(notation);
  finalizeAfterMove();
}

// finalize move: switch turn, detect endgame, update UI
function finalizeAfterMove(){
  turn = turn === 'w' ? 'b' : 'w';

  const anyLegal = playerHasAnyLegalMoves(turn);
  if(!anyLegal){
    if(isInCheck(board, turn, enPassantTarget)){
      statusText.textContent = `Checkmate — ${(turn==='w'?'White':'Black')} is checkmated.`;
      appendMoveLog('*** Checkmate');
    } else {
      statusText.textContent = `Stalemate — Draw.`;
      appendMoveLog('*** Stalemate');
    }
  } else {
    if(isInCheck(board, turn, enPassantTarget)){
      statusText.textContent = `${turn==='w' ? 'White' : 'Black'} is in CHECK.`;
    } else {
      statusText.textContent = 'Move completed.';
    }
  }

  if(turn === 'w') moveNumber++;
  selected = null; legalMoves = [];
  updateUI();
}

// simple SAN-ish notation
function getMoveNotation(piece, fr,fc,tr,tc, capture=false, meta=null){
  if(meta && meta.castle){
    return meta.castle === 'king' ? 'O-O' : 'O-O-O';
  }
  if(piece.type === 'p'){
    return (capture ? `${String.fromCharCode(97+fc)}x` : '') + coordToAlgebraic(tr,tc);
  } else {
    const ch = piece.type.toUpperCase();
    return `${ch}${capture ? 'x' : '-'}${coordToAlgebraic(tr,tc)}`;
  }
}

function appendMoveLog(text){
  const el = document.createElement('div');
  el.textContent = `${text}`;
  moveLog.appendChild(el);
  moveLog.scrollTop = moveLog.scrollHeight;
}

function playerHasAnyLegalMoves(color){
  for(let r=0;r<8;r++) for(let c=0;c<8;c++){
    const p = board[r][c];
    if(p && p.color===color){
      const ms = generateMovesForPiece(board, r, c, false, enPassantTarget);
      if(ms.length>0) return true;
    }
  }
  return false;
}

// Promotion UI
function promptPromotion(color, r, c, callback){
  promoOptions.innerHTML = '';
  const pieces = ['q','r','b','n'];
  pieces.forEach(pt=>{
    const btn = document.createElement('button');
    btn.className = 'promo-button';
    btn.innerHTML = `${PIECE_SYMBOLS[pt][color]}<div style="font-size:12px; margin-top:4px;">${pt.toUpperCase()}</div>`;
    btn.addEventListener('click', ()=>{
      promoModal.style.display = 'none';
      callback(pt);
    });
    promoOptions.appendChild(btn);
  });
  promoModal.style.display = 'flex';
}

// Undo
function undo(){
  if(history.length === 0) { statusText.textContent = 'Nothing to undo.'; return; }
  const last = history.pop();
  board = cloneBoard(last.boardBefore);
  enPassantTarget = last.enPassantBefore ? {...last.enPassantBefore} : null;
  // flip turn back
  turn = turn === 'w' ? 'b' : 'w';
  statusText.textContent = 'Undo performed.';
  updateUI();
  if(moveLog.lastChild) moveLog.removeChild(moveLog.lastChild);
}

// UI events
resetBtn.addEventListener('click', ()=> resetBoard());
flipBtn.addEventListener('click', ()=>{ flipped = !flipped; updateUI(); });
undoBtn.addEventListener('click', ()=> undo());
window.addEventListener('keydown', (e)=>{ if(e.key === 'Escape'){ selected = null; legalMoves = []; updateUI(); } });

// init
resetBoard();

</script>
</body>
</html>
