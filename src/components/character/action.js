import React from 'react';
import Countdown from "../../tools/countdown";

const Action = (props) => {

    const action = () => {
        switch (props.actionid) {
            case 1:
                return "You are still waiting, that the raid gets started.";
            case 2:
                return "You are still in dungeon layer fighting against beasts.";
            case 3:
                return "You are currently sleeping in your apartment.";
            case 4:
                return "You are currently on a quest. Seems funny.";
            case 15:
                return "You are currently fighting in the arena.";
        
            default:
                break;
        }
    }
    return ( 
        <div>
            {
                <div>
                    <div className="m-2"><u>{action()}</u></div>
                    {
                        props.targettime !== 0 &&
                        <Countdown
                            onFinish="finish..."
                            timeTillDate={props.targettime}
                            timeFormat="X"
                        />
                    }  
                </div>
            }
        </div>
     );
}
 
export default Action;