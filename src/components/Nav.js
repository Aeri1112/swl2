import React from "react";
import { Link } from 'react-router-dom';
import '../bootstrap.min.css';

const Nav = () => {

  return (
  <nav className="navbar navbar-expand-lg navbar-dark bg-dark align-items-start">
    <div className="container-fluid">
    <a className="navbar-brand" href="#">JTG</a>
    <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span className="navbar-toggler-icon"></span>
    </button>
    <div className="collapse navbar-collapse" id="navbarNavDropdown">
      <ul className="navbar-nav">
        <li className="nav-item dropdown">
          <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Charakter
      </a>
          <div className="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <Link to="/overview" className="dropdown-item">
                Überblick
              </Link>
              <Link to="/abilities" className="dropdown-item">
                Skills
              </Link>
          </div>
        </li>
        <li className="nav-item dropdown">
          <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Stadt
      </a>
          <div className="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a className="dropdown-item" href="#">Überblick</a>
            <a className="dropdown-item" href="#">Apartment</a>
            <a className="dropdown-item" href='#'>Bar</a>
            <a className="dropdown-item" href='#'>Arena</a>
            <a className="dropdown-item" href='#'>Layer</a>
          </div>
        </li>
        <li className="nav-item">
          <a className="nav-link" href='#'>Nachrichten</a>
        </li>
      <li className="nav-item">
          <a className="nav-link" href="#">Allianz</a>
        </li>
      <li className="nav-item dropdown">
          <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Einstellungen
          </a>
          <div className="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a className="dropdown-item" href="#">Kampf</a>
            <a className="dropdown-item" href="#">Ausbildung</a>
            <a className="dropdown-item" href="#">Account</a>
          </div>
        </li>
      <li className="nav-item dropdown">
          <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Bugs
          </a>
          <div className="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a className="dropdown-item" href="#">auflisten</a>
            <a className="dropdown-item" href="#">melden</a>
          </div>
        </li>
      <li className="nav-item dropdown">
          <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Statistiken
          </a>
          <div className="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a className="dropdown-item" href='#'>Spieler-Rangliste</a>
            <a className="dropdown-item" href="#">Allianz-Rangliste</a>
            <a className="dropdown-item" href="#">Statistiken</a>
          </div>
        </li>
        <li className="nav-item dropdown">
          <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Events
          </a>
          <div className="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a className="dropdown-item" href='#'>Ranglisten-Wettkampf</a>
          </div>
        </li>
      <li className="nav-item">
          <a className="nav-link" href='#'>Logout</a>
        </li>
      </ul>
    </div>
    </div>
</nav>
);
}

export default Nav;