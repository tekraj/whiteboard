import React, { Component } from "react";
import { connect } from "react-redux";

class Dashboard extends Component<any> {
  constructor(props: any) {
    super(props);
  }
  componentWillMount() {
    const { isAuthenticated } = this.props.auth ? this.props.auth : false;
    console.log(
      "Dashboard -> componentWillMount -> this.props",
      isAuthenticated
    );

    if (!isAuthenticated) {
      this.props.history.push("/login");
    }
  }
  render() {
    const { user } = this.props.auth ? this.props.auth : false;

    return (
      <div>
        <p>Hello {user.name} welcome to dashboard page</p>
      </div>
    );
  }
}

const mapStateToProps = (state: any) => ({
  isAuthenticated: state.isAuthenticated,
  auth: state.auth,
  errors: state.errors,
});

export default connect(mapStateToProps)(Dashboard);
