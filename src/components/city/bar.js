import React, {useState, useEffect} from "react"
import { useSelector, useDispatch } from "react-redux";
import {GET} from "../../tools/fetch";
import Bars from "../bars";

import {characterState__setOverviewData} from "../../redux/actions/characterActions";

const Bar = () => {

    const [loading, setLoading] = useState();
    const [reloadM, setReloadM] = useState(false);
    const [reloadH, setReloadH] = useState();
    const [reloadE, setReloadE] = useState();

    const dispatch = useDispatch();
    const data = useSelector(state => state.skills.skills);

    const clickMana = async () => {
        setReloadM(true)
        await GET("/city/bar/buy/m")
        try {
            const response = await GET('/character/overview')
            if (response) {
                dispatch(characterState__setOverviewData(response))
            }
        } catch (e) {
            console.error(e)
        } finally {
            // finally wird immer ausgefuehrt.
            // dadurch wird der state auch immer danach false gesetzt.
            const TimeoutId = setTimeout(() => {setReloadM(false)},350)
            return () => clearTimeout(TimeoutId);
        }
    }

    const clickHealth = async () => {
        setReloadH(true)
        await GET("/city/bar/buy/h")
        try {
            const response = await GET('/character/overview')
            if (response) {
                dispatch(characterState__setOverviewData(response))
            }
        } catch (e) {
            console.error(e)
        } finally {
            // finally wird immer ausgefuehrt.
            // dadurch wird der state auch immer danach false gesetzt.
            const TimeoutId = setTimeout(() => {setReloadH(false)},350)
            return () => clearTimeout(TimeoutId);
        }
    }

    const clickEnergy = async () => {
        setReloadE(true)
        await GET("/city/bar/buy/e")
        try {
            const response = await GET('/character/overview')
            if (response) {
                dispatch(characterState__setOverviewData(response))
            }
        } catch (e) {
            console.error(e)
        } finally {
            // finally wird immer ausgefuehrt.
            // dadurch wird der state auch immer danach false gesetzt.
            const TimeoutId = setTimeout(() => {setReloadE(false)},350)
            return () => clearTimeout(TimeoutId);
        }
    }

    const loadData = async() => {
        try {
            setLoading(true)
            const response = await GET('/character/overview')
            if (response) {
                dispatch(characterState__setOverviewData(response))
            }
        } catch (e) {
            console.error(e)
        } finally {
            // finally wird immer ausgefuehrt.
            // dadurch wird der state auch immer danach false gesetzt.
            setLoading(false)
        }
    }

    useEffect(() => {
        loadData();
    }, []); 

    return(
        loading === false && data.char.actionid === 0 && data.char.targetid === 0 && data.char.targettime === 0 ?
            <div>
                <div id='pic' className='container-fluid p-0 m-0 position-relative'>
                    <img className="w-100" style={{"verticalAlign":"sub"}} src={require(`../../images/city/bar1.jpg`) } alt="" />
                    <div className='position-absolute align-text-top' style={{top: "27.5%", left: "44%", width:"11%"}}>
                        <div className="neon pink font-weight-bolder" style={{fontSize: 'calc(5px + 5 * ((100vw - 360px) / 640))'}}>
                            <span
                                onClick={
                                    data.skills.mana_width < 100 &&
                                    data.char.cash >= 10 &&
                                    !reloadM ? 
                                        clickMana
                                    :
                                        null}
                            >
                                Mana {reloadM && <small>&uarr;</small>}
                            </span> {<br/>}
                            <small>10 Cr.</small>
                        </div>
                    </div>
                    <div className='position-absolute align-text-top' style={{top: "39%", left: "44%", width:"11%"}}>
                        <div className="neon pink font-weight-bolder" style={{fontSize: 'calc(5px + 5 * ((100vw - 360px) / 640))'}}>
                            <span 
                                onClick={
                                    data.skills.health_width < 100 && 
                                    !reloadH ? 
                                        clickHealth 
                                    : 
                                        null}
                            >
                                Health {reloadH && <small>&uarr;</small>} 
                            </span> {<br/>}
                            <small>for free!</small>
                        </div>
                    </div>
                    <div className='position-absolute align-text-top' style={{top: "28.5%", left: "22.5%", width:"11%"}}>
                        <div className="neon pink font-weight-bolder" style={{fontSize: 'calc(5px + 5 * ((100vw - 360px) / 640))'}}>
                        <span 
                            onClick={
                                data.skills.energy_width < 100 && 
                                data.char.cash >= 150 &&
                                !reloadE ? 
                                    clickEnergy 
                                : 
                                    null}
                        >
                            Energy {reloadE && <small>&uarr;</small>} 
                        </span> {<br/>}
                            <small>150 Cr.</small>
                        </div>
                    </div>
                </div>
                {
                    loading === false ?
                    <div>
                        <div className="text-center">
                            {data.char.cash + " Credits"}
                        </div>
                        <Bars type={"Health"} width={ data.skills.health_width + "%"} data={data.char.health} bg={"bg-danger"}/>
                        <Bars type={"Mana"} width={ data.skills.mana_width + "%"} data={data.char.mana} bg={"bg-primary"}/>
                        <Bars type={"Energy"} width={ data.skills.energy_width + "%"} data={data.char.energy} bg={"bg-success"}/>
                    </div>
                    : <div className="text-center">loading...</div> 
                }
                
            </div>
        : loading === false && data.char.actionid !== 0 && data.char.targetid !== 0 && data.char.targettime !== 0 ?
            <div className="align-center">You are doing something else</div>
        :
                "loading..."
    );
}

export default Bar;