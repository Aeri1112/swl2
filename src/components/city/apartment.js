import React, { useEffect, useState } from "react";
import {GET, POST} from "../../tools/fetch";

import Button from 'react-bootstrap/Button';

import PropTypes from 'prop-types';
import { makeStyles } from '@material-ui/core/styles';
import LinearProgress from '@material-ui/core/LinearProgress';
import Typography from '@material-ui/core/Typography';
import Box from '@material-ui/core/Box';

function LinearProgressWithLabel(props) {
  return (
    <Box display="flex" alignItems="center">
      <Box width="100%" mr={1}>
        <LinearProgress variant="determinate" {...props} />
      </Box>
      <Box minWidth={35}>
        <Typography variant="body2" color="textSecondary">{`${Math.round(
          props.value,
        )}%`}</Typography>
      </Box>
    </Box>
  );
}

LinearProgressWithLabel.propTypes = {
  /**
   * The value of the progress indicator for the determinate and buffer variants.
   * Value between 0 and 100.
   */
  value: PropTypes.number.isRequired,
};

const useStyles = makeStyles({
  root: {
    width: '100%',
  },
});

const calculateTimeLeft = (difference) => {

    let timeLeft = {};

    if (difference > 0) {
      timeLeft = {
        days: Math.floor(difference / (60 * 60 * 24)),
        hours: Math.floor((difference / (60 * 60)) % 24),
        minutes: Math.floor((difference / 60) % 60),
        seconds: Math.floor((difference) % 60),
      };
    }

    return timeLeft;
  };

const Apartment = () => {

    const [sleep, setSleep] = useState();
    const [open, setOpen] = useState();
    const [loading, setLoading] = useState();
    const [duration, setDuration] = useState("");
    const [timeRem, setTimeRem] = useState();
    const [sleepFor, setSleepFor] = useState();
    const [displayTime, setDisplayTime] = useState();
    const classes = useStyles();
    const [progress, setProgress] = useState();

    const getData = async () => {
        setLoading(true);
        const response = await GET(`/city/apa`);
        if(response) {
            if(response.sleep === true) {
                setTimeRem(response.timer)
                setProgress(response.progress)
                setSleepFor(response.duration)
            }
            setSleep(response.sleep);
            setOpen(response.open);
            setLoading(false);
        }
    }

    const handleInput = (e) => {
        const regex=/^[0-9]+$/;
        if (e.target.value.match(regex) && e.target.value > 0)
        {
            setDuration(+e.target.value);
        }
        else {
            setDuration("");
        }
    }

    const handleClick = () => {
        const request = POST("/city/apa", {duration:duration});
        if(request) {
            getData();
        }
    }

    useEffect(() => {
        getData();
    }, []);

    useEffect(() => {
        // exit early when we reach 0
        if (!timeRem) return;
    
        // save intervalId to clear the interval when the
        // component re-renders
        const intervalId = setInterval(() => {
          setTimeRem(timeRem - 1);
          setDisplayTime(calculateTimeLeft(timeRem));
          setProgress((sleepFor - timeRem) * 100 / sleepFor);
        }, 1000);
    
        // clear interval on re-render to avoid memory leaks
        return () => clearInterval(intervalId);
        // add timeLeft as a dependency to re-rerun the effect
        // when we update it
      });

    return(
        <div>
            {
                loading === false ?
                    !sleep && open ?
                    <form>
                        <input
                            type="text"
                            value={duration}
                            id="duration"
                            onChange={handleInput}
                            required
                            name="duration"
                        />
                        {" "}
                        <Button
                            variant="primary"
                            disabled={duration > 0 && duration <= 6 ? false : true}
                            onClick={handleClick}
                        >
                            sleep
                        </Button>
                    </form>
                    : null
                : null
            }
            {
                loading === false ?
                sleep && open ?
                        <div>
                        {displayTime ? <div> 
                                        <div>sleeping for</div>
                                        <div>{displayTime.hours + " hours " + displayTime.minutes + " minutes " + displayTime.seconds + " seconds"}</div>
                                        <div className={classes.root}>
                                            <LinearProgressWithLabel value={progress} />
                                        </div>
                                        </div>
                        : "loading..."
                        }
                        </div>
                    : null
                : "loading..."
            }
            {
                loading === false ?
                !open && <div>You are currently doing something else</div> : null
            }
        </div>
    );
}

export default Apartment;