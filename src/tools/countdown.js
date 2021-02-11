import React, {useState, useEffect} from "react";
import moment from "moment";

const Countdown = (props) => {

    const [days, setDays] = useState();
    const [hours, setHours] = useState();
    const [minutes, setMinutes] = useState();
    const [seconds, setSeconds] = useState();
    const [runOf, setRunOf] = useState(false);

    useEffect(() => {
        const interval = setInterval(() => {
            const then = moment(props.timeTillDate, props.timeFormat);
            const now = moment();
            const countdown = moment.duration(then.diff(now));
            setDays(countdown.get("days"));
            setHours(countdown.get("hours"));
            setMinutes(countdown.get("minutes"));
            setSeconds(countdown.get("seconds"));

            if(now > then) {
                setRunOf(true)
                document.title="finish";
            }
            else {
                document.title="" + countdown.get("minutes") + ":" + countdown.get("seconds");
            }
        }, 1000);

        return () => {
            if (interval) {
                clearInterval(interval);
            }
        }
    }, [])

    return (
        <div>
            {
            !runOf ? 
                <div className="countdown-wrapper">
                    {
                        days > 0 && 
                        <div className="countdown-item"><span>{days} days</span></div>
                    }
                    {
                        hours > 0 && 
                        <div className="countdown-item"><span>{hours} hours</span></div>
                    }
                    {
                        minutes > 0 && 
                        <div className="text-center"><span>{minutes} minutes</span></div>
                    }
                    {
                        seconds >= 0 && 
                        <div className="text-center"><span>{seconds} seconds</span></div>
                    }
                </div>
            : props.onFinish
            }
        </div>
    );
}

export default Countdown;