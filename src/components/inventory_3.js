import React, {useState, useEffect} from "react";
import { useSelector, useDispatch } from "react-redux";
import Pagination from "react-js-pagination";
import {GET} from "../tools/fetch";
import Item from "./item";
import Search from "./search";

import  Button  from "react-bootstrap/Button";
import ButtonGroup from "react-bootstrap/ButtonGroup";
import Table from "react-bootstrap/Table";
import Spinner from 'react-bootstrap/Spinner';
import Col from "react-bootstrap/Col";
import Row from "react-bootstrap/Row";
import Card from "react-bootstrap/Card";

import { inventoryState__setEquipment, inventoryState__setItems } from "../redux/actions/inventoryActions";

// so das ganze hier mal als funktionale componente
// vllt musst noch die importe anpassen, falls es direkt nutzen willst

const Inventory = () => {
    const [loadingItems, setLoadingItems] = useState()
    const [loadingEquip, setLoadingEquip] = useState()

    const [activePage, setActivePage] = useState(1)
    const [itemType, setItemType] = useState("weapons")
    const [sortDir, setSortDir] = useState("desc")
    const [sortType, setSortType] = useState("itemid")
    const [searchVal, setSearchVal] = useState("")

    const dispatch = useDispatch()
    const inv = useSelector(state => state.skills.inv)
    const eqp = useSelector(state => state.skills.eqp)
    
    const loadEquip = async() => {
        try {
            setLoadingEquip(true)
            const response = await GET('/character/inventory?id=weapons')
            if (response) {
                dispatch(inventoryState__setEquipment(response))
            }
        } catch (e) {
            console.error(e)
        } finally {
            // finally wird immer ausgefuehrt.
            // dadurch wird der state auch immer danach false gesetzt.
            setLoadingEquip(false)
        }
    }
           
    const dropItem = async item => {
        //send request to DB
        const request = await GET(`/character/inventory/dequip/${item.type}/${item.itemid}`)
        
        //get new State
        if(request) {
            try {
                setLoadingEquip(true)
                const response = await GET('/character/inventory?id=weapons')
                if (response) {
                    dispatch(inventoryState__setEquipment(response))
                }
            } catch (e) {
                console.error(e)
            } finally {
                // finally wird immer ausgefuehrt.
                // dadurch wird der state auch immer danach false gesetzt.
                setLoadingEquip(false)
            }
        }
    }

    const equipItem = async item => {
        //send request to DB
        const request = await GET(`/character/inventory/equip/${item.type}/${item.itemid}`)
        
        //get new State
        if(request) {
            try {
                setLoadingEquip(true)
                const response = await GET('/character/inventory?id=weapons')
                if (response) {
                    dispatch(inventoryState__setEquipment(response))
                }
            } catch (e) {
                console.error(e)
            } finally {
                // finally wird immer ausgefuehrt.
                // dadurch wird der state auch immer danach false gesetzt.
                setLoadingEquip(false)
            }
        }
    }

    const loadItems = async(activePage, itemType, sortDir, sortType, searchVal) => {
        try {
            setLoadingItems(true)
            const response = await GET(`/character/inventory?id=${itemType}&page=${activePage}&sort=${sortType}&direction=${sortDir}&search=${searchVal}`)
            if (response) {
                dispatch(inventoryState__setItems(response))
            }
        } catch (e) {
            console.error(e)
        } finally {
            // finally wird immer ausgefuehrt.
            // dadurch wird der state auch immer danach false gesetzt.
            setLoadingItems(false)
        }
    }

    const handlePageChange = (pageNumber) => {
        setActivePage(pageNumber)
      }

    const handleOnSortDir = (SortType) => {
        if(sortDir === "desc") {
            setSortDir("asc")
        }
        else {
            setSortDir("desc")
        }
        setSortType(SortType)
    }

    const onSearch = (event) => {
        setSearchVal(event.target.value)
    }

      useEffect(() => {
        loadItems(activePage,itemType,sortDir,sortType,searchVal)
    }, [activePage, sortDir, eqp, searchVal]) 

    useEffect(() => {
        loadEquip();
    }, []) 
    // das leere array hier ist wichtig, dadurch wird der effect nur einmal ausgefuehrt
    // useEffect wird immer ausegfuehrt, wenn einer der werte im array sich aendert.
    // wenn der leer ist, aendert sich ja nix
    // wenn du den array weg laesst wir der bei jedem render ausgefuerht.

    return ( 
        <div>
            { loadingEquip === false && inv.error ?
                <Row className="message error">
                    {inv.error}
                </Row>
                :
                null
            }
            
            {
                loadingEquip === false ? 
                (<Row>
                    <Col md="3">
                        {eqp.act_weapon ? <Item 
                            item={eqp.act_weapon}
                            imgFolder="weapons"
                            type="weapon"
                            dropItem={dropItem}
                        /> :
                        <Card>
                            <Card.Body className="text-center">
                                <Card.Text>
                                    no weapon
                                </Card.Text>
                            </Card.Body>
                        </Card>}
                    </Col>
                    <Col md="3">
                        {eqp.act_jewelry1 ? <Item 
                            item={eqp.act_jewelry1}
                            imgFolder="rings"
                            type="ring1"
                            dropItem={dropItem}
                        /> :
                        <Card>
                            <Card.Body className="text-center">
                                <Card.Text>
                                    no ring
                                </Card.Text>
                            </Card.Body>
                        </Card>}
                    </Col>
                    <Col md="3">
                        {eqp.act_jewelry2 ? <Item 
                            item={eqp.act_jewelry2}
                            imgFolder="rings"
                            type="ring2"
                            dropItem={dropItem}
                        /> :
                        <Card>
                            <Card.Body className="text-center">
                                <Card.Text>
                                    no ring
                                </Card.Text>
                            </Card.Body>
                        </Card>}
                    </Col>
                    <Col md="3">
                        <Item 
                            item={eqp.char}
                            imgFolder="misc"
                            type="cash"
                        />
                    </Col>
                </Row>) : 
                <Row className="p-1 justify-content-center">
                    <Spinner animation="border" />
                </Row>
            }
            <Row className="p-1 justify-content-center">
                <ButtonGroup>
                    <Button variant="outline-dark" onClick={() => {loadItems(1,"weapons","desc","itemid",""); setItemType("weapons"); setActivePage(1); setSortType("itemid"); setSortDir("desc"); setSearchVal("")}}>Waffen</Button>
                    <Button variant="outline-dark" onClick={() => {loadItems(1,"rings","desc","itemid",""); setItemType("rings"); setActivePage(1); setSortType("itemid"); setSortDir("desc"); setSearchVal("")}}>Ringe</Button>
                    <Button variant="outline-dark" onClick={() => {loadItems(1,"misc","desc","itemid",""); setItemType("misc"); setActivePage(1); setSortType("itemid"); setSortDir("desc"); setSearchVal("")}}>Verschiedenes</Button>
                </ButtonGroup>
            </Row>

            <Search
                onSearch={onSearch}
                value={searchVal}
            />

            {loadingItems && <Row className="p-1 justify-content-center"><Spinner animation="border" /></Row>} {/* hier wird der div nur angezeigt, wenn loading true ist */}
            
            <Table responsive="md" size="sm">
                <thead>
                    <tr>
                        <th>
                            <Button onClick={() => handleOnSortDir("name")} className="pl-2 pb-0 pt-0 pr-0 font-weight-bold text-secondary" variant="link">name</Button>
                        </th>

                        {itemType === "weapons" ? 
                            (
                                <>
                                    <th>
                                        <Button onClick={() => handleOnSortDir("mindmg")} className="p-0 font-weight-bold text-secondary" variant="link">
                                            mindmg
                                        </Button>
                                    </th>
                                    <th>
                                    <Button onClick={() => handleOnSortDir("maxdmg")} className="p-0 font-weight-bold text-secondary" variant="link">
                                            maxdmg
                                    </Button>
                                    </th>
                                </>
                            ) 
                            :   null
                        }
                        
                        <th>
                            <Button onClick={() => handleOnSortDir("price")} className="p-0 font-weight-bold text-secondary" variant="link">
                                price
                            </Button>
                        </th>
                        <th>
                            <Button onClick={() => handleOnSortDir("qlvl")} className="p-0 font-weight-bold text-secondary" variant="link">
                                qlvl
                            </Button>
                        </th>
                        <th>
                            <Button onClick={() => handleOnSortDir("reql")} className="p-0 font-weight-bold text-secondary" variant="link">
                                reql
                            </Button>
                        </th>
                        <th>
                            <Button onClick={() => handleOnSortDir("reqs")} className="p-0 font-weight-bold text-secondary" variant="link">
                                reqs
                            </Button>
                        </th>
                        <th>
                            <Button onClick={() => handleOnSortDir("stat1_value")} className="p-0 font-weight-bold text-secondary" variant="link">
                                stat1
                            </Button>
                        </th>
                        <th>
                            <Button onClick={() => handleOnSortDir("stat2_value")} className="p-0 font-weight-bold text-secondary" variant="link">
                                stat2
                            </Button>
                        </th>
                        <th>
                            <Button onClick={() => handleOnSortDir("stat3_value")} className="p-0 font-weight-bold text-secondary" variant="link">
                                stat3
                            </Button>
                        </th>
                        <th>
                            <Button onClick={() => handleOnSortDir("stat4_value")} className="p-0 font-weight-bold text-secondary" variant="link">
                                stat4
                            </Button>
                        </th>
                        <th>
                            <Button onClick={() => handleOnSortDir("stat5_value")} className="p-0 font-weight-bold text-secondary" variant="link">
                                stat5
                            </Button>
                        </th>
                    </tr>
                </thead> 
                <tbody>
                    {loadingItems === false && inv.items.map(item => {
                    //definieren ob Ring oder Waffe
                    item.type = inv.img;

                    return (
                        <tr className="small" key={item.itemid}>
                            <td>
                                {item.reql < inv.char.skills.level && item.reqs < inv.char.skills.dex ?
                                <Button className="text-muted" size="sm" variant="link" onClick={() => equipItem(item)}>{item.name}</Button>
                                : <div className="pl-2">{item.name}</div>}
                            </td>   
                            {itemType === "weapons" ? (
                            <>
                                <td>{item.mindmg}</td>
                                <td>{item.maxdmg}</td>
                            </>) : null} 
                            <td>
                                 {item.price}
                            </td> 
                            <td>
                                 {item.qlvl} 
                            </td> 
                            <td>
                                 {inv.char.skills.level < item.reql ?
                                 <span className="text-danger">{item.reql}</span> 
                                 : item.reql} 
                            </td> 
                            <td>
                                 {inv.char.skills.dex < item.reqs ?
                                 <span className="text-danger">{item.reqs}</span> 
                                 : item.reqs} 
                            </td>
                            <td>
                                {item.stat1_mod} {item.stat1_stat} {item.stat1_value !== "0" ? item.stat1_value : null}
                            </td> 
                            <td>
                                {item.stat2_mod} {item.stat2_stat} {item.stat2_value !== "0" ? item.stat2_value : null} 
                            </td> 
                            <td>
                                {item.stat3_mod} {item.stat3_stat} {item.stat3_value !== "0" ? item.stat3_value : null}
                            </td> 
                            <td>
                                {item.stat4_mod} {item.stat4_stat} {item.stat4_value !== "0" ? item.stat4_value : null}
                            </td> 
                            <td>
                                {item.stat5_mod} {item.stat5_stat} {item.stat5_value !== "0" ? item.stat5_value : null}
                            </td> 
                        </tr>
                        );
                    })} 
                </tbody>
            </Table>
            {!loadingItems ?
            <Pagination
                hideDisabled
                activePage={activePage}
                itemsCountPerPage={10}
                totalItemsCount={inv.totalItems ? inv.totalItems : 0}
                pageRangeDisplayed={5}
                onChange={handlePageChange}
                itemClass="page-item"
                linkClass="page-link"
            /> : null}  
            
        </div>  
    )
}

// hier habe ich nun useDispatch und useSelector von redux genutz.
// koenntest stattdessen immer noch den connector nutzen und funktionale klassen.
// useEffect und paar andere Sachen koennten anfangs ungewohnt sein.

// den redux store wuerde ich so flach wie moeglich halten.
// umso verzweigter der inhalt ist,
// desto schwieriger wird das selektieren.

// Aber ich muss sagen, nichts von dem was du gemacht hast war falsch.
// Im schlimmsten Fall, haettest du mit der Zeit manches halt anpassen muessen.
// Erstmal so arbeiten, wie es fuer dich selber am sinnvollsten ist.
// Solange etwas nicht massiv langsam ist, oder du dauernd schleifen im Code oder per Ajax erzeugst, alles nicht tragisch.
// Etwas Vertrauen und Erfolgsergebnisse sammeln.
// Durch die Arbeit an Frontend wie Backend, bist quasi zum Hobby-Fullstack Entwickler geworden.

export default Inventory;