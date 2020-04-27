import React, { Component } from "react";
import { GoogleLogin } from "react-google-login";
import { Redirect } from "react-router-dom";

export class Login extends Component {
  constructor(props: any) {
    super(props);
    this.state = {
      name: "",
      email: "",
      imageUrl: "",
      token: null,
    };
  }
  responseGoogle = (response: object) => {
    console.log("this is response", response);
    return <Redirect to="/dashboard" />;
  };
  render() {
    return (
      <GoogleLogin
        clientId="779634656077-oh0ehj7kv2ddk3b66cgm22v6n65bhnp7.apps.googleusercontent.com"
        onSuccess={this.responseGoogle}
        onFailure={this.responseGoogle}
        cookiePolicy={"single_host_origin"}
      />
    );
  }
}

export default Login;
