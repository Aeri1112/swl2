import React, {useState, useEffect} from "react";

import Bars from "../../components/bars";
import {GET,POST} from "../../tools/fetch";
import Countdown from "../../tools/countdown";

import Carousel from 'react-bootstrap/Carousel'
import Button from "react-bootstrap/Button";
import Modal from "react-bootstrap/Modal";

const Layer = (props) => {

    const [loading, setLoading] = useState();
    const [response, setResponse] = useState();
    const [report, setReport] = useState();
    const [carouselElements, setCarouselElements] = useState();

    //if you cannot enter the layer
    const [open, setOpen] = useState(false);
    const [redirect, setRedirect] = useState();

    const loadData = async () => {
        setLoading(true);
        try {
            const response = await GET('/city/layer')
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

                const items = [];

                if(response.doing === "no") {
                    for (let index = 0; index <= 18; index++) {
                        const a = Math.floor(Math.random() * 3) + 1;
                        const b = Math.floor(Math.random() * 6) + 1;
        
                        items.push(
                            <Carousel.Item key={index}>
                                <img className="d-block mx-auto img" src={require(`../../images/monster/neu/layer2_${a}_${b}.jpg`) } alt="" />
                                <Carousel.Caption className="d-md-block pb-0" style={{bottom:"0px"}}>
                                    <Button className="text-white" variant="link" onClick={() => handleAttack(a,b)}>attack</Button>
                                </Carousel.Caption>
                            </Carousel.Item>
                        )
                    }
                    setCarouselElements(items)
                }
                else if(response.doing === "yes") {
                    setCarouselElements(
                        <Carousel.Item>
                            <img className="d-block mx-auto img" src={require(`../../images/monster/neu/layer2_${response.char.location2}.jpg`) } alt="" />
                        </Carousel.Item>
                    )
                }
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
        const response = await GET("/city/layer/fight")
        setReport(response.fight_report);
    }

    const handleAttack = async (a,b) => {
        if(a != 3) {
            await POST("/city/layer/attack", {a:a, b:b,fight:"y"})
            loadData();
        }
        else { alert("there was nothing to attack!")}
    }

    const handleCast = async (force) => {
        switch (force) {
            case "fseei":
                const response = await POST("/city/layer/cast",{cast:1})
                setResponse(response)
                break;
        
            default:
                break;
        }
    }

    useEffect(() => {
        loadData();
    }, [])

    return (
        <div>
            {open && 
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
                loading === false && !report ?
                    <div>
                        <div className="container-fluid p-0 m-0">
                            <div className="row justify-content-center">
                                <div className="col-auto">
                                    <Carousel slide={false} fade={true} interval={null} indicators={false}>
                                        {carouselElements}
                                    </Carousel>
                                </div>
                            </div>
                        </div>
                        <div className='container-fluid d-flex justify-content-center p-0'>
                            <img className="d-block m-1" src={require(`../../images/fseei.gif`) } onClick={() => {handleCast("fseei")}} alt="" />
                            <img className="d-block m-1" src={require(`../../images/fpush_g.gif`) } alt="" />
                            <img className="d-block m-1" src={require(`../../images/fpull_g.gif`) } alt="" />
                        </div>
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
                : null
            }
            {
                loading === false && !report && response.doing === "yes" ?
                <Countdown
                    onFinish={<div className="text-center"><Button className="text-dark" variant="link" onClick={handleReport}>Bericht</Button></div>}
                    timeTillDate={response.char.targettime}
                    timeFormat="X"
                />
                : null
            }
            {
                loading === false && !report ?
                    <div>
                        <Bars type={"Health"} width={ response.skills.health_width + "%"} data={response.char.health} bg={"bg-danger"}/>
                        <Bars type={"Mana"} width={ response.skills.mana_width + "%"} data={response.char.mana} bg={"bg-primary"}/>
                        <Bars type={"Energy"} width={ response.skills.energy_width + "%"} data={response.char.energy} bg={"bg-success"}/>
                    </div>
                : null
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

export default Layer;