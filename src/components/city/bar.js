import React, {useState, useEffect} from "react"
import { useSelector, useDispatch } from "react-redux";
import { useHistory } from "react-router-dom";
import {GET} from "../../tools/fetch";
import Bars from "../bars";
import CheckQuest from "../quest/checkQuest";

import {characterState__setOverviewData} from "../../redux/actions/characterActions";

const Bar = () => {

    const history = useHistory();

    const [loading, setLoading] = useState();
    const [reloadM, setReloadM] = useState(false);
    const [reloadH, setReloadH] = useState();
    const [reloadE, setReloadE] = useState();

    const dispatch = useDispatch();
    const data = useSelector(state => state.skills.skills);

    const clickMana = async (mana) => {
        setReloadM(true)
        await GET(`/city/bar/buy/${mana}`)
        try {
            const response = await GET('/city/bar')
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
            const response = await GET('/city/bar')
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
            const response = await GET('/city/bar')
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
            const response = await GET('/city/bar')
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
    <div>
        {
            loading === false && data.quest[0] === 1 &&
            <CheckQuest 
                details={data.quest[1]}
                refresh={loadData}
            />
        }
        {
            loading === false && (data.char.location2 === "l" || data.char.location2 === "r") && data.quest === 0 ?
                history.push("/layer2")
            :   null
        }
        {
        loading === false && data.char.actionid === 0 && data.char.targetid === 0 && data.char.targettime === 0
        && (data.quest === 0 || (data.quest[1].quest_id === "1" && (data.quest[1].step_id === "4" || data.quest[1].step_id === "5"))) ?
            <div>
                <div id='pic' className='container-fluid p-0 m-0 position-relative'>
                    <img className="w-100" style={{"verticalAlign":"sub"}} src={require(`../../images/city/bar1.jpg`) } alt="" />
                        {/*der weg in den versteckten layer*/}
                        <div
                            className="position-absolute"
                            style={{top: "25%", left: "75%", width: "6%", height:"23%"}}
                            onClick={() => history.push("/layer2")}
                        />
                    <div className='position-absolute align-text-top' style={{top: "27.5%", left: "44%", width:"11%"}}>
                        <div className="neon pink font-weight-bolder" style={{fontSize: 'calc(5px + 5 * ((100vw - 360px) / 640))'}}>
                            <div
                                style={{cursor:"pointer",marginBottom:"-5px"}}
                                onClick={
                                    data.skills.mana_width < 100 &&
                                    data.char.cash >= 10 &&
                                    !reloadM ? 
                                        () => clickMana("m")
                                    :
                                        null}
                            >
                                Mana(50) {reloadM && <small>&uarr;</small>}
                            </div>
                            <div><small>10 Cr.</small></div>
                        </div>
                    </div>
                    <div className='position-absolute align-text-top' style={{top: "36%", left: "44%", width:"11%"}}>
                        <div className="neon pink font-weight-bolder" style={{fontSize: 'calc(5px + 5 * ((100vw - 360px) / 640))'}}>
                            <div
                                style={{cursor:"pointer"}}
                                onClick={
                                    data.skills.mana_width < 100 &&
                                    data.char.cash >= (Math.ceil((data.skills.max_mana - data.char.mana) / 50) * 10) &&
                                    !reloadM ? 
                                        () => clickMana("Bm")
                                    :
                                        null}
                            >
                                Mana(full) {reloadM && <small>&uarr;</small>}
                            </div>
                        </div>
                    </div>
                    <div className='position-absolute align-text-top' style={{top: "42%", left: "44%", width:"11%"}}>
                        <div className="neon pink font-weight-bolder" style={{fontSize: 'calc(5px + 5 * ((100vw - 360px) / 640))'}}>
                            <div 
                                style={{cursor:"pointer", marginBottom:"-5px"}}
                                onClick={
                                    data.skills.health_width < 100 && 
                                    !reloadH ? 
                                        clickHealth 
                                    : 
                                        null}
                            >
                                Health {reloadH && <small>&uarr;</small>} 
                            </div>
                            <small>for free!</small>
                        </div>
                    </div>
                    <div className='position-absolute align-text-top' style={{top: "28.5%", left: "22.5%", width:"11%"}}>
                        <div className="neon pink font-weight-bolder" style={{fontSize: 'calc(5px + 5 * ((100vw - 360px) / 640))'}}>
                        <span
                            style={{cursor:"pointer"}} 
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
                    loading === false && data.quest === 0 ?
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
        : loading === false && data.char.actionid !== 0 && data.char.targetid !== 0 && data.char.targettime !== 0 && data.quest === 0 ?
            <div className="align-center">You are doing something else</div>
        : loading === false && data.quest === 0 &&
                "loading..."
        }
    </div>
    );
}

export default Bar;