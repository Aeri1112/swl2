import React from "react";

class Shoe extends React.Component {

    createDeck () {

        this.shoe = [];
        let suits = ["hearts", "spades", "diamonds", "clubs"];
        let values = [2,3,4,5,6,7,8,9,10,"jack","queen","king","ace"];
        this.numberOfDecks = 6;

        for(let deck = 0; deck < this.numberOfDecks; deck++) {
            for(let suit of suits) {
                for(let value of values) {
                this.shoe.push([suit=suit, value=value])
                }
            }
        }
        this.shuffle();  
        return this.shoe;
    }

    getDeck() {
        const shoe = this.createDeck(this.suits, this.values);
        return shoe;
    }

    shuffle() {
        let counter = this.shoe.length, temp, i;

        while(counter) {
            i = Math.floor(Math.random() * counter--);
            temp = this.shoe[counter];
            this.shoe[counter] = this.shoe[i];
            this.shoe[i] = temp;
        }

        return this.shoe;
    }

    deal() {
        return this.shoe.pop()
    }

    render() {
        return (<></>);
    }
}

export default Shoe;