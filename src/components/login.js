import React, { useState } from "react";
import { Button, FormGroup, FormControl } from "react-bootstrap";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  function validateForm() {
    return email.length > 0 && password.length > 0;
  }
  
  async function handleSubmit(event) {
    event.preventDefault();

    try {
      await fetch("http://localhost/new_jtg/api/accounts/login", {
          method: "POST",
          body: JSON.stringify({
            accountname: "Aeri1112",
            password: "1234"
          })
      })
    } catch (e) {
      alert(e.message);
    }
  }
  

  return (
    <div className="Login">
      <form onSubmit={handleSubmit}>
        <FormGroup controlId="accountname" bssize="large">
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
        </FormGroup>
        <Button block bssize="large" disabled={!validateForm()} type="submit">
          Login
        </Button>
      </form>
    </div>
  );
}