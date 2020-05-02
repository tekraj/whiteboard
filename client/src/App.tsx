import React from "react";
import { BrowserRouter as Router, Route, Switch } from "react-router-dom";
import logo from "./logo.svg";
import "./App.css";
import Login from "./components/auth/login";
import Dashboard from "./components/dashboard/dashboard";
import { Provider } from "react-redux";
import store from "./store";
import { setCurrentUser } from "./actions/authAction";
import jwt from "jwt-simple";

if (localStorage.jwtToken) {
  //decode the token
  var decoded = jwt.decode(localStorage.jwtToken, "secret");
  //dispatch the user
  store.dispatch(setCurrentUser(decoded));
  // Check for expired token
}
function App() {
  return (
    <Provider store={store}>
      <div className="App">
        <Router>
          <Route exact path="/login" component={Login} />
          <Switch>
            <Route exact path="/" component={Dashboard} />
          </Switch>
        </Router>
      </div>
    </Provider>
  );
}

export default App;
