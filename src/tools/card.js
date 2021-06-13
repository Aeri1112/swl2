import React from 'react';
import Card from 'react-bootstrap/Card'

const StatCard = ({left, right, title, all, wins, lose, wprob, loots, lootsprob, rats, giantRat, reek, splitwins, doublewins, bj, winningstreak, insurancewins, gewinn}) => {
    return ( 
        <Card>
            <Card.Body>
                <Card.Title> {title} </Card.Title>
                <Card.Text>
                    <div>{all}</div>
                    <div>{wins}</div>
                    <div>{lose}</div>
                    <div>{wprob}</div>
                    {
                        loots &&
                        <div>{loots}</div>
                    }
                    {
                        lootsprob &&
                        <div>{lootsprob}</div>
                    }
                    {
                        rats &&
                        <div>{rats}</div>
                    }
                    {
                        left &&
                        <div>{left}</div>
                    }
                    {
                        right &&
                        <div>{right}</div>
                    }
                    {
                        giantRat &&
                        <div>{giantRat}</div>
                    }
                    {
                        reek &&
                        <div>{reek}</div>
                    }
                    {
                        splitwins &&
                        <div>
                            <div>{bj}</div>
                            <div>{splitwins}</div>
                            <div>{doublewins}</div>
                            <div>{winningstreak}</div>
                            <div>{insurancewins}</div>
                            <div>{gewinn}</div>
                        </div>
                        
                    }
                </Card.Text>
            </Card.Body>
        </Card>
     );
}
 
export default StatCard;