import React, {useState, useEffect} from "react";
import {useDispatch, useSelector} from "react-redux";
import {POST, GET} from "../tools/fetch";
import { characterState__setOverviewData } from "../redux/actions/characterActions";

import Abi from "./ability";

import Row from "react-bootstrap/Row";
import CheckQuest from "./quest/checkQuest";

const Abis = () => {

    const data = useSelector(state => state.skills);
    const dispatch = useDispatch();
    const [loading, setLoading] = useState();
    const [loadTrainAbi, setLoadTrainAbi] = useState(false);
    const [skillable, setSkillable] = useState(false);

    const quest = useSelector(state => state.skills.skills.quest);

    const loadAbi = async () => {
        setLoading(true);
        try{
            const response = await GET('/character/abilities')
            if(response) {
                dispatch(characterState__setOverviewData(response))
                if(response.rsp > 0) {
                    setSkillable(true);
                }
            }
        }
        finally {
            setLoading(false);
        }
    }

    const skillAbi = async (abi) => {
        setLoadTrainAbi(abi);
        try {
            const response = await POST('/character/abilities',{train:abi})
            if(response) {
                dispatch(characterState__setOverviewData(response))
                if(response.rsp <= 0) {
                    setSkillable(false);
                }
            }
        }
        finally {          
            setLoadTrainAbi(false);
        }

    }

    useEffect(() => {
        loadAbi();
    }, []);

    return (
        <>
        {
            loading === false && quest[0] === 1 &&
            <div>
                <CheckQuest
                    details={quest[1]}
                    refresh={loadAbi}
                />
            </div>
        }
        {
            loading === false && (quest === 0 || (quest[1].quest_id === "1" && (quest[1].step_id === "6" || quest[1].step_id === "7"))) ?
                <Row>
                    <Abi 
                        abi="cns" 
                        basePoints={data.skills.skills[0].cns} 
                        tempBonus={data.skills.tempBonus["tmpcns"]} 
                        onClick={skillAbi}
                        skillable={skillable}
                        loadTrainAbi={loadTrainAbi}
                    />
                    <Abi 
                        abi="agi" 
                        basePoints={data.skills.skills[0].agi} 
                        tempBonus={data.skills.tempBonus["tmpagi"]} 
                        onClick={skillAbi}
                        skillable={skillable}
                        loadTrainAbi={loadTrainAbi}
                    />
                    <Abi 
                        abi="tac" 
                        basePoints={data.skills.skills[0].tac} 
                        tempBonus={data.skills.tempBonus["tmptac"]} 
                        onClick={skillAbi}
                        skillable={skillable}
                        loadTrainAbi={loadTrainAbi}
                    />
                    <Abi
                        abi="dex" 
                        basePoints={data.skills.skills[0].dex} 
                        tempBonus={data.skills.tempBonus["tmpdex"]} 
                        onClick={skillAbi}
                        skillable={skillable}
                        loadTrainAbi={loadTrainAbi}
                    />
                    <Abi
                        abi="spi" 
                        basePoints={data.skills.skills[0].spi} 
                        tempBonus={data.skills.tempBonus["tmpspi"]} 
                        onClick={skillAbi}
                        skillable={skillable}
                        loadTrainAbi={loadTrainAbi}
                    />
                    <Abi 
                        abi="itl" 
                        basePoints={data.skills.skills[0].itl} 
                        tempBonus={data.skills.tempBonus["tmpitl"]} 
                        onClick={skillAbi}
                        skillable={skillable}
                        loadTrainAbi={loadTrainAbi}
                    />
                    <Abi 
                        abi="lsa" 
                        basePoints={data.skills.skills[0].lsa} 
                        tempBonus={data.skills.tempBonus["tmplsa"]} 
                        onClick={skillAbi}
                        skillable={skillable}
                        loadTrainAbi={loadTrainAbi}
                    />
                    <Abi 
                        abi="lsd" 
                        basePoints={data.skills.skills[0].lsd} 
                        tempBonus={data.skills.tempBonus["tmplsd"]} 
                        onClick={skillAbi}
                        skillable={skillable}
                        loadTrainAbi={loadTrainAbi}
                    />
                </Row>
            : null
        }
        </>
    );
}
  
  export default Abis;