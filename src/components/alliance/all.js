import React, {useEffect, useState} from 'react';
import {Link} from "react-router-dom";
import {GET} from "../../tools/fetch";
import Alliance_menu from "./alliance_menu";

import Table from "react-bootstrap/Table";

const All = () => {

    const [loading, setLoading] = useState();
    const [response, setResponse] = useState();

    const [loadingChar, setLoadingChar] = useState();
    const [char, setChar] = useState();

    const loadingData = async () => {
        setLoading(true);
        try{
            const response = await GET("/alliances/all");
            if (response) {
                setResponse(response);
            }
        }
        catch {

        }
        finally {
            setLoading(false);
        }
    }

    const loadingCharData = async () => {
        try {
            setLoadingChar(true)
            const response = await GET('/character/user')
            if (response) {
                setChar(response)
            }
        } catch (e) {
            console.error(e)
        } finally {
            // finally wird immer ausgefuehrt.
            // dadurch wird der state auch immer danach false gesetzt.
            setLoadingChar(false)
        }
    }

    useEffect(() => {
        loadingData();
        loadingCharData();
    }, [])

    return (
        <div>
            {
                loading === false && loadingChar === false &&
                <div>
                    <Table striped size="sm" responsive="sm">
                        <thead>
                            <tr>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Kürzel
                                </th>
                                <th>
                                    Ausrichtung
                                </th>
                                <th>
                                    Aktion
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {
                                response.alliances.map((element) => {
                                    return (
                                        <tr key={element.id}>
                                            <td>
                                                {element.name}
                                            </td> 
                                            <td>
                                                {element.short}
                                            </td>
                                            <td>
                                                {element.alignment === 1 ? "hell" : element.alignment === 0 ? "neutral" : "dunkel"}
                                            </td>
                                            <td>
                                                <Link to={`/alliance/view/${element.id}`}>view</Link>
                                            </td>  
                                        </tr>
                                    );
                                })
                            }
                        </tbody>
                    </Table>
                    <div>
                        {<Alliance_menu alliId={char.user.char.alliance}/>}
                    </div>
                </div>
            }          
        </div> 
    );
}
 
export default All;