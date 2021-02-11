import React, { useEffect, useState } from "react";
import {useDispatch} from "react-redux";
import {GET, setJwtToken} from "../tools/fetch";
import { Link, Redirect } from 'react-router-dom';
import { NavLink } from "react-bootstrap";

const Nav = () => {

  const dispatch = useDispatch();

  const [rfp, setRfp] = useState();
  const [rsp, setRsp] = useState();
  const [redirect, setRedirect] = useState(false);

  const checkSkillpoints = async (points) => {
    const response = await GET(`/character/points/${points}`)
    if(response.points != null) {
      if(points === "rfp") {
        setRfp(response.points.rfp)
      }
      else if (points === "rsp") {
        setRsp(response.points.rsp)
      }
    }
  }

  const handleLogout = () => {
      setJwtToken(null) // hier nutze ich sie, um den token wieder zu entziehen.
      // wenn keine aktion auf dem server notwendig ist, reicht das hier auch.
      setRedirect(true)
      dispatch({type:"IS_AUTH",payload:{isAuth:"false"}});
  }

  useEffect(() => {
    checkSkillpoints("rfp");
    checkSkillpoints("rsp");
  }, []);

  return (
    <div>
      {redirect === true && 
        <Redirect push to="/login" />
        /*window.location.assign("/login")*/
      }
      <nav className="navbar navbar-expand-lg navbar-dark bg-dark align-items-start">
        <div className="container-fluid">
        <Link to="/overview" className="navbar-brand">JTG</Link>
        <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span className="navbar-toggler-icon"></span>
        </button>
        <div className="collapse navbar-collapse" id="navbarNavDropdown">
          <ul className="navbar-nav">
            <li className="nav-item dropdown">
              <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Charakter {rsp > 0 || rfp > 0 ? <span className="text-danger"> !</span> : null}
          </a>
              <div className="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <Link to="/overview" className="dropdown-item">
                    Überblick
                  </Link>
                  <Link to="/inventory" className="dropdown-item">
                    Ausrüstung
                  </Link>
                  <Link to="/abilities" className="dropdown-item">
                    Fähigkeiten {rsp > 0 ? <span className="text-danger"> !</span> : null}
                  </Link>
                  <Link to="/forces" className="dropdown-item">
                    Mächte {rfp > 0 ? <span className="text-danger"> !</span> : null}
                  </Link>
              </div>
            </li>
            <li className="nav-item dropdown">
              <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Stadt
          </a>
              <div className="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <Link to="/city" className="dropdown-item">
                    Überblick
                  </Link>
                  <Link to="/apartment" className="dropdown-item">
                    Apartment
                  </Link>
                  <Link to="/bar" className="dropdown-item">
                    Bar
                  </Link>
                  <Link to="/arena" className="dropdown-item">
                    Arena
                  </Link>
                  <Link to="/casino" className="dropdown-item">
                    Casino
                  </Link>
                  <Link to="/layer" className="dropdown-item">
                    Layer
                  </Link>
              </div>
            </li>
            <li className="nav-item">
              <a className="nav-link" href='#'>Nachrichten</a>
            </li>
          <li className="nav-item">
              <Link to="/alliance" className="nav-link">Allianz</Link>
            </li>
          <li className="nav-item dropdown">
              <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Einstellungen
              </a>
              <div className="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <Link to="/pref" className="dropdown-item">
                    Kampf
                  </Link>
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
              <NavLink className="nav-link" onClick={handleLogout}>Logout</NavLink>
            </li>
          </ul>
        </div>
        </div>
      </nav>
    </div>
);
}

export default Nav;