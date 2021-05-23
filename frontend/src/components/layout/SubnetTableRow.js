import React from "react";

export default class SubnetTableRow extends React.Component {

  render = () => {
    return (
      <tr>
        <th scope="row">{this.props.rowKey}</th>
        <td>{this.props.rowValue}</td>
      </tr>
    );
  };
}
