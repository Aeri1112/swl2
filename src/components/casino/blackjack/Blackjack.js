import React, {useState, useEffect} from "react";
import Hand from "./Hand";
import DealersHand from "./DealersHand";
import Shoe from "./Shoe";
import BetInput from "./BetInput";
import {GET, POST} from "../../../tools/fetch";

import Button from 'react-bootstrap/Button'

const Blackjack = () => {
    
    const [loading, setLoading] = useState();
    const [saving, setSaving] = useState();

    const [gameStatus, setGameStatus] = useState("prep");
    const [isDealing, setDealing] = useState(false);
    const [insuranceAv, setInsuranceAv] = useState(false);
    const [splittedAces, setSplittedAces] = useState(false);

    const [Deck, setDeck] = useState();
    const [needNewDeck, setNeedNewDeck] = useState(Math.floor(Math.random() * (160 - 150 + 1) ) + 150);
    const [newDeck, setNewDeck] = useState(false);

    const [PlayerStatus, setPlayerStatus] = useState("active")
    const [PlayerCards, setPlayerCards] = useState([]);
    const [PlayerCardsCount, setPlayerCardsCount] = useState(0);
    const [PlayerIsDouble, setPlayerIsDouble] = useState(false);
    const [insurance, setInsurance] = useState(false);
    const [cash, setCash] = useState(0);
    const [split, setSplit] = useState(false);
    const [bet, setBet] = useState(1);
    const [PlayerHandStatus, setPlayerHandStatus] = useState("");
    const [softCount, setSoftCount] = useState(false);

    const [SplitStatus, setSplitStatus] = useState("active")
    const [SplitCards, setSplitCards] = useState([]);
    const [SplitCardsCount, setSplitCardsCount] = useState(0);
    const [SplitIsDouble, setSplitIsDouble] = useState(false);
    const [SplitBet, setSplitBet] = useState(0);
    const [SplitHandStatus, setSplitHandStatus] = useState("");
    const [softCountSplit, setSoftCountSplit] = useState(false);

    const [DealerStatus, setDealerStatus] = useState("active")
    const [DealerCards, setDealerCards] = useState([]);
    const [DealerCardsCount, setDealerCardsCount] = useState(0);
    const [DealerNoBJ, setDealerNoBJ] = useState(false);

    const loadData = async () => {
        setLoading(true);
        try {
            const response = await GET('/character/overview')
            if (response) {
                setCash(response.char.cash)
            }
        } catch (e) {
            console.error(e)
        } finally {
            // finally wird immer ausgefuehrt.
            // dadurch wird der state auch immer danach false gesetzt.
            setLoading(false)
        }
    };

    const SaveData = async () => {
        setSaving(true);
        try {
            const response = await POST('/character/saveuser', {where:"char", what:"cash", amount:cash})
            console.log(response)
        } catch (e) {
            console.error(e)
        } finally {
            // finally wird immer ausgefuehrt.
            // dadurch wird der state auch immer danach false gesetzt.
            setSaving(false)
        }
    }

    useEffect(() => {
        loadData();
    },[]);

    useEffect(() => {
        if(gameStatus === "prep") {
            setDeck(Shoe.prototype.getDeck());
        }
        else if (gameStatus === "finish" && newDeck) {
            setDeck(Shoe.prototype.getDeck());
            setNewDeck(false);
        }    
    }, [newDeck, gameStatus]);
    //old game round is finish? clean up and start a new one
    useEffect(() => {       
        if (gameStatus === "finish" && isDealing) {
            setPlayerStatus("active");
            setDealerStatus("active");
            setPlayerCardsCount(0);
            setDealerCardsCount(0);
            setInsurance(false);
            setSplit(false);
            setPlayerHandStatus("");
            setSplitHandStatus("");
            setDealerNoBJ(false);
            setSoftCount(false);
            setSoftCountSplit(false);
            setSplittedAces(false);
        }
    }, [gameStatus, isDealing])
    //Dealing
    useEffect(() => {
        if (isDealing) {
            console.log(Deck)
            const Card1 = Deck.pop();
            const Card2 = Deck.pop();
            const Card3 = Deck.pop();
            const Card4 = Deck.pop();

            setPlayerCards([Card1, Card3]);
            setDealerCards([Card2, Card4]);

            //(cash - bet) da die bet erst druten vom cash abgezogen wird
            if (Card2[1] === "ace" && (cash - bet) >= (bet * 0.5)) {
                setInsuranceAv(true);
            }

            setCash(cash - bet);
            
            setGameStatus("running");
            setDealing(false);
            checkShoe();
        }
    }, [isDealing]);
    //CountPlayerCards
    useEffect(() => {
        countPlayersCards(PlayerCards, "player");
    }, [PlayerCards]);
    //CountSplitCards
    useEffect(() => {
        countPlayersCards(SplitCards, "split");
    }, [SplitCards]);

    useEffect(() => {
        if (gameStatus === "DealersTurn" && PlayerStatus === "BJ" && countCardsWithoutSet(DealerCards) === 21 && isBlackJack(DealerCards)) {
            setGameStatus("finish")
        }
        else if (gameStatus === "DealersTurn" && PlayerStatus === "BJ" && countCardsWithoutSet(DealerCards) !== 21) {
            setGameStatus("finish");
        }
        else if (gameStatus === "DealersTurn") {
            countDealersCards();
        }
        else if (gameStatus === "finish") {
            whoWins();
            SaveData();
        }
    }, [gameStatus, DealerCards])

    const dealDealer = () => {
        setDealerCards([...DealerCards, Deck.pop()]);
        checkShoe();
    }

    const handleClickDeal = () => setDealing(true);

    const handleClickHit = () => {
        if(gameStatus === "running") {
            setPlayerCards([...PlayerCards, Deck.pop()]);
        }
        else if (split && gameStatus === "SplitTurn") {
            setSplitCards([...SplitCards, Deck.pop()]);
        }
        checkShoe();
    }

    const handleClickStand = () => {
        if(split && gameStatus === "running") {
            setGameStatus("SplitTurn")
            setPlayerStatus("stand");
        }
        else if(split && gameStatus === "SplitTurn"){     
            setGameStatus("DealersTurn");
            setSplitStatus("stand")
        }
        else if(!split && gameStatus === "running") {
            setPlayerStatus("stand")
            setGameStatus("DealersTurn")
        }
        if(!split) setSoftCount(false);
                else setSoftCountSplit(false);
    }

    const handleClickDouble = () => {
        setCash(cash - bet);
        handleClickHit();
        if(split && gameStatus === "running") {
            setBet(bet * 2);
            setPlayerStatus("stand");
            setPlayerIsDouble(true);
            setGameStatus("SplitTurn");
        }
        else if(split && gameStatus === "SplitTurn"){
            setSplitBet(bet * 2);
            setSplitStatus("stand");
            setSplitIsDouble(true);
            setGameStatus("DealersTurn");
        }
        else {
            setBet(bet * 2);
            setPlayerStatus("stand");
            setPlayerIsDouble(true);
            setGameStatus("DealersTurn")
        }
    }

    const handleClickNoInsurance = () => {
        setInsuranceAv(false);
        setInsurance(false);
        //checking for Blackjack
        if(countCardsWithoutSet(DealerCards) === 21 && isBlackJack(DealerCards)) {
            countDealersCards();
            setGameStatus("finish")
            if(PlayerCardsCount !== 21)
            {
                setPlayerStatus("stand")
            }
        }
        else {
            setDealerNoBJ(true)
        }

        if(PlayerCardsCount === 21 && isBlackJack(PlayerCards)) {
            setPlayerStatus("BJ")
        }
    }

    const handleClickInsurance = () => {
        //Versicherung abziehen zwischenspeichern
        let cashFlow = -(bet / 2);
        setInsuranceAv(false);
        setInsurance(true);
        
        //checking for Blackjack
        if(countCardsWithoutSet(DealerCards) === 21 && isBlackJack(DealerCards)) {
            //Versicherungsumme zurück geben zwischenspeichern
            cashFlow += (bet * 1.5);
            countDealersCards();
            setGameStatus("finish")
        }
        else {
            setDealerNoBJ(true);
        }

        if(PlayerCardsCount === 21 && isBlackJack(PlayerCards)) {
            setPlayerStatus("BJ")
        }
        else {
            setPlayerStatus("stand")
        }

        setCash(cash + cashFlow)
    }

    const handleClickSplit = () => {
        setSplit(true);
        setSplitBet(bet);
        setCash(cash - bet);
        setPlayerCards([PlayerCards[0], Deck.pop()]);
        setSplitCards([PlayerCards[1], Deck.pop()]);
        if(PlayerCards[0][1] === "ace") {
            setSoftCount(false);
            setSoftCountSplit(false);
            setPlayerStatus("stand");
            setSplitStatus("stand");
            setGameStatus("DealersTurn");
            setSplittedAces(true);
        }
    }

    const checkSplit = (Cards) => {
        if(Cards[0][1] === Cards[1][1]) {
            return true;
        }
        else {
            return false;
        }
    }

    const checkShoe = () => {
        if(Deck.length < needNewDeck) {
            setNewDeck(true);
        }
    }

    const countPlayersCards = (Cards, Type) => {
        let gesamtValue = 0;
        let hasAce = 0;

        Cards.forEach(card => {
            let cardValue = 0;

            if(card[1] === "jack" || card[1] === "queen" || card[1] === "king") {
                cardValue = 10;
            }
            else if (card[1] === "ace") {
                hasAce += 1;
                if(Type === "player" && !splittedAces) setSoftCount(true);
                else if (Type === "split" && !splittedAces) setSoftCountSplit(true);
            }
            else {
                cardValue += card[1];
            }
            gesamtValue += cardValue;
        });

        for (let index = 0; index < hasAce; index++) {
            if(gesamtValue + 11 > 21) {
                gesamtValue += 1;
                if(Type === "player") setSoftCount(false);
                else setSoftCountSplit(false);
            }            
            else {
                gesamtValue += 11;
            }
        }
        if(Type === "player") setPlayerCardsCount(gesamtValue);
        else setSplitCardsCount(gesamtValue);

        if (gesamtValue === 21 && isBlackJack(Cards) && !insuranceAv) {
            if(Type === "player") {
                setPlayerStatus("stand");
                if(!split) {
                    setGameStatus("DealersTurn")
                    setPlayerStatus("BJ");
                }
                else {
                    setGameStatus("SplitTurn")
                }
            }
            else {
                setSplitStatus("stand");
                setGameStatus("DealersTurn")
            }
            if(Type === "player") setSoftCount(false);
                else setSoftCountSplit(false);
        }
        else if(gesamtValue === 21 && !insuranceAv) {
            if(Type === "player") {
                setPlayerStatus("stand");
                if(!split) {
                    setGameStatus("DealersTurn")
                }
                else {
                    setGameStatus("SplitTurn")
                }
            }
            else {
                setSplitStatus("stand");
                setGameStatus("DealersTurn");
            }
            if(Type === "player") setSoftCount(false);
                else setSoftCountSplit(false);
        }
        else if(gesamtValue > 21 && !insuranceAv) {
            if(Type === "player") {
                setPlayerStatus("bust");
                if(!split) {
                    setGameStatus("finish");
                }
                else {
                    setGameStatus("SplitTurn")
                }
            }
            else {
                setSplitStatus("bust");
                if(PlayerStatus === "bust"){
                    setGameStatus("finish");
                }
                setGameStatus("DealersTurn")   
            }
            if(Type === "player") setSoftCount(false);
                else setSoftCountSplit(false);    
        }
    };

    const countDealersCards = () => {
        let gesamtValue = 0;
        let hasAce = 0;
        DealerCards.forEach(card => {
            let cardValue = 0;
            if(card[1] === "jack" || card[1] === "queen" || card[1] === "king") {
                cardValue = 10;
            }
            else if (card[1] === "ace") {
                hasAce += 1;
            }
            else {
                cardValue = card[1];
            }
            gesamtValue += cardValue;
        });
        for (let index = 0; index < hasAce; index++) {
            if(gesamtValue + 11 > 21) {
                gesamtValue += 1;
            }            
            else {
                gesamtValue += 11;
            }
        }
        setDealerCardsCount(gesamtValue);

        if (gesamtValue === 21 && isBlackJack(DealerCards)) {
            setDealerStatus("BJ");
            setGameStatus("finish");
        }
        else if(gesamtValue > 21) {
            setDealerStatus("bust");
            setGameStatus("finish");
        }
        else if(gesamtValue > 16 && gesamtValue < 22) {
            setDealerStatus("stand");
            setGameStatus("finish");
        }
        else {
            setTimeout(() => dealDealer(), 1000)
        }
    };

    const countCardsWithoutSet = (Cards) => {
        let gesamtValue = 0;
        let hasAce = 0;

        Cards.forEach(card => {
            let cardValue = 0;

            if(card[1] === "jack" || card[1] === "queen" || card[1] === "king") {
                cardValue = 10;
            }
            else if (card[1] === "ace") {
                hasAce += 1;
            }
            else {
                cardValue += card[1];
            }
            gesamtValue += cardValue;
        });
        for (let index = 0; index < hasAce; index++) {
            if(gesamtValue + 11 > 21) {
                gesamtValue += 1;
            }            
            else {
                gesamtValue += 11;
            }
        }
        return gesamtValue;
    }

    const isBlackJack = (Cards) => {
        if(Cards.length === 2) {
            return true;
        }
        else {
            return false;
        }
    }

    const whoWins = () => {
        let win = 0;

        if(PlayerStatus === "bust") {
            setPlayerHandStatus("bust!")
        }
        else if (DealerStatus === "bust") {
            setPlayerHandStatus("wins!")
            win += (bet * 2);
        }

        if(SplitStatus === "bust") {
            setSplitHandStatus("bust!")
        }
        else if (DealerStatus === "bust") {
            setSplitHandStatus("wins!")
            win += (SplitBet * 2);
        }

        if(DealerCardsCount > PlayerCardsCount && DealerStatus !== "bust" && DealerStatus !== "BJ") {
            setPlayerHandStatus("lose!")
        }
        else if (DealerCardsCount < PlayerCardsCount && PlayerStatus !== "bust" && PlayerStatus !== "BJ") {
            setPlayerHandStatus("wins!")
            win += (bet * 2);
        }
        else if (DealerStatus === "BJ" && PlayerStatus === "BJ") {
            setPlayerHandStatus("push!")
            win += (bet)
        }
        else if (DealerStatus === "BJ" && PlayerStatus !== "BJ") {
            setPlayerHandStatus("lose!")
        }
        else if (DealerStatus !== "BJ" && PlayerStatus === "BJ") {
            setPlayerHandStatus("wins with BJ!")
            win += (bet * 2.5)
        }
        else if (DealerCardsCount === PlayerCardsCount) {
            setPlayerHandStatus("push")
            win += (bet)
        }

        if(split) {
            if(DealerCardsCount > SplitCardsCount && DealerStatus !== "bust" && DealerStatus !== "BJ") {
                setSplitHandStatus("lose!")
            }
            else if (DealerCardsCount < SplitCardsCount && SplitStatus !== "bust" && SplitStatus !== "BJ") {
                setSplitHandStatus("wins!")
                win += (SplitBet * 2);
            }
            else if (DealerStatus === "BJ" && SplitStatus === "BJ") {
                setSplitHandStatus("push!")
                win += (SplitBet)
            }
            else if (DealerStatus === "BJ" && SplitStatus !== "BJ") {
                setSplitHandStatus("lose!")
            }
            else if (DealerStatus !== "BJ" && SplitStatus === "BJ") {
                setSplitHandStatus("wins with BJ!")
                win += (SplitBet * 2.5)
            }
            else if (DealerCardsCount === SplitCardsCount) {
                setSplitHandStatus("push")
                win += (SplitBet)
            }
        }
        
        setCash(cash + win);

        if(PlayerIsDouble === true) {
            setPlayerIsDouble(false);
            setBet(bet / 2);
        }

        if(SplitIsDouble === true) {
            setSplitIsDouble(false);
            setBet(SplitBet / 2);
        }
    }

    const changeBet = (e) => {
        const regex=/^[0-9]+$/;
        if (e.target.value.match(regex) && e.target.value > 0 && e.target.value <= cash)
        {
            setBet(+e.target.value);
        }
        else {
            setBet(0);
        }
    }
    return (
        loading === false ?
        <div>
            <div className="h4">Your Cash: {cash} <br/>
            Your Bet: {bet}</div>
            { 
                gameStatus !== "prep" ?
                    <div>
                        <div>
                            <DealersHand
                                cards={DealerCards}
                                count={DealerCardsCount}
                                show={gameStatus !== "running" && gameStatus !== "SplitTurn" ? true : false}
                            />
                        </div>
                        <div className="mt-2">
                            <Hand
                                cards={PlayerCards}
                                count={PlayerCardsCount}
                                status={PlayerHandStatus !== "" ? PlayerHandStatus : null}
                                softCount={softCount}
                            />
                        </div>
                        {PlayerStatus === "active" && !insuranceAv ?
                            <>
                                <br/>
                                <Button
                                    variant="outline-success"
                                    onClick={handleClickHit}
                                >
                                    HIT
                                </Button>{' '}
                                <Button
                                    variant="outline-danger"
                                    onClick={handleClickStand}
                                >
                                    STAND
                                </Button>{' '}
                                {
                                    (PlayerCardsCount > 6 && 
                                    PlayerCardsCount < 12 && 
                                    cash >= (bet * 2) &&
                                    PlayerCards.length === 2) ?
                                    <Button
                                        variant="outline-warning"
                                        onClick={handleClickDouble}
                                    >
                                        DOUBLE
                                    </Button>
                                    : null
                                }
                                {" "}
                                {
                                    checkSplit(PlayerCards) &&
                                    cash >= bet &&
                                    PlayerCards.length === 2 &&
                                    !split ?
                                    <Button
                                        variant="outline-primary"
                                        onClick={handleClickSplit}
                                    >
                                        SPLIT
                                    </Button>
                                    : null
                                }
                                <br/>
                            </>
                            : null
                        }
                        {split 
                            ? 
                            <Hand 
                                cards={SplitCards}
                                count={SplitCardsCount}
                                status={SplitHandStatus !== "" ? SplitHandStatus : null}
                                softCount={softCountSplit}
                            /> 
                        : null} 
                        {gameStatus === "SplitTurn" && !insuranceAv ?
                            <>
                                <br/>
                                <Button
                                    variant="outline-success"
                                    onClick={handleClickHit}
                                >
                                    HIT
                                </Button>{' '}
                                <Button
                                    variant="outline-danger"
                                    onClick={handleClickStand}
                                >
                                    STAND
                                </Button>{' '}
                                {
                                    (SplitCardsCount > 6 && 
                                    SplitCardsCount < 12 && 
                                    cash >= (bet * 2) &&
                                    SplitCards.length === 2) ?
                                    <Button
                                        variant="outline-warning"
                                        onClick={handleClickDouble}
                                    >
                                        DOUBLE
                                    </Button>
                                    : null
                                }
                            </>
                            : null
                        }
                        {newDeck ? <div>Shoe change after this round</div> : null}
                        {DealerNoBJ ? <div>Dealer has no Blackjack!</div> : null}
                        {insuranceAv ?
                            <div>
                                <br/>
                                Versicherung ist möglich
                                <br/>
                                <Button 
                                    variant="outline-success"
                                    onClick={handleClickInsurance}
                                >
                                    YES, PLEASE!
                                </Button>
                                {" "}
                                <Button 
                                    variant="outline-danger"
                                    onClick={handleClickNoInsurance}
                                >
                                    NO, THANKS!
                                </Button>
                            </div>
                            : null
                        }
                    </div> 
                : null
            }
               
            {
                gameStatus === "prep" || gameStatus === "finish" ?
                <>
                <div>
                <BetInput
                    onChange={changeBet}
                    value={bet}
                />
                Enter Bet and push Deal</div> 
                <Button
                    variant="primary"
                    disabled={bet > 0 && bet <= cash && !saving ? false : true}
                    onClick={!isDealing ? handleClickDeal : null}
                >
                    {isDealing ? 'dealing...' : saving ? "saving..." : 'Deal!'}
                </Button>
                </>
                : null
            }            
        </div>
        : "loading...");
}
export default Blackjack;