import React, { Component } from "react";
import { connect } from "react-redux";

class Dashboard extends Component<any> {
  constructor(props: any) {
    super(props);
  }
  state = { data: [] };

  componentDidMount() {
    this.getTestQuery();
  };

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
  async getTestQuery() {
    const API_URL = process.env.REACT_APP_API_URL;
    const API_BASE_SLUG = process.env.REACT_APP_API_BASE_SLUG;

    const API_BASE = `${API_URL}/${API_BASE_SLUG}`

    fetch(`${API_BASE}/test_query`)
      .then(data => {
        return data.json()
      })
      .then(res => {
        this.setState({ data: res })
      })
  };

  render() {
    const { user } = this.props.auth ? this.props.auth : false;
    const data = this.state.data.map((item: any) =>
      <p key={item.id}>{item.id}. {item.firstname} {item.lastname}</p>
    )
    return (
      <div>
        <p>Hello {user.name} welcome to dashboard page</p>
        {(() => {
          if (this.state.data.length) {
            return (
              <div>
                <h1>From database on dashboard!:</h1>
                <ol>
                  {data}
                </ol>
              </div>
            )
          }
          else {
            return <p>No Data!</p>
          }
        })()}
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
