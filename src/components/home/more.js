import React from 'react';
import {Modal, ModalBody } from "react-bootstrap";
import ModalHeader from 'react-bootstrap/esm/ModalHeader';

const About = (props) => {
    return ( 
        <Modal show={props.show} onHide={() => props.handleAbout()}>
            <ModalHeader>
                Mehr über Star Wars Legends
            </ModalHeader>
            <ModalBody className="text-justify">
                'Star Wars Legends' ist ein textbasiertes RPG, 
                dass man im Browser spielt. Es basiert auf den Geschichten von den Jedi und Sith, 
                in einer weit entfernten Galaxie, auf einem tristen und feindlichen Schmugglermond.
                <br/><br/>
                "In 'Star Wars Legends' &uuml;bernimmt man die Rolle eines Jedi/Sith.
                Das Ziel ist es der schnellste, st&auml;rkste und beste Jedi im Universum zu werden, 
                aber das ist leichter gesagt als getan. Auf dich warten viele Gegner gegen die du bestehen musst.<br/>
                Verschiedene Kreaturen und viele andere Kämpfer, die genau wie du die st&auml;rksten sein wollen.
                Du kannst in der Arena versuchen im fairen Kampf gegen deine Konkurrenten zu bestehen.
                Finde es heraus, aber pass auf, dass du nicht der dunklen Seite der Macht verf&auml;llst, denn dann gibt es kein zur&uuml;ck mehr. 
                Nur hier wirst du erfahren, ob du es w&uuml;rdig bist ein Jedi Ritter zu werden.M&ouml;ge die Macht mit dir sein!";

                <br/><br/>
                Einige Hauptmerkmale im &Uuml;berblick:<br/>
                - 8 Grundf&auml;higkeiten, 8 Talente und 24 M&auml;chte<br/>
                - 5 Gegenstandsarten mit unz&auml;hligen Variationen und Werte<br/>
                - K&auml;mpfe gegen andere Jedi - auch in Gruppen<br/>
                - Erkunde die Stadt, k&auml;mpfe in Dungeons<br/>
                - Passe das Kampfverhalten deines Jedi deinen Vorstellungen an<br/>
                - Treibe Handel, gr&uuml;nde eine Allianz...<br/>
            </ModalBody>
        </Modal>
     );
}
 
export default About;