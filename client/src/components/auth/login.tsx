import React, { Component } from "react";
import { GoogleLogin } from "react-google-login";
import { Redirect } from "react-router-dom";

export class Login extends Component<any> {
  state = {
    data: [],
    name: "",
    email: "",
    imageUrl: "",
    token: null,
  };

  componentDidMount() {
    this.getTestQuery();
  }

  async getTestQuery() {
    const API_URL = process.env.REACT_APP_API_URL;
    const API_BASE_SLUG = process.env.REACT_APP_API_BASE_SLUG;

    const API_BASE = `${API_URL}/${API_BASE_SLUG}`;

    fetch(`${API_BASE}/test_query`)
      .then((data) => {
        return data.json();
      })
      .then((res) => {
        this.setState({ data: res });
      });
  }
  GOOGLE_CLIENT_ID: string = process.env.REACT_APP_GOOGLE_CLIENT_ID!;
  signup(profileObj: any) {
    console.log(profileObj);
    this.props.history.push("/dashboard");
  }
  responseGoogle = (response: any) => {
    console.log("this is response", response);
    this.signup(response.profileObj);
  };
  render() {
    const data = this.state.data.length
      ? this.state.data.map((item: any) => (
          <p key={item.id}>
            {item.id}. {item.firstname} {item.lastname}
          </p>
        ))
      : null;
    return (
      <React.Fragment>
        <GoogleLogin
          clientId={this.GOOGLE_CLIENT_ID}
          onSuccess={this.responseGoogle}
          onFailure={this.responseGoogle}
          cookiePolicy={"single_host_origin"}
        />
        {(() => {
          if (this.state.data.length) {
            return (
              <div>
                <h1>From database:</h1>
                <ol>{data}</ol>
              </div>
            );
          } else {
            return <p>No Data!</p>;
          }
        })()}
      </React.Fragment>
    );
  }
}

export default Login;
