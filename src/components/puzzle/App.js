import React from "react";
import Board from "./Board";
import "./index.css";

function Puzzle(props) {
  const img = `images/${props.img}.png`

  return (
    <div className="App">
      <Board imgUrl={img} win={props.win}/>
    </div>
  );
}

export default Puzzle;
