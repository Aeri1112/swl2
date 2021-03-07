import React, {useState, useEffect} from 'react';
import {useSelector} from "react-redux";
import {GET} from "../../tools/fetch";
import StatCard from "../../tools/card";
import { CardDeck } from 'react-bootstrap';

const Stats = () => {

    const [loading, setLoading] = useState();

    const [data, setData] = useState();

    const userid = useSelector(state => state.user.userId);

    const loadData = async () => {
        setLoading(true);
        const response = await GET(`/statistics/get/${userid}`)
        if(response) {
            setData(response.stats)
        }
        setLoading(false);
    }

    useEffect(() => {
        loadData();
    },[])

    return ( 
        <div>
            {
                loading === false && data &&
                <CardDeck className="mt-1">
                    <StatCard
                        title="Arena" 
                        all={"Fights: " + (data.arenawins + data.arenalosts)} 
                        wins={"wins: " + data.arenawins}
                        lose={"lose: " + data.arenalosts}
                        wprob={"Siegqoute: " + Math.round((data.arenawins * 100 / (data.arenalosts + data.arenawins)) * 100) / 100 + " %"}
                    />
                    <StatCard 
                        title="Layer" 
                        all={"Fights: " + (data.npcwins + data.npclosts)}
                        wins={"wins: " + data.npcwins}
                        lose={"lose: " + data.npclosts}
                        wprob={"Siegqoute: " + Math.round((data.npcwins * 100 / (data.npclosts + data.npcwins)) * 100) / 100 + " %"}
                        loots={"Loots: " + data.loots}
                        lootsprob={"Lootqoute: " + Math.round((data.loots * 100 / (data.npcwins)) * 100) / 100 + " %"}
                        rats={"killed Rats: " + data.killedRat}
                    />
                    <StatCard 
                        title="Raids"
                        all={"Fights: " + (data.raidwins + data.raidlosts)}
                        wins={"wins: " + data.raidwins}
                        lose={"lose: " + data.raidlosts}
                        wprob={"Siegqoute: " + Math.round((data.raidwins * 100 / (data.raidlosts + data.raidwins)) * 100) / 100 + " %"}
                        loots={"Loots: " + data.raidloots}
                        lootsprob={"Lootqoute: " + Math.round((data.raidloots * 100 / (data.raidwins)) * 100) / 100 + " %"}
                        giantRat={"killed Giant-Rats: " + data.killedGiantRat}
                        reek={"killed Reeks: " + data.killedReek}
                    />
                    <StatCard 
                        title="All-Fights"
                        all={"Fights: " + (data.totalwins + data.totallosts)} 
                        wins={"wins: " + data.totalwins}
                        lose={"lose: " + data.totallosts}
                        wprob={"Siegqoute: " + Math.round((data.totalwins * 100 / (data.totallosts + data.totalwins)) * 100) / 100 + " %"}
                    />
                    <StatCard 
                        title="Blackjack"
                        all={"Hands: " + data.bjhands} 
                        wins={"wins: " + data.bjtotalwins}
                        lose={"lose: " + (data.bjhands - data.bjtotalwins)}
                        wprob={"Siegqoute: " + Math.round((data.bjtotalwins * 100 / ((data.bjhands - data.bjtotalwins) + data.bjtotalwins)) * 100) / 100 + " %"}
                        splitwins={"Splitwins: " + data.bjsplitwins}
                        doublewins={"Doublewins: " + data.bjdoublewins}
                        winningstreak={"longest winning streak: " + data.bjwinningstreak}
                        insuracewins={"Insurace wins: " + data.bjinsurancewins}
                        gewinn={"Profit: " + data.bjearning + " Cr."}
                    />
                </CardDeck>
            }
        </div>
     );
}
 
export default Stats;