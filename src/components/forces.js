import React, {useState, useEffect} from "react";
import {useDispatch, useSelector} from "react-redux";
import {POST, GET} from "../tools/fetch";
import { characterState__setOverviewData } from "../redux/actions/characterActions";
import Force from "./force";

import Row from "react-bootstrap/Row";

const Forces = () => {

    const data = useSelector(state => state.skills);
    const dispatch = useDispatch();
    const [loading, setLoading] = useState();
    const [loadTrainForce, setLoadTrainForce] = useState(false);
    const [skillable, setSkillable] = useState(false);
    let range;

    const loadForce = async () => {
        setLoading(true);
        try{
            const response = await GET('/character/forces')
            if(response) {
                dispatch(characterState__setOverviewData(response))
                if(response.rfp > 0) {
                    setSkillable(true);
                }
            }
        }
        finally {
            setLoading(false);
        }
    }

    const skillForce = async (force) => {
        setLoadTrainForce(force);
        try {
            const response = await POST('/character/forces',{train:force})
            if(response) {
                dispatch(characterState__setOverviewData(response))
                if(response.rfp <= 0) {
                    setSkillable(false);
                }
            }
        }
        finally {          
            setLoadTrainForce(false);
        }

    }

    useEffect(() => {
        loadForce();
    }, []);

    if(loading === false) {
        if(data.skills.skills.fabso > 0 || data.skills.skills.fprot > 0) {
            range = 4;
        }
        else if (data.skills.skills.fheal > 0 || data.skills.skills.fteam > 0) {
            range = 3;
        }
        else if (data.skills.skills.fconf > 0 || data.skills.skills.fblin > 0) {
            range = 2;
        }
        else if (data.skills.skills.fproj > 0 || data.skills.skills.fpers > 0) {
            range = 1;
        }
        
        if (data.skills.skills.fdead > 0 || data.skills.skills.fdest > 0) {
            range = -4;
        }
        else if (data.skills.skills.fthun > 0 || data.skills.skills.fchai > 0) {
            range = -3;
        }
        else if (data.skills.skills.fdrai > 0 || data.skills.skills.fgrip > 0) {
            range = -2;
        }
        else if (data.skills.skills.frage > 0 || data.skills.skills.fthro > 0) {
            range = -1;
        }
    }

        return (
            <>
            {
            loading === false ?
                <Row>
                    <Force
                        f="fspee"
                        basePoints={data.skills.skills.fspee} 
                        tempBonus={data.skills.tempBonusForces["tmpfspee"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={true}
                    />
                    <Force
                        f="fjump"
                        basePoints={data.skills.skills.fjump} 
                        tempBonus={data.skills.tempBonusForces["tmpfjump"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={true}
                    />
                    <Force
                        f="fpush"
                        basePoints={data.skills.skills.fpush} 
                        tempBonus={data.skills.tempBonusForces["tmpfpush"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={true}
                    />
                    <Force
                        f="fpull"
                        basePoints={data.skills.skills.fpull} 
                        tempBonus={data.skills.tempBonusForces["tmpfpull"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={true}
                    />
                    <Force
                        f="fseei"
                        basePoints={data.skills.skills.fseei} 
                        tempBonus={data.skills.tempBonusForces["tmpfseei"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={true}
                    />
                    <Force
                        f="fsabe"
                        basePoints={data.skills.skills.fsabe} 
                        tempBonus={data.skills.tempBonusForces["tmpfsabe"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={data.skills.skills.level > 75 ? true : false}
                    />

                    <Force
                        f="fpers"
                        basePoints={data.skills.skills.fpers} 
                        tempBonus={data.skills.tempBonusForces["tmpfpers"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range <= -4 ? false : true}
                    />
                    <Force
                        f="fproj"
                        basePoints={data.skills.skills.fproj} 
                        tempBonus={data.skills.tempBonusForces["tmpfproj"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range <= -4 ? false : true}
                    />
                    <Force
                        f="fblin"
                        basePoints={data.skills.skills.fblin} 
                        tempBonus={data.skills.tempBonusForces["tmpfblin"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range <= -3 ? false : true}
                    />
                    <Force
                        f="fconf"
                        basePoints={data.skills.skills.fconf} 
                        tempBonus={data.skills.tempBonusForces["tmpfconf"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range <= -3 ? false : true}
                    />
                    <Force
                        f="fheal"
                        basePoints={data.skills.skills.fheal} 
                        tempBonus={data.skills.tempBonusForces["tmpfheal"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range <= -2 ? false : true}
                    />
                    <Force
                        f="fteam"
                        basePoints={data.skills.skills.fteam} 
                        tempBonus={data.skills.tempBonusForces["tmpfteam"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range <= -2 && data.skills.skills.fheal <= 0 ? false : true}
                    />
                    <Force
                        f="fprot"
                        basePoints={data.skills.skills.fprot} 
                        tempBonus={data.skills.tempBonusForces["tmpfprot"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range <= -1 ? false : true}
                    />
                    <Force
                        f="fabso"
                        basePoints={data.skills.skills.fabso} 
                        tempBonus={data.skills.tempBonusForces["tmpfabso"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range <= -1 ? false : true}
                    />

                    <Force
                        f="fthro"
                        basePoints={data.skills.skills.fthro} 
                        tempBonus={data.skills.tempBonusForces["tmpfthro"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range >= 4 ? false : true}
                    />
                    <Force
                        f="frage"
                        basePoints={data.skills.skills.frage} 
                        tempBonus={data.skills.tempBonusForces["tmpfrage"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range >= 4 ? false : true}
                    />
                    <Force
                        f="fgrip"
                        basePoints={data.skills.skills.fgrip} 
                        tempBonus={data.skills.tempBonusForces["tmpfgrip"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range >= 3 ? false : true}
                    />
                    <Force
                        f="fdrai"
                        basePoints={data.skills.skills.fdrai} 
                        tempBonus={data.skills.tempBonusForces["tmpfdrai"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range >= 3 ? false : true}
                    />
                    <Force
                        f="fthun"
                        basePoints={data.skills.skills.fthun} 
                        tempBonus={data.skills.tempBonusForces["tmpfthun"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range >= 2 ? false : true}
                    />
                    <Force
                        f="fchai"
                        basePoints={data.skills.skills.fchai} 
                        tempBonus={data.skills.tempBonusForces["tmpfchai"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range >= 2 && data.skills.skills.fthun <= 0 ? false : true}
                    />
                    <Force
                        f="fdest"
                        basePoints={data.skills.skills.fdest} 
                        tempBonus={data.skills.tempBonusForces["tmpfdest"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range >= 1 ? false : true}
                    />
                    <Force
                        f="fdead"
                        basePoints={data.skills.skills.fdead} 
                        tempBonus={data.skills.tempBonusForces["tmpfdead"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={range >= 1 ? false : true}
                    />

                    <Force
                        f="frvtl"
                        basePoints={data.skills.skills.frvtl} 
                        tempBonus={data.skills.tempBonusForces["tmpfrvtl"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={data.skills.skills.level >= 75 ? true : false}
                    />
                    <Force
                        f="ftnrg"
                        basePoints={data.skills.skills.ftnrg} 
                        tempBonus={data.skills.tempBonusForces["tmpftnrg"]} 
                        onClick={skillForce}
                        skillable={skillable}
                        loadTrainForce={loadTrainForce}
                        available={data.skills.skills.level >= 75 ? true : false}
                    />
                </Row>
                : null
            }
            </>
        );
}
  
  export default Forces;