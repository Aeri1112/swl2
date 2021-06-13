import React from "react";
import { Link } from 'react-router-dom';

const Casino = () => {

    return (
        <div>
            <div>
                <Link to="/bj">
                    Blackjack
                </Link>
            </div>
            <div>
                <Link to="/roulette">
                    Roulette
                </Link>
            </div>
        </div>
    );
}

export default Casino;