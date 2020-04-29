import React, { Component } from "react";
import { GoogleLogin } from "react-google-login";

export class Login extends Component<any> {
  constructor(props: any) {
    super(props);
    this.state = {
      name: "",
      email: "",
      imageUrl: "",
      token: null,
    };
  }
  signup(res: object) {
    console.log(res);
    this.props.history.push("/dashboard");
  }
  responseGoogle = (response: object) => {
    this.signup(response);
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
