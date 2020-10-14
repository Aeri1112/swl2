import React, { useState } from "react";
import { Button, FormGroup, FormControl, Modal } from "react-bootstrap";
import {GET, POST, setJwtToken} from "../tools/fetch";

// ich hab hier n bissl was veraendert, aber nix tragisches.

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  // data wird als json string dann angezeigt, auch fuer fehler
  const [data, setData] = useState(null)
  const [loading, setLoading] = useState(false)

  function validateForm() {
    return email.length > 0 && password.length > 0;
  }
  
  
  async function handleSubmit(event) {
    event.preventDefault();
    // hier habe das ergebnis des logins gehandlet
    try {
      if (loading) return
      setLoading(true)
      const data = await POST('/test/login', {accountname: "Aeri1112", password: "1234"})
      setData(data)
      setJwtToken(data.token) // hier nutze ich die function um den token global zu setzen.
      // await fetch("http://localhost/new_jtg/api/accounts/login", {
      //     method: "POST",
      //     body: JSON.stringify({
      //       accountname: "Aeri1112",
      //       password: "1234"
      //     })
      // })
    } catch (e) {
      setData(e)
    } finally {
      setLoading(false)
    }
  }
  
  const testAuth = async () => {
    // funktion zum testen ob man eingelogt ist
    // wenn nicht, wird der fehler ausgegeben mit code 401
    // sonst der entsprechende inhalt
    try {
      if (loading) return
      setLoading(true)
      const respone = await GET('/test/auth')
      setData(respone)
    } catch (e) {
      setData(e)
    } finally {
      setLoading(false)
    }
  }
  
  const logout = () => {
    setJwtToken(null) // hier nutze ich sie, um den token wieder zu entziehen.
    // wenn keine aktion auf dem server notwendig ist, reicht das hier auch.
    setData('logout done')
  }

  
    // noch ein paar kleine anpassung fuer das testen
  return (
    <div className="Login">
      {loading && <div>LADE DATEN ...</div>}
      <form onSubmit={handleSubmit}>
        {/*<FormGroup controlId="accountname" bssize="large">
          <>Email</>
          <FormControl
            autoFocus
            type="text"
            value={email}
            onChange={e => setEmail(e.target.value)}
          />
        </FormGroup>
        <FormGroup controlId="password" bssize="large">
          <>Password</>
          <FormControl
            value={password}
            onChange={e => setPassword(e.target.value)}
            type="password"
          />
        </FormGroup>*/}
        <Button block bssize="large" type="submit" style={{marginTop: 25}}>
          {loading ? 'lade...' : 'Login'}
        </Button>
        
        <Button style={{marginTop: 25}} onClick={testAuth} type={'button'} block>
          {loading ? 'lade' : 'Test Auth'}
        </Button>
  
        <Button style={{marginTop: 25}} onClick={logout} type={'button'} block>
          {loading ? 'lade' : 'Logout'}
        </Button>
      </form>
      <pre  style={{marginTop: 25}}>
        <h3>Daten:</h3>
        <pre>{JSON.stringify(data, null, 2)}</pre>
      </pre>
    </div>
  );
}