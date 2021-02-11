import React, { useEffect, useState } from "react";
import { useSelector, useDispatch } from "react-redux";
import {GET} from "../tools/fetch";

import { fetchAllianceData } from "../redux/actions/allianceActions";

const Alliance = () => {

    const [loading, setLoading] = useState();
    const dispatch = useDispatch();
    const AlliData = useSelector(state => state.alliance);

    const loadingAllianceData = async() => {
        try {
            setLoading(true)
            const response = await GET('/alliances')
            if (response) {
                dispatch(fetchAllianceData(response))
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
        loadingAllianceData();
    }, []);

    return (
    <>
    {loading === false ? "" + AlliData.AlliData.alliance.name + " (" + AlliData.AlliData.alliance.short + ")" : null}
    </>
    );
}

export default Alliance;