import React from "react";


import Card from "react-bootstrap/Card";
import Button from 'react-bootstrap/Button';

const Item = (props) => {

    const postDataHandler = () => {
        props.dropItem({itemid: props.item.itemid, type: props.type})
    }

    let dmg = "";
    if(props.item.mindmg !== 0) { dmg = "Damage: " + props.item.mindmg + " - " + props.item.maxdmg}

    if(props.type === "cash") {
        dmg = props.item.cash + " Credits";
        props.item.img = "cash";
        props.item.stat1 = "";
        props.item.stat2 = "";
        props.item.stat3 = "";
        props.item.stat4 = "";
        props.item.stat5 = "";
        props.item.reql = 0;
        props.item.reqs = 0;
    }
    return (
        <div>
            <Card>
                <Card.Img variant="top" style={{width: '100px'}} className="mx-auto d-block mt-2" src={require(`../images/items/${props.imgFolder}/${props.item.img}.jpg`) } />
                <Card.Body className="text-center">
                    
                    {
                        !props.type ? <Card.Title>
                            {props.item.name}
                        </Card.Title> : null
                    }
                    
                    <Card.Text>
                        {props.item.uni === "y" ? <span className="text-warning">unique item</span> : null}
                        {props.item.uni === "y" ? <br/> : null}

                        {props.item.nodrop === "y" ? "no drop" : null}
                        {props.item.nodrop === "y" ? <br/> : null}

                        {dmg}
                        {dmg ? <br/> : null}

                        {props.item.stat1 !== "" ? props.item.stat1 : null}
                        {props.item.stat1 !== "" ? <br/> : null}

                        {props.item.stat2 !== "" ? props.item.stat2 : null}
                        {props.item.stat2 !== "" ? <br/> : null} 

                        {props.item.stat3 !== "" ? props.item.stat3 : null}
                        {props.item.stat3 !== "" ? <br/> : null}

                        {props.item.stat4 !== "" ? props.item.stat4 : null}
                        {props.item.stat4 !== "" ? <br/> : null}

                        {props.item.stat5 !== "" ? props.item.stat5 : null}
                        {props.item.stat5 !== "" ? <br/> : null}

                        {props.item.reql !== 0 ? `req. level: ${props.item.reql}` : null}
                        {props.item.reql !== 0 ? <br/> : null}

                        {props.item.reqs !== 0 ? `req. skill: ${props.item.reqs}` : null}

                    </Card.Text>
                </Card.Body>

                {props.type !== "cash" ? 
                <Card.Footer>
                    <small>
                    <Button className="text-muted" size="sm" variant="link" onClick={postDataHandler}>ablegen</Button>
                    </small>                       
                </Card.Footer> : null}
                
            </Card>
        </div>
    )
};

export default Item;