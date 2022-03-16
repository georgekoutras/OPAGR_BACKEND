import React from "react";
import {NavLink} from "react-router-dom";

import '../../../sass/app.scss';

const Menu = (props) => {
    return (
        <nav className="side-menu">
            <ul>
                <li>
                    <NavLink activeClassName="active" to='/home'>Αρχική</NavLink>
                </li>
                <li>
                    <NavLink activeClassName="active" to='/users'>Χρήστες</NavLink>
                </li>
                <li>
                    <NavLink activeClassName="active" to='/cultivation-types'>Είδη Καλλιεργειών</NavLink>
                </li>
                <li>
                    <NavLink activeClassName="active" to='/cultivations'>Καλλιέργειες</NavLink>
                </li>
                <li>
                    <NavLink activeClassName="active" to='/devices'>Συσκευές</NavLink>
                </li>
                <li>
                    <NavLink activeClassName="active" to='/notifications'>Ειδοποιήσεις</NavLink>
                </li>
            </ul>
        </nav>
    );
}

export default Menu;