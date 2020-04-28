import React, { Component } from "react";

export default class dashboard extends Component {
  state = { data: [] };

  componentDidMount() {
    this.getTestQuery();
  };

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
    const data = this.state.data.map((item: any) =>
      <p key={item.id}>{item.id}. {item.firstname} {item.lastname}</p>
    )
    return (
      <div>
        <p>Hello welcome to dashboard page</p>
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
