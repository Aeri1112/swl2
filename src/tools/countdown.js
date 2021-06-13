import React, {useState, useEffect} from "react";
import moment from "moment";

const Countdown = (props) => {

    const [days, setDays] = useState();
    const [hours, setHours] = useState();
    const [minutes, setMinutes] = useState();
    const [seconds, setSeconds] = useState();
    const [runOf, setRunOf] = useState(false);

    useEffect(() => {
        const then = moment(props.timeTillDate, props.timeFormat);
        const now = moment();
        const countdown = moment.duration(then.diff(now));
        setDays(countdown.get("days"));
        setHours(countdown.get("hours"));
        const min = countdown.get("minutes") < 10 ? "0" + countdown.get("minutes") : countdown.get("minutes");
        setMinutes(countdown.get("minutes"));
        const sec = countdown.get("seconds") < 10 ? "0" + countdown.get("seconds") : countdown.get("seconds");
        setSeconds(countdown.get("seconds"));
        if(now > then) {
            setRunOf(true)
            document.title="finish";
        }
        else {
            document.title= min + ":" + sec;
        }

        const interval = setInterval(() => {
            const then = moment(props.timeTillDate, props.timeFormat);
            const now = moment();
            const countdown = moment.duration(then.diff(now));
            setDays(countdown.get("days"));
            setHours(countdown.get("hours"));
            const min = countdown.get("minutes") < 10 ? "0" + countdown.get("minutes") : countdown.get("minutes");
            setMinutes(countdown.get("minutes"));
            const sec = countdown.get("seconds") < 10 ? "0" + countdown.get("seconds") : countdown.get("seconds");
            setSeconds(countdown.get("seconds"));

            if(now > then) {                   
                setRunOf(true);
                document.title="finish";
            }
            else {
                document.title= min + ":" + sec;
            }
        }, 1000);

        return () => {
            if (interval) {
                clearInterval(interval);
            }
        }
    }, [])

    useEffect(() => {
        if((props.onFinish instanceof Function) === true && runOf === true) {
            props.onFinish()
        }
    },[runOf])

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
            : (props.onFinish instanceof Function) === false &&
                    props.onFinish
            }
        </div>
    );
}

export default Countdown;