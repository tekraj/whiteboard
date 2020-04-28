import React from "react";
import { BrowserRouter as Router, Route, Switch } from "react-router-dom";
import logo from "./logo.svg";
import "./App.css";
import Login from "./components/auth/login";
import Dashboard from "./components/dashboard/dashboard";
function App() {
  return (
    <div className="App">
      <Router>
        <Route exact path="/" component={Login} />
        <Switch>
          <Route exact path="/dashboard" component={Dashboard} />
        </Switch>
      </Router>
    </div>
  );
}

export default App;
