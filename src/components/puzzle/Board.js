import React, { useEffect, useState } from "react";
import Tile from "./Tile";
import { TILE_COUNT, GRID_SIZE, BOARD_SIZE } from "./constants"
import { canSwap, shuffle, swap, isSolved } from "./helpers"
import { POST } from "../../tools/fetch";
import { Button } from "react-bootstrap";

function Board({ imgUrl, win }) {
  const [tiles, setTiles] = useState([...Array(TILE_COUNT).keys()]);
  const [isStarted, setIsStarted] = useState(false);

  const shuffleTiles = () => {
    const shuffledTiles = shuffle(tiles)
    setTiles(shuffledTiles);
  }

  const swapTiles = (tileIndex) => {
    if (canSwap(tileIndex, tiles.indexOf(tiles.length - 1))) {
      const swappedTiles = swap(tiles, tileIndex, tiles.indexOf(tiles.length - 1))
      setTiles(swappedTiles)
    }
  }

  const handleTileClick = (index) => {
    swapTiles(index)
  }

  const handleShuffleClick = () => {
    shuffleTiles()
  }

  const handleStartClick = () => {
    shuffleTiles()
    setIsStarted(true)
  }

  const sendWin = async () => {
    const request = await POST("/quest/puzzle", {win:1});
    if(request) {
      win(request);
    }
  }

  const hasWon = isSolved(tiles)

  useEffect(() => {
    if(hasWon && isStarted) {
      sendWin();
    }
  },[hasWon])

  const pieceWidth = Math.round(BOARD_SIZE / GRID_SIZE);
  const pieceHeight = Math.round(BOARD_SIZE / GRID_SIZE);
  const style = {
    width: BOARD_SIZE,
    height: BOARD_SIZE,
  };

  return (
    <>
      <ul style={style} className="board">
        {tiles.map((tile, index) => (
          <Tile
            key={tile}
            index={index}
            imgUrl={imgUrl}
            tile={tile}
            width={pieceWidth}
            height={pieceHeight}
            handleTileClick={handleTileClick}
          />
        ))}
      </ul>
      {
        !isStarted &&
          <Button variant="secondary" onClick={() => handleStartClick()}>Start</Button>
      }
    </>
  );
}

export default Board;
