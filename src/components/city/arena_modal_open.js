import React, {useEffect, useState} from 'react';
import {POST} from "../../tools/fetch";

import Modal from "react-bootstrap/Modal";
import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";

const ArenaModal = (props) => {
    const {show} = props;
    const [cost, setCost] = useState(0);

    const [formData, setFormData] = useState({
      type: "duel",
      res: "0",
      bet: 0,
      lower: false,
      higher: false
    });

    const handleFormChange = (e) => {
      setFormData({
        ...formData,
        [e.target.name]: e.target.value
      });
    }

    const handleSwitchChange = (e) => {
      setFormData({
        ...formData,
        [e.target.name] : e.target.checked
      });
    }

    useEffect(() => {
      calcRent();
    }, [formData])

    const calcRent = () => {
      const calcCostType = formData.type === "duel" ? 2 : 10;
      const calcCostResLow = formData.lower && +formData.res === 1 ? 5 :formData.lower && +formData.res === 2 ? 3 :formData.lower && +formData.res === 3 ? 1 : 0;
      const calcCostResHigh = formData.higher && +formData.res === 1 ? 5 :formData.higher && +formData.res === 2 ? 3 :formData.higher && +formData.res === 3 ? 1 : 0;
      const calcCost = calcCostType + calcCostResLow + calcCostResHigh;
      setCost(calcCost)
    }

    const postFight = async () => {
      const response = await POST("/city/arena/open",{formData, cost:cost});
      if (response) {
        props.onHide(false);
      }
    }

  return (
    <div>
    <Modal animation={false} show={show} onHide={() => props.onHide(false)}>
        <Modal.Header>
          <Modal.Title>Kampf anmelden</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form>
            <Form.Group controlId="type" onChange={handleFormChange}>
              <Form.Label>Kampf-Typ</Form.Label>
              <Form.Control name="type" as="select" custom>
                <option>duel</option>
                <option>coop</option>
              </Form.Control>
            </Form.Group>
            <Form.Group controlId="bet" onChange={handleFormChange}>
              <Form.Label>Wette</Form.Label>
              <Form.Control name="bet" type="number" placeholder="Wetteinsatz" />
            </Form.Group>
            <Form.Group controlId="res" onChange={handleFormChange}>
              <Form.Label>Level-Restriction</Form.Label>
              <Form.Control name="res" as="select" custom>
                <option value={0}>keine</option>
                <option value={1}>level bis 15%</option>
                <option value={2}>level bis 27%</option>
                <option value={3}>level bis 39%</option>
              </Form.Control>
            </Form.Group>
            <div className="d-flex d-inline">
              <Form.Check 
                name="lower"
                onChange={handleSwitchChange}
                type="switch"
                id="switch-kleiner"
                label="kleiner"
                checked={formData.lower}
              />
              {
                formData.lower && formData.res === "1" ?
                  <div>&nbsp; &rarr;{` Leveluntergrenze: ${props.response.res["1_low"]}`}</div>
                : formData.lower && formData.res === "2" ?
                  <div>&nbsp; &rarr;{` Leveluntergrenze: ${props.response.res["2_low"]}`}</div>
                : formData.lower && formData.res === "3" ? 
                  <div>&nbsp; &rarr;{` Leveluntergrenze: ${props.response.res["3_low"]}`}</div>
                : null
              }
            </div>
            <div className="d-flex d-inline">
            <Form.Check 
              name="higher"
              onChange={handleSwitchChange}
              type="switch"
              id="switch-größer"
              label="größer"
              checked={formData.higher}
            />
            {
              formData.higher && formData.res === "1" ?
              <div>&nbsp; &rarr;{` Levelobergrenze: ${props.response.res["1_high"]}`}</div>
              : formData.higher && formData.res === "2" ?
              <div>&nbsp; &rarr;{` Levelobergrenze: ${props.response.res["2_high"]}`}</div>
              : formData.higher && formData.res === "3" ? 
              <div>&nbsp; &rarr;{` Levelobergrenze: ${props.response.res["3_high"]}`}</div>
              : null
            }
            </div>
          </Form>
          Kosten: {cost}
        </Modal.Body>
        <Modal.Footer>
          <Button variant="primary" onClick={postFight}>
            eröffnen
          </Button>
        </Modal.Footer>
      </Modal>
      </div>
  )
}

export default ArenaModal;