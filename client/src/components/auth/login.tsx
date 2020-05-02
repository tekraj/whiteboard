import React, { Component } from "react";
import { GoogleLogin } from "react-google-login";
import { withRouter } from "react-router-dom";
import { loginuser } from "../../actions/authAction";
import { connect } from "react-redux";

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
  signup(profileObj: any) {
    console.log(profileObj);
    this.props.loginuser(profileObj);
    this.props.history.push("/");
  }
  responseGoogle = (response: any) => {
    this.signup(response.profileObj);
  };
  componentWillMount() {
    const { isAuthenticated } = this.props.auth ? this.props.auth : false;
    if (isAuthenticated) {
      this.props.history.push("/");
    }
  }
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
const mapStateToProps = (state: any) => ({
  auth: state.auth,
  errors: state.errors,
});

export default connect(mapStateToProps, { loginuser })(Login);
