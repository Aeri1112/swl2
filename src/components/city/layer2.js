import React, {useState, useEffect} from "react";

import Bars from "../../components/bars";
import {GET,POST} from "../../tools/fetch";
import Countdown from "../../tools/countdown";

import Button from "react-bootstrap/Button";
import Modal from "react-bootstrap/Modal";
import CheckQuest from "../quest/checkQuest";

const Layer2 = (props) => {

    const [loading, setLoading] = useState();
    const [response, setResponse] = useState();
    const [report, setReport] = useState();
    const [carouselElements, setCarouselElements] = useState();
    const [enemy, setEnemy] = useState("");

    const [door, setDoor] = useState();

    //if you cannot enter the layer
    const [open, setOpen] = useState(false);
    const [redirect, setRedirect] = useState();

    //indicator for fighting
    const [fighting, setFighting] = useState(false);

    const loadData = async () => {
        setLoading(true);
        try {
            const response = await GET('/city/layer2')
            if (response) {
                setResponse(response)

                if(response.char.item_hand === 0) {
                    setOpen("weapon")
                    setRedirect("inventory")
                }
                else if (response.char.health <= 10 && response.char.actionid != 2) {
                    setOpen("health")
                    setRedirect("bar")
                }
                else if (response.char.energy < 1 && response.char.actionid != 2) {
                    setOpen("energy")
                    setRedirect("bar")
                }
                else if (response.busy === true) {
                    setOpen("busy")
                    setRedirect("overview")
                }

                if(response.doing === "yes") {

                    setEnemy(response.char.location2);

                    setCarouselElements(
                        <img className="d-block mx-auto img" src={require(`../../images/npc/${response.char.location2}.jpg`) } alt="" />
                    )
                }
                else {
                    setCarouselElements(
                        <>
                            <div className="col-md-6" onClick={() => setEnemy("l")}>
                                <img style={{cursor:"pointer"}} className="shadow-lg border rounded-lg d-block mx-auto img" src={require(`../../images/npc/l.jpg`) } alt="" />
                                <div className="mt-1 text-center"><b>Deena Brin</b></div>
                                <Bars type={"Strength"} width="50%" data="mid" bg={"bg-danger"}/>
                            </div>
                            <div className="col-md-6" onClick={() => setEnemy("r")}>
                                <img style={{cursor:"pointer"}} className="shadow-lg border rounded-lg d-block mx-auto img" src={require(`../../images/npc/r.jpg`) } alt="" />
                                <div className="mt-1 text-center"><b>X'jan Mariell</b></div>
                                <Bars type={"Strength"} width="70%" data="high" bg={"bg-danger"}/>
                            </div>
                        </>
                    )
                }
            }
            const openDoor = await GET(`/quest/quest/2`)
            if(openDoor) {
                setDoor(openDoor.status)
            }
        } catch (e) {
            console.error(e)
        } finally {
            // finally wird immer ausgefuehrt.
            // dadurch wird der state auch immer danach false gesetzt.
            setLoading(false)
        }
    }

    const handleReport = async () => {
        setFighting(true);
        const response = await GET("/city/layer2/fight")
        if(response.fight_report) {
            setReport(response.fight_report);
        }
        else if(response.doing === "yes") {
            const report = await GET("/city/layer2/view");
            if(report.fight_report) {
                setReport(report.fight_report);
            }
        }
        setFighting(false);
    }

    const handleAttack = async () => {
        await POST("/city/layer2/attack", {enemy:enemy,fight:"y"})
        loadData();
    }

    const handleCast = async (force) => {
        if(response.doing !== "yes") {
            switch (force) {
                case "fseei":
                    const response = await POST("/city/layer2/cast",{cast:1})
                    setResponse(response)
                    break;
            
                default:
                    break;
            }
        }
    }

    useEffect(() => {
        loadData();
    }, [])

    useEffect(() => {
        if (enemy !== "") {
            setCarouselElements(
                <div className="position-relative">
                    <img className="mx-auto img" src={require(`../../images/npc/${enemy}.jpg`)} alt="" />
                    {
                        response.doing !== "yes" &&
                        <div className="text-center position-absolute" style={{top:"90%", width:"100%", height:"40px"}}>
                            <Button className="text-danger" variant="link" onClick={handleAttack}>
                                attack
                            </Button>
                        </div>
                    }
                </div>
            )
        }
    },[enemy, response])

    return (
        <div>
            {
                open && 
                <Modal show={true} onHide={() => props.history.push(`/${redirect}`)}>
                    <Modal.Header closeButton>
                        <Modal.Title>You can't enter the Layer</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        {
                            open === "weapon" ? "Better equip a weapon!"
                            : open === "health" || open === "energy" ? "Better take a drink!"
                            : open === "busy" ? "You fighting somewhere else!"
                            : null
                        }
                    </Modal.Body>
                </Modal>
            }
            {
                loading === false && response.quest[0] === 1 &&
                <div>
                    <CheckQuest
                        details={response.quest[1]}
                        refresh={loadData}
                    />
                </div>
            }
            {
                loading === false && !report && door === "done" && enemy === "" &&
                <div>
                    Als du dich an der vermeindlich geschlossenen Türe umschaust, hörst du ein kurzes dumpfes Geräusch hinter der Tür. {<br/>}
                    Du klopfst an. Völlig unerwartet wird dir prompt die Tür geöffnet.{<br/>}
                    Etwas ungläubig schaust du dich um ehe der Boss dich nach deinem Gegner frägt.{<br/>}
                    Du befindest dich wohl in einer illegalen Untergrundarena.{<br/>}

                    <div className="m-2 text-center"><b>Wähle deinen Gegner</b></div>
                </div>
            }
            {
                loading === false && !report && door === "done" &&
                    <div>
                        <div className="container-fluid p-0 m-0">
                            <div className="row justify-content-center">
                                {carouselElements}
                            </div>
                        </div>
                        {
                            enemy !== "" && response.doing !== "yes" &&
                            <div className="text-center">
                                <Button variant="outline-secondary" onClick={() => (setEnemy(""), loadData())}>back</Button>
                            </div>
                        }
                        {
                            enemy !== "" &&
                            <div className='container-fluid d-flex justify-content-center p-0'>
                                <img className="d-block m-1" src={require(`../../images/fseei.gif`) } onClick={() => {handleCast("fseei")}} alt="" />
                                <img className="d-block m-1" src={require(`../../images/fpush_g.gif`) } alt="" />
                                <img className="d-block m-1" src={require(`../../images/fpull_g.gif`) } alt="" />
                            </div>
                        }
                        {
                            response.char.tmpcast > 0 ? 
                                <div
                                    className="text-primary text-center"
                                >
                                        {`Through Force Seeing you have eagle-eyes (${response.char.tmpcast} %)`}
                                </div>
                            
                            : response.cast === false ?
                                <div
                                    className="text-danger text-center"
                                >
                                        {"You cannot use this force"}
                                </div>
                            : null
                        }
                    </div>
            }
            {
                loading === false && !report && door === "not available yet" &&
                <div className="text-center">
                    Auf deinem Weg in den Untergrund gehen dir die alten Geschichten durch den Kopf. Du fühlst dich den drohenden Kämpfen noch nicht gewachsen.{<br/>}
                    Komme wieder wenn du stärker bist.
                </div>
            }
            {
                loading === false && !report && response.doing === "yes" ?
                <Countdown
                    onFinish={<div className="text-center"><Button disabled={fighting} className="text-dark" variant="link" onClick={handleReport}>{fighting ? "lade..." : "Bericht"}</Button></div>}
                    timeTillDate={response.char.targettime}
                    timeFormat="X"
                />
                : null
            }
            {
                loading === false && !report && door === "done" && enemy !== "" &&
                    <div>
                        <Bars type={"Health"} width={ response.skills.health_width + "%"} data={response.char.health} bg={"bg-danger"}/>
                        <Bars type={"Mana"} width={ response.skills.mana_width + "%"} data={response.char.mana} bg={"bg-primary"}/>
                        <Bars type={"Energy"} width={ response.skills.energy_width + "%"} data={response.char.energy} bg={"bg-success"}/>
                    </div>
            }
            {
                loading === false && report ?
                <div>
                    <div dangerouslySetInnerHTML={{ __html: report.report }}>
                    </div>
                    <Button className="text-dark" variant="link" onClick={() => {loadData(); setReport()}}>verwerfen</Button>
                </div>
                : null
            }
        </div>
    );
}

export default Layer2;