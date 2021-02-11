import React, {useState, useEffect} from "react";
import { useSelector, useDispatch } from "react-redux";
import {GET} from "../tools/fetch";
import Alliance from "./alliance";
import Rank from "./ranks";
import Bars from "./bars";
import Row from "react-bootstrap/Row";
    
import {characterState__setOverviewData, characterState__setMaster} from "../redux/actions/characterActions";

const Overview = () => {

    const [loading, setLoading] = useState();
    const dispatch = useDispatch();
    const data = useSelector(state => state.skills.skills);
    const master = useSelector(state => state.skills.master);

    const loadData = async() => {
        try {
            setLoading(true)
            const response = await GET('/character/overview')
            if (response) {
                dispatch(characterState__setOverviewData(response))

                const masterResponse = await GET(`/character/user?id=${response.char.masterid}`)
                if (masterResponse) {
                    dispatch(characterState__setMaster(masterResponse))
                }
            }
            setLoading(false)
        } 
        catch (e) {
            return
        }
    }

    useEffect(() => {
        loadData();
    }, []); 

    return (
        <>
        {
            loading === false ? 
                <div>
                    <Row>
                        <div className="col-6">
                            <div>
                                Name: { data.char.username }
                            </div>
                            <div>
                                Spezies: { data.char.species }
                            </div>
                            <div>
                                Alter: { data.char.age } Jahre
                            </div>
                            <div>
                                Größe: { data.char.size } cm
                            </div>
                            <div>
                                Heimatwelt: { data.char.homeworld }
                            </div>
                        </div> 
                        <div className="col-6">
                            <div>
                                Level: { data.skills.level }
                            </div>
                            <div>
                                Allianz: {data.char.alliance !== "0" ? <Alliance/> : "no"}
                            </div>
                            <div>
                                Rang: {<Rank rank={data.char.rank} side={data.skills.side}/>}
                            </div>
                            <div>
                                {
                                    data.skills.level > 75 ? "Schüler: " : "Meister: "
                                }
                                { master.user.char.username + " (" + master.user.skills.level + ")" }
                            </div>
                        </div>
                    </Row>

                    <Bars type={"Ausrichtung"} width={"100%"} data={data.side.side} perc={data.side.perc} white={data.side.white_begin} bg={""}/>
                    <Bars type={"Health"} width={ data.skills.health_width + "%"} data={data.char.health} bg={"bg-danger"}/>
                    <Bars type={"Mana"} width={ data.skills.mana_width + "%"} data={data.char.mana} bg={"bg-primary"}/>
                    <Bars type={"Energy"} width={ data.skills.energy_width + "%"} data={data.char.energy} bg={"bg-success"}/>
                    <Bars type={"Experience"} width={data.skills.level_width + "%"} data={data.skills.xp} bg={"bg-warning"}/>
                </div>
           : "loading..."
        }
        </>
    )
}

    

export default Overview;